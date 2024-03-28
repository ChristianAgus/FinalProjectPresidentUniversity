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

use Auth;

class HomeAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['category']   = MsCategory::where('status', "Active")->orderBy('name', "ASC")->get();
        $data['product']    = MsProduct::where('status', "Active")->orderBy('name', "ASC")->get();
        $data['count_cart'] = OrderDetail::whereHas('orders', function ($q) {
            $q->where('status', "Cart");
        })->count();
        $data['grand_cart'] = OrderDetail::whereHas('orders', function ($q) {
            $q->where('status', "Cart");
        })->sum('sub_total');
        return view('frontendadmin.home', $data);
    }

    public function add_cart(Request $request)
    {
        try {
            DB::beginTransaction();
            if($request->product_id) {
                $db_product = MsProduct::where('id', $request->product_id)->first();
                $db_order = Order::where(['status' => "Cart", 'user_id' => Auth::user()->id])->first();

                if($db_order) {
                    $gt_order = OrderDetail::whereHas('orders', function ($q) {
                        $q->where(['status' => "Cart", 'user_id' => Auth::user()->id]);
                    })->sum('sub_total');

                    $insert_order = $db_order;
                    $db_order->grand_total = $gt_order+ $db_product->price;
                    $db_order->save();
                } else { 
                    $insert_order = Order::create([
                        'status'        => "Cart",
                        'user_id'       => Auth::user()->id,
                        'grand_total'   => $db_product->price
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
                        'message'	    => 'Order tidak ditemukan!'
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
            $q->where(['status' => "Cart", 'user_id' => Auth::user()->id]);
        })->get();
        $data['count_cart'] = OrderDetail::whereHas('orders', function ($q) {
            $q->where(['status' => "Cart", 'user_id' => Auth::user()->id]);
        })->count();
        $data['grand_cart'] = OrderDetail::whereHas('orders', function ($q) {
            $q->where(['status' => "Cart", 'user_id' => Auth::user()->id]);
        })->sum('sub_total');
        return view('frontendadmin.cart', $data);
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
        $data['orders'] = Order::where(['status' => "Cart", 'user_id' => Auth::user()->id])->first();
        if($data['orders']) {
            $data['order_detail'] = OrderDetail::whereHas('orders', function ($q) {
                $q->where(['status' => "Cart", 'user_id' => Auth::user()->id]);
            })->get();
            $data['count_cart'] = OrderDetail::whereHas('orders', function ($q) {
                $q->where(['status' => "Cart", 'user_id' => Auth::user()->id]);
            })->count();
            $data['grand_cart'] = OrderDetail::whereHas('orders', function ($q) {
                $q->where(['status' => "Cart", 'user_id' => Auth::user()->id]);
            })->sum('sub_total');
            return view('frontendadmin.order', $data);
        } else {
            return redirect()->route('frontendadmin.home');
        }
    }

    public function create_order(Request $request)
    {
        $data=$request->all();
        $limit=[
            'full_name'         => 'required',
            'phone_number'      => 'required|numeric',
            'address'           => 'required',
            'password'          => 'required',
            'pay_category'      => 'required|in:Cash,Transfer',
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
                if($request->password == "hf2018") {
                $db_order = Order::where([
                    'id'        => $request->order_id,
                    'status'    => 'Cart'
                ])->first();
                if($db_order) {
                    $ocToday = Order::where([
                        'order_date'    => Carbon::now()->format("Y-m-d"),
                        'status'        => "Closed"
                    ])->orderBy('created_at', "Desc")->first();
                    if($ocToday) {
                        $oc_number = $ocToday->oc_number+1;
                    } else {
                        $oc_number = "5".Carbon::now()->format("m").Carbon::now()->format("d")."999";   
                    }
                    if($request->pay_category == "Transfer") {
                        $image = $request->file('proof_of_payment');
                        $input['file'] = 'BuktiPembayaran_'.$oc_number.'.'.$image->getClientOriginalExtension();

                        $destinationPath = public_path('/uploads/frontend/proof_of_payment');
                        $imgFile = Image::make($image->getRealPath());
                        $imgFile->resize(100, 100, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($destinationPath.'/'.$input['file']);
                        $destinationPath = public_path('/uploads/frontend/proof_of_payment');
                        $image->move($destinationPath, $input['file']);

                        $va_by_admin = null;
                    } else if($request->pay_category == "Virtual Account") {
                        $va_by_admin = $request->va_by_admin;
                        $input['file'] = null;
                    } else {
                        $va_by_admin = null;
                        $input['file'] = null;
                    }
                    $db_order->oc_number        = $oc_number;
                    $db_order->order_date       = Carbon::now()->format("Y-m-d");
                    $db_order->customer_name    = $request->full_name;
                    $db_order->customer_phone   = $request->phone_number;
                    $db_order->customer_address = $request->address;
                    $db_order->status           = "Closed";
                    $db_order->payment_status   = 1;
                    $db_order->pay_category     = $request->pay_category;
                    $db_order->proof_of_payment = $input['file'];
                    $db_order->sales_name       = Auth::user()->name;
                    $db_order->user_id          = Auth::id();
                    $db_order->order_notes      = $request->order_notes;
                    $db_order->va_by_admin      = $va_by_admin;
                    $db_order->save();
                    // $order_detail = OrderDetail::where('order_id', $db_order->id)->get();
                    // $pesan = '
                    //     <style type="text/css">
                    //         body {
                    //             margin: 0;
                    //             font-family: Muli,-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
                    //             font-weight: 400;
                    //             line-height: 1.5;
                    //             font-size: 10px;
                    //         }
                    //         .text-center {
                    //             text-align: center;
                    //         }
                    //         .text-right {
                    //             text-align: right;
                    //         }
                        
                    //         .table-bordered, .table-bordered td, .table-bordered th {
                    //             border: 1px solid #b0b0b0
                    //         }
                    //         .table-bordered2 {
                    //             border: 1px solid #b0b0b0
                    //         }
                    //         .ml-2 {
                    //             margin-left:2px;
                    //         }
                    //         .bg-th {
                    //             background-color: #dfdfe1;
                    //         }
                    //         .pb-3 {
                    //             padding-bottom: 3px;
                    //         }
                    //     </style>
                    //     <body>
                    //         <table class="table-bordered2" width="50%">
                    //             <tr>
                    //                 <td><b>Order Confirmation</b><br/>
                    //                 '.$db_order->oc_number.'
                    //                 </td>
                    //             </tr>
                    //         </table>
                    //         <br/>
                    //         <table class="pb-3">
                    //             <tbody>
                    //                 <tr>
                    //                     <th width="60px">Date</th>
                    //                     <th width="15px"> : </th>
                    //                     <td>'.Carbon::parse($db_order->order_date)->formatLocalized("%B, %d %Y").'</td>
                    //                 </tr>
                    //                 <tr>
                    //                 <th>Payment</th>
                    //                     <th> : </th>
                    //                     <td>'.$db_order->pay_category.'</td>
                    //                 </tr>
                    //                 <tr>
                    //                     <th>Ship To</th>
                    //                     <th> : </th>
                    //                     <td>'.$db_order->customer_name.'</td>
                    //                 </tr>
                    //                 <tr>
                    //                     <th>Phone</th>
                    //                     <th> : </th>
                    //                     <td>'.$db_order->customer_phone.'</td>
                    //                 </tr>
                    //                 <tr>
                    //                     <th>Address</th>
                    //                     <th> : </th>
                    //                     <td>'.$db_order->customer_address.'</td>
                    //                 </tr>
                    //             </tbody>
                    //         </table>
                    //         <br/>
                    //         <table class="table-bordered" width="100%">
                    //             <tr>
                    //                 <th class="text-center bg-th">No.</th>
                    //                 <th class="text-center bg-th">Product Code</th>
                    //                 <th class="text-center bg-th">Product Name</th>
                    //                 <th class="text-center bg-th">Price (IDR)</th>
                    //                 <th class="text-center bg-th">Qty</th>
                    //                 <th class="text-center bg-th">Total</th>
                    //             </tr>';
                    //             $nomor = 1;
                    //             foreach($order_detail as $order_details) {
                    //             $pesan .='
                    //             <tr>
                    //                 <td class="text-center">'.$nomor++.'</td>
                    //                 <td class="ml-2">'.$order_details->products->sku.'</td>
                    //                 <td class="ml-2">'.$order_details->name.'</td>
                    //                 <td class="ml-2 text-right">'. number_format($order_details->price/1.11).'</td>
                    //                 <td class="ml-2 text-center">'. $order_details->qty.'</td>
                    //                 <td class="text-right">'.number_format($order_details->sub_total/1.11).'</td>
                    //             </tr>';
                    //             }
                    //             $pesan .='
                    //             <tr>
                    //                 <td colspan="5" class="text-right">Sub Total (IDR)</td>
                    //                 <td class="text-right">'.number_format($db_order->grand_total/1.11).'</td>
                    //             </tr>
                    //             <tr>
                    //                 <td colspan="5" class="text-right">PPN 11%</td>
                    //                 <td class="text-right">'.number_format($db_order->grand_total/1.11*0.11).'</td>
                    //             </tr>
                    //             <tr>
                    //                 <td colspan="5" class="text-right">Grand Total (IDR)</td>
                    //                 <td class="text-right">'.number_format($db_order->grand_total,0).'</td>
                    //             </tr>
                    //         </table>
                    //         <p>&nbsp;</p>
                    //         <table width="100%">
                    //             <tr>
                    //                 <th class="text-center">Haldin appreciate your business,</th>
                    //                 <th class="text-center">Customer Approved by,</th>
                    //             </tr>
                    //             <tr>
                    //                 <th class="text-center">&nbsp;</th>
                    //                 <th class="text-center">&nbsp;</th>
                    //             </tr>
                    //             <tr>
                    //                 <th class="text-center"><i>'.$db_order->sales_name.'</i></th>
                    //                 <th class="text-center">&nbsp;</th>
                    //             </tr>
                    //             <tr>
                    //                 <th class="text-center">&nbsp;</th>
                    //                 <th class="text-center">&nbsp;</th>
                    //             </tr>
                    //             <tr>
                    //                 <td class="text-center" style="text-decoration: underline;">'.$db_order->sales_name.'</td>
                    //                 <td class="text-center" style="text-decoration: underline;">'.$db_order->customer_name.'</td>
                    //             </tr>
                    //         </table>
                    //         <p>&nbsp;</p>
                    //         <table class="table-bordered2" width="50%">
                    //             <tr>
                    //                 <td><b>Please transfer to :</b><br/>
                    //                 PT. Haldin Pacific Semesta <br/>
                    //                 Bank Central Asia <br/>
                    //                 Bank Account No : 869.11-8888.4
                    //                 </td>
                    //             </tr>
                    //         </table>
                    //         <table width="100%">
                    //         <tr>
                    //             <td><b><a href='.route("frontendadmin.download_invoice", $db_order->oc_number).'>Download Invoice</a></b></td>
                    //         </tr>
                    //         </table>
                    //     </body>
                    // '; 
                    // $mail = new PHPMailer(true);
                    // try {
                    //     $mail->IsHTML(true);
                    //     $mail->CharSet = 'UTF-8';
                    //     $mail->IsSMTP();
                    //     $mail->SMTPAuth = true;
                    //     $mail->SMTPSecure = 'tls';
                    //     $mail->Host = 'mail.myhaldin.com';
                    //     $mail->Port = 587;
                    //     $mail->Username = 'administrator@myhaldin.com'; // user email
                    //     $mail->Password = '!!@dminHaldin2022'; // password email
                    //     $mail->setFrom('administrator@myhaldin.com', 'CMS Haldin'); // user email
                    //     $mail->addReplyTo('administrator@myhaldin.com', 'CMS Haldin'); //user email
                    //     if($request->pay_category == "Transfer") {
                    //         $mail->addAttachment("uploads/frontend/proof_of_payment/".$db_order->proof_of_payment);
                    //     }
                    //     $mail->AddAddress("salessupport.haldinfoods@myhaldin.com");
                    //     $mail->addCC("basmalah.ghufthy@myhaldin.com");
                    //     $mail->addCC("eko.yunianto@myhaldin.com");
                    //     $mail->addCC("richardo.noya@myhaldin.com");
                    //     $mail->addCC("benny.wijaya@myhaldin.com");
                    //     $mail->addCC("ali.muntaha@myhaldin.com");
                    //     $mail->addCC("ita.irawati@haldin-natural.com");
                        	
                    //     $mail->Subject = 'Order Confirmation '.$db_order->oc_number.' '.$db_order->customer_name;
                    //     $mail->Body = $pesan;
                    //     $mail->send();
                    // } catch (Exception $e) {
                    //     return response()->json([
                    //         'type'      => 'warning',
                    //         'message'   => $e->getMessage()
                    //     ]);
                    // }
                    DB::commit();
                    return response()->json([
                        'invoice' 		=> $db_order->id,
                        'success' 		=> true,
                        'message'	    => 'The order has been successfully created.'
                    ]);
                } else {
                    return response()->json([
                        'success' 		=> false,
                        'message'	    => 'Order data not found!'
                    ]);
                }
            } else {
                return response()->json([
                    'success' 		=> false,
                    'message'	    => 'You dont have access, thank you!'
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

    public function invoice_detail($id)
    {
        $data['order'] = Order::where('id', $id)->first();
        if($data['order']) {
            $data['count_cart'] = OrderDetail::whereHas('orders', function ($q) {
                $q->where(['status' => "Cart", 'user_id' => Auth::user()->id]);
            })->count();
            $data['grand_cart'] = OrderDetail::whereHas('orders', function ($q) {
                $q->where(['status' => "Cart", 'user_id' => Auth::user()->id]);
            })->sum('sub_total');
            $data['order_detail'] = OrderDetail::where('order_id', $data['order']->id)->get();

            return view('frontendadmin.invoice', $data);
        } else {
            return redirect()->route('frontendadmin.home');
        }
    }

    public function download_invoice($oc_number)
    {
    
        $db_order = Order::where(['oc_number' => $oc_number, 'status'=>'Closed'])->first();
        if($db_order) {
            $filename = 'Order Confirmation '.$oc_number.' '.$db_order->customer_name.'.pdf';
            $data['order'] = $db_order;
            $data['order_detail'] = OrderDetail::where('order_id', $db_order->id)->get();
            $data['nomor'] = 1;
    
            $view = \View::make('frontendadmin.download', $data);
            $html = $view->render();
    
            $pdf = new TCPDF;
            
            $pdf::SetTitle('Order Confirmation '.$oc_number.' '.$db_order->customer_name);
            $pdf::AddPage();
            $pdf::writeHTML($html, true, false, true, false, '');
    
            $pdf::Output(public_path($filename), 'I');
            // return response()->download(public_path($filename));
        } else {
            return redirect()->route('frontendadmin.home');
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
