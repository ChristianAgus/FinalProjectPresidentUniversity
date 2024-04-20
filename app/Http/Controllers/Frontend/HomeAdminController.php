<?php

namespace App\Http\Controllers\Frontend;
use Illuminate\Support\Facades\Validator;
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
            $db_product = $db_order_detail->products; $stock = $db_product->stock; if ($request->qty > $stock) { return response()->json([ 'success' => false, 'message' => "Not Enough Stock." ]); } 
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
        $validator = Validator::make($data, $limit);
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
                if (!$db_order) {
                    return response()->json([
                        'success'   => false,
                        'message'   => 'Order not found or not in Cart status.'
                    ]);
                }
            
                $orderDetails = OrderDetail::whereHas('order', function ($query) use ($request) {
                    $query->where('id', $request->order_id);
                })->get();
            
                if ($orderDetails->isEmpty()) {
                    return response()->json([
                        'success'   => false,
                        'message'   => 'No order details found for this order.'
                    ]);
                }
            
                foreach ($orderDetails as $orderDetail) {
                    $product = MsProduct::find($orderDetail->product_id);
                    if ($product) {
                        if ($product->stock < $orderDetail->qty) {
                            throw new Exception("Insufficient stock for product: {$product->name}");
                        }
            
                        $product->stock -= $orderDetail->qty;
                        $product->sold += $orderDetail->qty;
                        $product->save();
                    }
                }
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
