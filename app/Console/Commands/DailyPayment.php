<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class DailyPayment extends Command
{
    protected $signature = 'payment:daily';

    protected $description = 'Respectively send an exclusive quote to everyone daily via email.';

    public function __construct()
    {
        parent::__construct();
    }

 public function handle()
    {
        try {
            $cekReminds = FadCaReminder::where([
                'reminder1_date' => Carbon::now()->format("Y-m-d"),
                'reminder1_status' => 0
            ]);
            if($cekReminds->count() > 0) {
                $pesan = "
                <html> 
                    <head> 
                        <title>Email Pemberitahuan</title> 
                    </head> 
                    <body>";
            
                        $pesan .="
                        <table cellspacing='0' align='left' style='width:100%;font-size: 14px;margin-bottom: 15px;border-collapse: collapse;text-align: left;font-family:cursive;' cellpadding='0' border='0'>	       
                            <tr>
                                <td>
                                    Hi All,<br/>
                                    Melalui email ini, administrator memberitahukan bahwa anda telah memasuki tahap reminder I.<br/>
                                    Mohon segera melakukan realiasi cash advance reguler atau business trip melalui link berikut <b><a href=".route('dataUserCA', 'Released').">CMS Myhaldin.</a></b>
                                </td>
                            </tr>
                        </table>
                        <table cellspacing='0' align='left' style='width:100%;font-size: 14px;margin-bottom: 10px;border-collapse: collapse;text-align: left;font-family:cursive;' cellpadding='0' border='0'>	       
                            <tr>
                                <th>-- 📢 Note 📢 --</th>
                            </tr>
                        </table>
                        <table cellspacing='0' align='left' style='width:100%;font-size: 14px;margin-bottom: 15px;border-collapse: collapse;text-align: left;font-family:cursive;' cellpadding='0' border='0'>	       
                            <tr>
                                <td style='vertical-align: baseline;'>1.</td>
                                <th style='width:12%;vertical-align: baseline;'>Reminder I</th>
                                <th style='width:2%;vertical-align: baseline;'> : </th>
                                <td>Sistem otomatis akan menginformasikan kepada users yang belum melakukan realiasi.</td>
                            </tr>
                            <tr>
                                <td style='vertical-align: baseline;'>2.</td>
                                <th style='vertical-align: baseline;'>Reminder II</th>
                                <th style='vertical-align: baseline;'> : </th>
                                <td>Sistem otomatis akan menginformasikan kepada users yang belum melakukan realiasi dan sistem otomatis akan mengunci pengajuan CA user tersebut.</td>
                            </tr>
                            <tr>
                                <td style='vertical-align: baseline;'>3.</td>
                                <th style='vertical-align: baseline;'>Reminder III</th>
                                <th style='vertical-align: baseline;'> : </th>
                                <td>Sistem otomatis akan menginformasikan kepada users yang belum melakukan realiasi.<br/>
                                Dan 1 hari berikutnya, sistem akan mengkunci pengajuan cash advance user tersebut dan departement yang terkait dengan user tersebut.
                                </td>
                            </tr>
                        </table>
                        <table cellspacing='0' align='left' style='width:100%;font-size: 14px;margin-bottom: 15px;border-collapse: collapse;text-align: left;font-family:cursive;' cellpadding='0' border='0'>	       
                            <tr>
                                <td>Demikian informasi ini disampaikan, terima kasih atas kerja samanya. 
                                <br/><br/><b>Administrator</b>
                                </td>
                            </tr>						      
                        </table>";
                $pesan .="</body> 
                </html>"; 
            
                $mail = new PHPMailer(true);
                try {
                    $mail->CharSet = 'UTF-8';
                    $mail->IsHTML(true);
                    $mail->IsSMTP();
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = 'tls';
                    $mail->Host = 'mail.myhaldin.com';
                    $mail->Port = 587;
                    $mail->Username = 'administrator@myhaldin.com'; // user email
                    $mail->Password = '!!@dminHaldin2022'; // password email
                    $mail->setFrom('administrator@myhaldin.com', 'CMS Haldin'); // user email
                    $mail->addReplyTo('administrator@myhaldin.com', 'CMS Haldin'); //user email
                    // $mail->AddAddress("benny.wijaya@myhaldin.com");
                    foreach($cekReminds->get() as $reminds) {
                        $mail->AddAddress($reminds->user->email);
                    }
                    $mail->Subject = '⏳ Reminder I untuk Melakukan Realisasi CA';
                    $mail->Body = $pesan;
                    $mail->send();

                    foreach($cekReminds->get() as $remind) {
                        $remind->reminder1_status = 1;
                        $remind->save();
                    }   
                    DB::commit();
                    $this->info('Successfully sent daily reminder I for realization cash advance to everyone.');
                
                } catch (Exception $e) {
                    $this->info($e->getMessage());
                }
            } else {
                $this->info('On this day there is no reminder for realization cash advance.');
            }
        } catch (Exception $e) {
           $this->info($e->getMessage());
        }
        $checkTransaction = Order::where(['status' => 'Pending', 'payment_status' => 1]);
        if($checkTransaction->count() > 0) {
              foreach ($checkTransaction->get() as $trans) {
                $updateOrder = Order::where('id', $trans->id)->first();
                if ($updateOrder) {
                    $socialMedia = SocialMedia::all();
                    $contact     = Contact::first();
                    // if ($updateOrder->status == 'Pending' && $updateOrder->payment_status == 1) {
                        $misc             = Registration::where('referenceNo', $updateOrder->id)->first();
                        $updateOrder->status         = Order::$statusNew;
                        $updateOrder->payment_date   = Carbon::parse($misc->transDt . $misc->transTm);
                        // $updateOrder->payment_date   = Carbon::parse($trans->transDt . $trans->transTm);
                        // $updateOrder->payment_status = Order::$paymentStatusPaid;
                        $updateOrder->save();

                        // $misc             = Registration::where('referenceNo', $updateOrder->id)->first();
                        // dd($misc);
                        $getOrderShippingAddress = OrderShippingAddress::where('order_id', $updateOrder->id)->first();
                        $getCarts                = OrderDetail::with(['product', 'packagingSize'])->where('order_id', $updateOrder->id)->get();
                        $socialMedia             = SocialMedia::all();
                        $contact                 = Contact::first();

                        // $email               = $misc->billingEmail;
                        // $name                = $misc->billingNm;

                        //$orderPaymentMethod  = $updateOrder->getPaymentMethodName();

                        $data     = [
                            'getOrderShippingAddress' => $getOrderShippingAddress,
                            'carts'                   => $getCarts,
                            //'orderPaymentMethod'      => $orderPaymentMethod,
                            'getOrder'                => $updateOrder,
                            'socialMedia'             => $socialMedia,
                            'contact'                 => $contact
                        ];
                        $url ='https://apps.haldinfoods.com/api-haldin-agent/api/kirim_email_oc_sales/'.$updateOrder->id;
                        
			    		$ch = curl_init($url);
			    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			    		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			    		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			    		$data = curl_exec($ch);
			    		curl_close($ch); 

                        
                    }
              
              }
        }
        
        	    $orders = Order::where(['payment_status' => 0]);
	   // dd($orders->count());
	   // if($orders->count() > 0) {
    //         $socialMedia = SocialMedia::all();
    //         $contact = Contact::first();
    //         foreach ($orders->get() as $order) {
    //             // 1.1 If payment == 'nicepay' and has nicepay__v1__check_transaction_status
    //             if ($order->payment == Order::$paymentNicepay && $nicepayV1CheckTransactionStatuses = $order->nicepayV1CheckTransactionStatuses
    //             ) {
    //                 // dd($order->nicepayV1CheckTransactionStatuses);
    //                 foreach ($nicepayV1CheckTransactionStatuses as $nicepayV1CheckTransactionStatus) {
    //                     // 1.1.1 If payment method is exist and status == 0 (paid)
    //                     if (
    //                         ($nicepayV1CheckTransactionStatus->payMethod == PaymentMethod::$creditCard and $nicepayV1CheckTransactionStatus->status == CreditCard::$success)
    //                         or ($nicepayV1CheckTransactionStatus->payMethod == PaymentMethod::$virtualAccount and $nicepayV1CheckTransactionStatus->status == VirtualAccount::$paid)
    //                         or ($nicepayV1CheckTransactionStatus->payMethod == PaymentMethod::$cvsConvenienceStore and $nicepayV1CheckTransactionStatus->status == Cvs::$paid)
    //                     ) {
    //                         // 1.1.1.1 Update order set payment date, payment status
                            
    //                         $cekOrder = Order::where('id', $nicepayV1CheckTransactionStatus->referenceNo)->first();
                            
    //                         $order->status         = Order::$statusNew;
    //                         // $order->status         = "Pending";
    //                         $order->payment_date   = Carbon::parse($nicepayV1CheckTransactionStatus->transDt . $nicepayV1CheckTransactionStatus->transTm);
    //                         $order->payment_status = Order::$paymentStatusPaid;
    //                         $order->save();
                           
    //                         // 1.1.1.2 Send email

    //                         // get order data
    //                         $getOrder = Order::with('user')->findOrFail($order->id);

    //                         // get carts data
    //                         $getCarts = OrderDetail::with(['product', 'packagingSize'])->where('order_id', $order->id)->get();

    //                         // get order shipping address
    //                         $getOrderShippingAddress = OrderShippingAddress::where('order_id', $order->id)->first();

    //                         // $email = $getOrder->user->email;
    //                         // $name  = $getOrder->user->name;

    //                         // prepare data
    //                         $data = [
    //                             'getOrderShippingAddress' => $getOrderShippingAddress,
    //                             'carts'                   => $getCarts,
    //                             'getOrder'                => $getOrder,
    //                             'socialMedia'             => $socialMedia,
    //                             'contact'                 => $contact
    //                         ];
             
    //                       $url1 ='https://apps.haldinfoods.com/api-haldin-agent/api/kirim_email_oc_sales/'.$nicepayV1CheckTransactionStatus->referenceNo;
    //     					$ch = curl_init($url1);
    //     					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    //     					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    //     					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //     					$data = curl_exec($ch);
    //     					curl_close($ch); 
    //                     }
    //                 }
    //             }
    //         }
           
    //     }
        
        
	}
}
