<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Nicepay\ProcessUrl;
use App\Models\Nicepay\CheckTransactionStatus;
use App\Models\Nicepay\EnterpriseRegistration;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;

use Modules\Nicepay\Libraries\NicepayProfessional;

class NicepayController extends Controller
{
    protected $nicepayProfessional;
    public function __construct()
    {
        $this->nicepayProfessional = new NicepayProfessional;
    }
    
    public function store(Request $request)
    {
        $dbProcessUrl= new ProcessUrl;
        $dbProcessUrl->fill($request->input());
        $dbProcessUrl->data = json_encode($request->input());
        $dbProcessUrl->save();

        if ($dbProcessUrl) {
            // 2. Check Transaction Status
            $pushedToken = $dbProcessUrl->merchantToken;
            //dd($pushedToken);
            $this->nicepayProfessional->set('tXid', $dbProcessUrl->tXid);
            $this->nicepayProfessional->set('referenceNo', $dbProcessUrl->referenceNo);
            $this->nicepayProfessional->set('amt', $dbProcessUrl->amt);
            $this->nicepayProfessional->set('iMid', config('nicepay.imid'));
            $merchantToken = $this->nicepayProfessional->merchantTokenC();
            
            $this->nicepayProfessional->set('merchantToken', $merchantToken);
            // 2.1 Request To Nicepay
            $paymentStatus = $this->nicepayProfessional->checkPaymentStatus($dbProcessUrl->tXid, $dbProcessUrl->referenceNo, $dbProcessUrl->amt);

            $dbProcessUrl->request_log = 'txid: ' . $dbProcessUrl->tXid . 'ref: ' . $dbProcessUrl->referenceNo . 'amt: ' . $dbProcessUrl->amt;
            $dbProcessUrl->save();

            // 2.2 Response From Nicepay
            if ($pushedToken == $merchantToken) {
                // 2.2.1 Insert into CheckTransactionStatus
                $checkTransactionStatus = new CheckTransactionStatus;
                //dd($checkTransactionStatus);
                $checkTransactionStatus->fill((array) $paymentStatus);
                $checkTransactionStatus->data = json_encode($paymentStatus);
                $checkTransactionStatus->save();

                if (isset($paymentStatus->payment_status) && $paymentStatus->payment_status == 0) {
                    $db_order                 = Order::where('oc_number', $checkTransactionStatus->referenceNo)->first();
                    $db_order->status         = "Closed";
                    $db_order->payment_date   = Carbon::parse($checkTransactionStatus->transDt . $checkTransactionStatus->transTm);
                    $db_order->payment_status = 1;
                    $db_order->save();
                    $order_detail = OrderDetail::where('order_id', $db_order->id)->get();
                    $pesan = '
                        <style type="text/css">
                            body {
                                margin: 0;
                                font-family: Muli,-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
                                font-weight: 400;
                                line-height: 1.5;
                                font-size: 10px;
                            }
                            .text-center {
                                text-align: center;
                            }
                            .text-right {
                                text-align: right;
                            }
                            .text-left {
                                text-align: left;
                            }
                        
                            .table-bordered, .table-bordered td, .table-bordered th {
                                border: 1px solid #b0b0b0
                            }
                            .table-bordered2 {
                                border: 1px solid #b0b0b0
                            }
                            .ml-2 {
                                margin-left:2px;
                            }
                            .bg-th {
                                background-color: #dfdfe1;
                            }
                            .pb-3 {
                                padding-bottom: 3px;
                            }
                        </style>
                        <body>
                            <table class="table-bordered2" width="50%">
                                <tr>
                                    <td><b>Order Confirmation</b><br/>
                                    '.$db_order->oc_number.'
                                    </td>
                                </tr>
                            </table>
                            <br/>
                            <table class="pb-3 text-left">
                                <tbody>
                                    <tr>
                                        <th width="60px">Date</th>
                                        <th width="15px"> : </th>
                                        <td>'.Carbon::parse($db_order->order_date)->formatLocalized("%B, %d %Y").'</td>
                                    </tr>
                                    <tr>
                                        <th>Ship To</th>
                                        <th> : </th>
                                        <td>'.$db_order->customer_name.'</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <th> : </th>
                                        <td>'.$db_order->customer_phone.'</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <th> : </th>
                                        <td>'.$db_order->customer_address.'</td>
                                    </tr>
                                    <tr>
                                        <th>Note</th>
                                        <th> : </th>
                                        <td>'.$db_order->order_notes.'</td>
                                    </tr>
                                </tbody>
                            </table>
                            <br/>
                            <table class="table-bordered" width="100%">
                                <tr>
                                    <th class="text-center bg-th">No.</th>
                                    <th class="text-center bg-th">Product Code</th>
                                    <th class="text-center bg-th">Product Name</th>
                                    <th class="text-center bg-th">Price (IDR)</th>
                                    <th class="text-center bg-th">Qty</th>
                                    <th class="text-center bg-th">Total</th>
                                </tr>';
                                $nomor = 1;
                                foreach($order_detail as $order_details) {
                                $pesan .='
                                <tr>
                                    <td class="text-center">'.$nomor++.'</td>
                                    <td class="ml-2">'.$order_details->products->sku.'</td>
                                    <td class="ml-2">'.$order_details->name.'</td>
                                    <td class="ml-2 text-right">'. number_format($order_details->price/1.11).'</td>
                                    <td class="ml-2 text-center">'. $order_details->qty.'</td>
                                    <td class="text-right">'.number_format($order_details->sub_total/1.11).'</td>
                                </tr>';
                                }
                                $pesan .='
                                <tr>
                                    <td colspan="5" class="text-right">Sub Total (IDR)</td>
                                    <td class="text-right">'.number_format($db_order->grand_total/1.11).'</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right">PPN 11%</td>
                                    <td class="text-right">'.number_format($db_order->grand_total/1.11*0.11).'</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right">Grand Total (IDR)</td>
                                    <td class="text-right">'.number_format($db_order->grand_total,0).'</td>
                                </tr>
                            </table>
                            <p>&nbsp;</p>
                            <table width="100%">
                                <tr>
                                    <th class="text-center">Haldin appreciate your business,</th>
                                    <th class="text-center">Customer Approved by,</th>
                                </tr>
                                <tr>
                                    <th class="text-center">&nbsp;</th>
                                    <th class="text-center">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th class="text-center"><i>'.$db_order->sales_name.'</i></th>
                                    <th class="text-center">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th class="text-center">&nbsp;</th>
                                    <th class="text-center">&nbsp;</th>
                                </tr>
                                <tr>
                                    <td class="text-center" style="text-decoration: underline;">'.$db_order->sales_name.'</td>
                                    <td class="text-center" style="text-decoration: underline;">'.$db_order->customer_name.'</td>
                                </tr>
                            </table>
                            <p>&nbsp;</p>
                            <table class="table-bordered2" width="50%">
                                <tr>
                                    <td><b>Please transfer to :</b><br/>
                                    PT. Haldin Pacific Semesta <br/>
                                    Bank Central Asia <br/>
                                    Bank Account No : 869.11-8888.4
                                    </td>
                                </tr>
                            </table>
                            <table width="100%">
                            <tr>
                                <td><b><a href='.route("frontend.download_invoice", $db_order->oc_number).'>Download Invoice</a></b></td>
                            </tr>
                            </table>
                        </body>
                    '; 
                    $mail = new PHPMailer(true);
                    try {
                        $mail->IsHTML(true);
                        $mail->CharSet = 'UTF-8';
                        $mail->IsSMTP();
                        $mail->SMTPAuth = true;
                        $mail->SMTPSecure = 'tls';
                        $mail->Host = 'mail.myhaldin.com';
                        $mail->Port = 587;
                        $mail->Username = 'administrator@myhaldin.com'; // user email
                        $mail->Password = '!!@dminHaldin2022'; // password email
                        $mail->setFrom('administrator@myhaldin.com', 'CMS Haldin'); // user email
                        $mail->addReplyTo('administrator@myhaldin.com', 'CMS Haldin'); //user email
                  
                        // $mail->AddAddress("salessupport.haldinfoods@myhaldin.com");
                        // $mail->addCC("basmalah.ghufthy@myhaldin.com");
                        // $mail->addCC("eko.yunianto@myhaldin.com");
                        // $mail->addCC("richardo.noya@myhaldin.com");
                        $mail->addCC("benny.wijaya@myhaldin.com");
                        // $mail->addCC("ali.muntaha@myhaldin.com");
                        // $mail->addCC("ita.irawati@haldin-natural.com");
                        	
                        $mail->Subject = 'Order Confirmation '.$db_order->oc_number.' '.$db_order->customer_name;
                        $mail->Body = $pesan;
                        $mail->send();
                    } catch (Exception $e) {
                        return response()->json([
                            'type'      => 'warning',
                            'message'   => $e->getMessage()
                        ]);
                    }
					
                } elseif (isset($paymentStatus->status) && $paymentStatus->status == '1') {
                    echo '<pre>Void</pre>';
                } elseif (isset($paymentStatus->status) && $paymentStatus->status == '2') {
                    echo '<pre>Refund</pre>';
                } elseif (isset($paymentStatus->status) && $paymentStatus->status == '9') {
                    echo '<pre>Reversal</pre>';
                } else {
                    echo '<pre>Status Unknown</pre>';
                }
            }
        } else {
            echo '<pre>Cant Create dbProcessUrl</pre>';
        }
    }
}
