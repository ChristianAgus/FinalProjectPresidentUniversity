<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;

use App\Models\MsCategory;
use App\Models\MsProduct;
use App\Models\Order;
use App\Models\OrderDetail;

// use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Image;
use DB;
use PDF;
use Elibyy\TCPDF\Facades\TCPDF;

use App\Models\Nicepay\EnterpriseRegistration;
use App\Models\Nicepay\EnterpriseRegistrationResponse;

class HomeController extends Controller
{

    public function ipaddress() 
    {
        return \Request::ip();
    }

    public function index()
    {
        $data['category']   = MsCategory::where('status', "Active")->orderBy('name', "ASC")->get();
        $data['product']    = MsProduct::where('status', "Active")->orderBy('name', "ASC")->get();
        $data['count_cart'] = OrderDetail::whereHas('orders', function ($q) {
            $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
        })->count();
        $data['grand_cart'] = OrderDetail::whereHas('orders', function ($q) {
            $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
        })->sum('sub_total');
        return view('frontend.home', $data);
    }

    public function add_cart(Request $request)
    {
        try {
            DB::beginTransaction();
            if($request->product_id) {
                $db_product = MsProduct::where('id', $request->product_id)->first();
                $db_order = Order::where(['status' => "Cart", 'ip_address' => $this->ipaddress()])->first();

                if($db_order) {
                    $gt_order = OrderDetail::whereHas('orders', function ($q) {
                        $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
                    })->sum('sub_total');

                    $insert_order = $db_order;
                    $db_order->grand_total = $gt_order+ $db_product->price;
                    $db_order->save();
                } else { 
                    $insert_order = Order::create([
                        'status'        => "Cart",
                        'grand_total'   => $db_product->price,
                        'ip_address'    => $this->ipaddress()
                    ]);
                }
                if($insert_order) {
                    $exist_product = OrderDetail::where([
                        'order_id'      => $insert_order->id,
                        'product_id'    => $request->product_id
                    ])->first();
                    if($exist_product) {
                        $exist_product->qty         =  $exist_product->qty+1;
                        $exist_product->sub_total   =  $exist_product->qty*$exist_product->price;
                        $exist_product->save();
                    } else {
                        $insert_det_order = OrderDetail::create([
                            'product_id'    => $request->product_id,
                            'order_id'      => $insert_order->id,
                            'name'          => $db_product->name,
                            'size'          => $db_product->size,
                            'uom'           => $db_product->uom,
                            'price'         => $db_product->price,
                            'qty'           => 1,
                            'sub_total'     => $db_product->price
                        ]);
                    }
                    
                    DB::commit();
                    return response()->json([
                        'count_cart'    => OrderDetail::where('order_id', $insert_order->id)->count(),
                        'grand_cart'    => number_format(OrderDetail::where('order_id', $insert_order->id)->sum('sub_total'), 0),
                        'success' 		=> true,
                        'message'	    => 'Berhasil menambah ke keranjang!'
                    ]);
                } else {
                    return response()->json([
                        'success' 		=> false,
                        'message'	    => 'Master Order tidak ditemukan!'
                    ]);
                }
            } else {
                return response()->json([
                    'success' 		=> false,
                    'message'	    => 'Produk tidak ditemukan!'
                ]);
            }

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function get_cart()
    {
        $data['order_detail'] = OrderDetail::whereHas('orders', function ($q) {
            $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
        })->get();
        $data['count_cart'] = OrderDetail::whereHas('orders', function ($q) {
            $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
        })->count();
        $data['grand_cart'] = OrderDetail::whereHas('orders', function ($q) {
            $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
        })->sum('sub_total');
        return view('frontend.cart', $data);
    }

    public function set_cart(Request $request)
    {
        try {
            DB::beginTransaction();
            $db_order_detail = OrderDetail::where('id', $request->orderDetID)->first();
            $db_order_detail->qty       = $request->qty;
            $db_order_detail->sub_total = $db_order_detail->price*$request->qty;
            $db_order_detail->save();

            $db_order = Order::where('id', $request->orderID)->first();
            $db_order->grand_total = OrderDetail::where('order_id', $request->orderID)->sum('sub_total');
            $db_order->save();

            DB::commit();
            return response()->json([
                'success'       => true,
                'message'       => "Berhasil mengubah qty",
                'sub_total'     => "Rp".number_format($db_order_detail->sub_total, 0),
                'grand_total'   => "Rp".number_format($db_order->grand_total, 0)
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function remove_cart(Request $request)
    {
        try {
            DB::beginTransaction();
            $db_order_detail = OrderDetail::where('id', $request->orderDetID);
            if($db_order_detail->delete()) {
                $order_dets = OrderDetail::where('order_id', $request->orderID)->count();
                $db_order = Order::where('id', $request->orderID)->first();
                if($order_dets == 0) {
                    $db_order->grand_total = 0;
                    $db_order->delete();
                } else {
                    $db_order->grand_total = OrderDetail::where('order_id', $request->orderID)->sum('sub_total');
                    $db_order->save();
                }
    
                DB::commit();
                return response()->json([
                    'success'       => true,
                    'message'       => "Produk berhasil dihapus dari keranjang belanja",
                    'grand_total'   => "Rp".number_format($db_order->grand_total, 0)
                ]);
            } else {

            }

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function get_order()
    {
        $data['orders'] = Order::where(['status' => "Cart", 'ip_address' => $this->ipaddress()])->first();
        $data['order']  = Order::where(['status' => "Closed", 'ip_address' => $this->ipaddress()])
        ->orderBy('created_at', "DESC")
        ->first();

        if($data['orders']) {
            $data['order_detail'] = OrderDetail::whereHas('orders', function ($q) {
                $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
            })->get();
            $data['count_cart'] = OrderDetail::whereHas('orders', function ($q) {
                $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
            })->count();
            $data['grand_cart'] = OrderDetail::whereHas('orders', function ($q) {
                $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
            })->sum('sub_total');
            return view('frontend.order', $data);
        } else {
            return redirect()->route('frontend.home');
        }
    }

    public function create_order(Request $request)
    {
        $data=$request->all();
        $limit=[
            'full_name'         => 'required',
            'phone_number'      => 'required|numeric',
            'address'           => 'required',
            'pay_category'      => 'required',
            'proof_of_payment'  => 'image|mimes:jpeg,jpg,bmp,png,svg,gif|nullable'
        ];
        $validator = Validator($data, $limit);
        if ($validator->fails()){
            return response()->json([
                'success'   => false,
                'message'	=> $validator->errors()->first()
            ]);
        } else {
            try {
                DB::beginTransaction();
                $db_order = Order::where([
                    'id'            => $request->order_id,
                    'status'        => 'Cart',
                ])->first();
                if($db_order) {
                    $ocToday = Order::where([
                        'order_date'    => Carbon::now()->format("Y-m-d"),
                    ])->where('oc_number', '!=', null)->orderBy('created_at', "Desc")->first();
                    if($ocToday) {
                        $oc_number = $ocToday->oc_number+1;
                    } else {
                        $oc_number = "5".Carbon::now()->format("m").Carbon::now()->format("d")."001";   
                    }
                    $paymentMethod = explode(";", $request->pay_category);
                    $db_order->oc_number        = $oc_number;
                    $db_order->order_date       = Carbon::now()->format("Y-m-d");
                    $db_order->customer_name    = $request->full_name;
                    $db_order->customer_phone   = $request->phone_number;
                    $db_order->customer_address = $request->address;
                    $db_order->status           = "Order";
                    $db_order->pay_category     = $paymentMethod[0];
                    $db_order->order_notes      = $request->order_notes;
                    $db_order->save();
                    $apiUrl                         = 'https://www.nicepay.co.id/nicepay/api/onePass.do';
                    $tgl                            = Carbon::now();
                    $requestData                    = array();
                    $requestData['iMid']            = 'HALDINF00D';
                    $requestData['payMethod']       = $paymentMethod[0];
                    $requestData['currency']        = 'IDR';
                    $requestData['merchantKey']     = 'DuXlxlO1UAmWVYTJV3/XtHDiFRF4Ah+9U3eIP9TwivCOYoZ82Js5+ph56+3m+Xq+fiQdrCmqBlE5v2XPhrvjhQ==';
                    $requestData['amt']             = $db_order->grand_total;
                    $requestData['bankCd']          = $paymentMethod[1];
                    $requestData['referenceNo']     = $db_order->oc_number;
                    $requestData['goodsNm']         = $requestData['referenceNo'];
                    $requestData['billingNm']       = $db_order->customer_name;
                    $requestData['billingPhone']    = $db_order->customer_phone;
                    $requestData['billingEmail']    = "administrator@myhaldin.com";
                    $requestData['billingAddr']     = $db_order->customer_address;
                    $requestData['billingCity']     = 0;
                    $requestData['billingState']    = 0;
                    $requestData['billingPostCd']   = 0;
                    $requestData['billingCountry']  = 'Indonesia';
                    $requestData['callBackUrl']     = 'https://apps.haldinfoods.com/nicepay/frontend/nicepay-v1/db-call-back-url';
                    $requestData['dbProcessUrl']    = 'https://info.haldinfoods.com/nicepay/frontend/nicepay-v1/db-process-url';
                    $requestData['description']     = 'Payment Of Ref No.' . $requestData['referenceNo'];
                    $requestData['merchantToken']   = hash('sha256', $requestData['iMid'].$requestData['referenceNo'].$requestData['amt'].$requestData['merchantKey']);
                    $requestData['userIP']          = $_SERVER['REMOTE_ADDR'];
                    $requestData['cartData']        ='{}';	
                    $requestData['reqServerIP']     ='127.0.0.1';
                    $requestData['mitraCd']         = $paymentMethod[1];
                    
                    $postData = '';
                    foreach ($requestData as $key => $value) {
                      $postData .= urlencode($key) . '='.urlencode($value).'&';
                    }
                    $postData = rtrim($postData, '&');
            
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $apiUrl);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            
                    $curl_result = curl_exec($ch);
                   
                    $result = json_decode($curl_result);
            
                    foreach($result as $rws){
                        $dat_res = $rws.",";
                    }
                    if(isset($result->resultCd) && $result->resultCd == "0000"){
                        $data_rest_nicepay[] = $result;
                        $vcc_number = $result->bankVacctNo;
                        EnterpriseRegistration::create([
                            'iMid'              => 'HALDINF00D',
                            'merchantKey'       => NULL,
                            'payMethod'         => $paymentMethod[0],
                            'currency'          => 'IDR',
                            'amt'               => $db_order->grand_total,
                            'referenceNo'       => $db_order->oc_number,
                            'goodsNm'           => '',
                            'billingNm'         => $db_order->customer_name,
                            'billingPhone'      => $db_order->customer_phone,
                            'billingEmail'      => "administrator@myhaldin.com",
                            'billingCity'       => 0,
                            'billingState'      => 0,
                            'billingPostCd'     => 0,
                            'billingCountry'    => "Indonesia",
                            'callBackUrl'       => 'https://apps.haldinfoods.com/nicepay/frontend/nicepay-v1/db-call-back-url',
                            'dbProcessUrl'      => 'https://info.haldinfoods.com/nicepay/frontend/nicepay-v1/db-process-url',
                            'description'       => 'Payment Of Ref No.' . $requestData['referenceNo'],
                            'merchantToken'     => $requestData['merchantToken'],
                            'userIP'            => $_SERVER['REMOTE_ADDR'],
                            'cartData'          => '{}',
                            'instmntType'       => NULL,
                            'instmntMon'        => '1',
                            'cardCvv'           => NULL,
                            'onePassToken'      => NULL,
                            'recurrOpt'         => NULL,
                            'bankCd'            => $paymentMethod[1],
                            'vacctValidDt'      => NULL,
                            'vacctValidTm'      => NULL,
                            'mitraCd'           => $paymentMethod[1],
                            'clickPayNo'        => NULL,
                            'dataField3'        => NULL,
                            'clickPayToken'     => NULL,
                            'payValidDt'        => NULL,
                            'payValidTm'        => NULL,
                            'billingAddr'       => $db_order->customer_address,
                            'deliveryNm'        => $db_order->customer_name,
                            'deliveryPhone'     => $db_order->customer_phone,
                            'deliveryAddr'      => $db_order->customer_address,
                            'deliveryEmail'     => "administrator@myhaldin.com",
                            'deliveryCity'      => 0,
                            'deliveryState'     => 0,
                            'deliveryPostCd'    => 0,
                            'deliveryCountry'   => 0,
                            'vat'               => 0,
                            'fee'               => 0,
                            'notaxAmt'          => 0,
                            'reqDt'             => NULL,
                            'reqTm'             => NULl,
                            'reqDomain'         => NULL,
                            'reqServerIP'       => NULL,
                            'reqClientVer'      => NULL,
                            'userSessionID'      => NULL,
                            'userAgent'         => NULL,
                            'userLanguage'      => NULL,
                            'tXid'              => $result->tXid,
                            'created_at'        => Carbon::now(),
                            'updated_at'        => Carbon::now()
                        ]);
                        
                        EnterpriseRegistrationResponse::create([
                            'resultCd'      => $result->resultCd,
                            'resultMsg'     => $result->resultMsg,
                            'tXid'          => $result->tXid,
                            'referenceNo'   => $result->referenceNo,
                            'payMethod'     => $result->payMethod,
                            'amount'        => $result->amount,
                            'currency'      => $result->currency,
                            'goodsNm'       => $result->goodsNm,
                            'billingNm'     => $result->billingNm,
                            'transDt'       => $result->transDt,
                            'transTm'       => $result->transTm,
                            'description'   => $result->description,
                            'callbackUrl'   => 'https://apps.haldinfoods.com/nicepay/frontend/nicepay-v1/db-call-back-url',
                            'authNo'        => NULL,
                            'issuBankCd'    => NULL,
                            'issuBankNm'    => NULL,
                            'cardNo'        => NULL,
                            'instmntMon'    => NULL,
                            'istmntType'    => NULL,
                            'recurringToken'=> NULL,
                            'preauthToken'  => NULL,
                            'ccTransType'   => NULL,
                            'vat'           => NULL,
                            'fee'           => NULL,
                            'notaxAmt'      => NULL,
                            'bankCd'        => $result->bankCd,
                            'bankVacctNo'   => $result->bankVacctNo,
                            'vacctValidDt'  => $result->vacctValidDt,
                            'vacctValidTm'  => $result->vacctValidTm,
                            'mitraCd'       => NULL,
                            'payNo'         => NULL,
                            'payValidTm'    => NULL,
                            'payValidDt'    => NULL,
                            'receiptCode'   => NULL,
                            'mRefNo'        => NULL,
                            'data'          => $dat_res,
                            'created_at'    => Carbon::now(),
                            'updated_at'    => Carbon::now()
                        ]);
                        DB::commit();
                        return response()->json([
                            'success'           => true,
                            'result_data'       => $data_rest_nicepay,
                            'tanggal_order'     => date('Y-m-d'),
                            'jam_order'         => date('H:i:s'),
                            'tanggal_pay'       => date('Y-m-d',strtotime(date('Y-m-d') . "+1 days")),
                            'pay_metode'        => $paymentMethod[0],
                            'card_metode'       => $paymentMethod[1],
                            'mc_token'          => $requestData['merchantToken'],
                            'txId'              => $result->tXid,
                            'timeStamp'         => $tgl,
                            'amt'               => $result->amount,
                            'invoice'           => $db_order->oc_number,
                            'message'	        => 'The order has been successfully created.'

                        ]);
                    } elseif (isset($result->resultCd)) {
                        DB::commit();
                        return response()->json([
                            'success'      => true,
                            'pay_metode'   => $paymentMethod[0],
                            'card_metode'  => $paymentMethod[1],
                            'invoice'      => $db_order->oc_number,
                            'message'      => $result->resultMsg,
                        ]);
                    } else {
                        return response()->json([
                            'success'      => false,
                            'message'      => "Connection Timeout. Please Try again",
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' 		=> false,
                        'message'	    => 'Order data not found!'
                    ]);
                } 
            } catch (Exception $e) {
                DB::rollback();
                return response()->json([
                    'success'   => false,
                    'message'   => $e->getMessage()
                ]);
            }
        }
    }

    public function get_histori()
    {
        $data['order'] = Order::where(['status' => "Closed", 'ip_address' => $this->ipaddress()])->get();
        $data['count_cart'] = OrderDetail::whereHas('orders', function ($q) {
            $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
        })->count();
        $data['grand_cart'] = OrderDetail::whereHas('orders', function ($q) {
            $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
        })->sum('sub_total');
        return view('frontend.history', $data);
    }

    public function invoice_detail($oc_number)
    {
        $data['order'] = Order::where('oc_number', $oc_number)->first();
        $data['nicepay'] = EnterpriseRegistrationResponse::where('referenceNo', $oc_number)->first();
        // dd($data);
        if($data) {
            $data['count_cart'] = OrderDetail::whereHas('orders', function ($q) {
                $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
            })->count();
            $data['count_cart'] = OrderDetail::whereHas('orders', function ($q) {
                $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
            })->count();
            $data['grand_cart'] = OrderDetail::whereHas('orders', function ($q) {
                $q->where(['status' => "Cart", 'ip_address' => $this->ipaddress()]);
            })->sum('sub_total');
            $data['order_detail'] = OrderDetail::where('order_id', $data['order']->id)->get();

            return view('frontend.invoice', $data);
        } else {
            return redirect()->route('frontend.home');
        }
    }

    public function download_invoice($oc_number)
    {
    
        $db_order = Order::where(['oc_number' => $oc_number, 'status'=>'Closed'])->first();
        if($db_order) {
            $filename = 'Order Confirmation '.$oc_number.' '.$db_order->customer_name.'.pdf';
            $data['order'] = $db_order;
            $data['order_detail']   = OrderDetail::where('order_id', $db_order->id)->get();

            $data['od_haldinfoods'] = OrderDetail::where('order_id', $db_order->id)->whereHas('products', function ($q) {
                $q->where('category_id', 1);
            })->count();
            
            $data['od_karu'] = OrderDetail::where('order_id', $db_order->id)->whereHas('products', function ($q) {
                $q->where('category_id', 2);
            })->count();
            
            $data['od_talasi'] = OrderDetail::where('order_id', $db_order->id)->whereHas('products', function ($q) {
                $q->where('category_id', 3);
            })->count();
            $data['nicepay'] = EnterpriseRegistrationResponse::where('referenceNo', $oc_number)->first();

            $data['nomor'] = 1;
    
            $view = \View::make('frontend.download', $data);
            $html = $view->render();
    
            $pdf = new TCPDF;
            
            $pdf::SetTitle('Order Confirmation '.$oc_number.' '.$db_order->customer_name);
            $pdf::AddPage();
            $pdf::writeHTML($html, true, false, true, false, '');
    
            $pdf::Output(public_path($filename), 'I');
            // return response()->download(public_path($filename));
        } else {
            return redirect()->route('frontend.home');
        }
    }

    public function search_product(Request $request)
    {
        if($request->keyword != ''){
            $products = MsProduct::
            where(function ($query) use ($request) {
                $query->where('name','LIKE','%'.$request->keyword.'%')
                      ->orWhere('sku','LIKE','%'.$request->keyword.'%');
            })->where('status', "Active")->orderBy('name', "ASC")->get();
        } else {
            $products = MsProduct::where('status', "Active")->orderBy('name', "ASC")->get();
        
        }
        return response()->json([
           'products' => $products
        ]);
    }

}
