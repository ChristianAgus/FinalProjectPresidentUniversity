<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Orders\OrdersReport;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\MsProduct;
use DB;
use Auth;



class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Order::orderByRaw("oc_number Desc");
            return DataTables::of($data)
            ->addColumn('grand_total', function ($data) {
                return "Rp".number_format((float)$data->grand_total, 0);
            })          
            ->addColumn('image', function ($data) {
                $imageButton = '';
                if ($data->proof_of_payment !== null) {
                    $imageButton = "<a href=".asset('/uploads/frontend/proof_of_payment')."/".$data->proof_of_payment." class='btn btn-sm btn-outline-warning popup-image' title='Image'>Image</a>";
                }
                
                $pendingButton = '';
                if ($data->status == "Order") {
                    $pendingButton = "
                        <button data-color='#137e22' data-url=" . route('order.change_status', $data->id) . " data-status='Closed Order' class='btn btn-sm btn-outline-success btn-square js-change-status' title='Change to Closed'>Pending</button>
                    ";
                }
            
                return $imageButton . $pendingButton;
            })            
            
            ->addColumn('status', function ($data) {
                $statusLabel = '';
            
                if ($data->status == 'Order') {
                    $statusLabel = '<span class="badge bg-warning">Payment not complete</span>';
                } elseif ($data->status == 'Closed') {
                    $statusLabel = '<span class="badge bg-success">Payment Complete</span>';
                } elseif ($data->status == 'Canceled') {
                    $statusLabel = '<span class="badge bg-danger">Canceled</span>';
                } else {
                    $statusLabel = '<span class="badge bg-info">Waiting Create Oc number</span>';
                }
            
                return $statusLabel;
            })
            
            
            ->addColumn('action', function ($data) {
                $order_data = array(
                    'id'               => $data->id,
                    'oc_number'        => $data->oc_number,
                    'customer_name'    => $data->customer_name,
                    'customer_address' => $data->customer_address,
                    'customer_phone'   => $data->customer_phone,
                    'order_date'       => $data->order_date,
                    'pay_category'     => $data->pay_category
                );
            
                if ($data->oc_number !== null) {
                    return "
                        <a href='" . route('order.invoice', $data->id) . "' class='btn btn-sm btn-outline-info btn-square' title='View Invoice'><i class='fa-solid fa-print'></i> Invoice</a>
                        <a href='" . route('frontendadmin.download_invoice', $data->oc_number) . "' class='btn btn-sm btn-outline-info btn-square' title='View Invoice'><i class='fa-solid fa-print'></i> </a>";
                }
                return '';
            })
            
            
            ->rawColumns(['action', 'updated_at', 'image', 'status'])
            ->make(true);
        }
        return view('backend.master.history.order');
    }


    public function change_status($id) 
    {
        $x = Order::find($id);
        if($x->status == "Order") {
            $x->status = "Closed";
            $x->payment_status = "1";
            $x->sales_confirmation = Auth::id();
            $x->save();
            return redirect()->back()
            ->with([
                'type'    => 'success',
                'message' => '<i class="em em-email em-svg mr-2"></i>Successfully to Closed Order '. $x->name 
            ]);
        }
    }

    public function cancelStatus(Request $request, $id)
    {
        $order = Order::find($id);
        
        if ($order->status == "Order") {
            $order->status = "Canceled";
            $order->save();
            
            $orderDetails = OrderDetail::where('order_id', $id)->get();
        
            foreach ($orderDetails as $orderDetail) {
                $product = MsProduct::find($orderDetail->product_id);
                if ($product) {
                    $product->stock += $orderDetail->qty;
                    $product->sold -= $orderDetail->qty;
                    $product->save();
                }
                $orderDetail->update(['qty' => 0]);
            }
            
            return redirect()->back()->with([
                'type' => 'success',
                'message' => '<i class="em em-email em-svg mr-2"></i>Successfully canceled order ' . $order->name
            ]);
        } else {
            return redirect()->back()->with([
                'type' => 'error',
                'message' => '<i class="em em-email em-svg mr-2"></i>Order cannot be canceled because it is not in "Order" status'
            ]);
        }
    }
    
    public function viewInvoice($id)
    {
        $data['test']      = Order::where('id', $id)->first();
        if($data) {
            $data['details']   = OrderDetail::where('order_id', $id)->get();
            return view('backend.master.history.invoice', $data);
        } else {
            return redirect()->back();
        }

    }

    public function OrderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id')->withTrashed();
    }

    public function OrderReport()
    {
        return Excel::download(new OrdersReport, "Report Orders.xlsx");
    }

    public function excel(){
        return view('backend.master.history.reportorders');
    }

}
