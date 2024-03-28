<?php

namespace Modules\Orders\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Orders\Models\Order;
use Modules\Orders\Models\OrderDetail;
use Modules\Orders\Models\OrderShippingAddress;
use Modules\Orders\Models\OrderShippingMethod;

//use Excel;
//use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = 'Admin - Orders';

        // prepare date
        $data['today'] = Carbon::now()->format('Y-m-d');

        $data['yesterday'] = Carbon::yesterday()->format('Y-m-d');

        $data['thisMonthStart'] = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $data['thisMonthEnd'] = Carbon::now()->lastOfMonth()->format('Y-m-d');

        $data['lastMonthStart'] = Carbon::now()->subMonth()->firstOfMonth()->format('Y-m-d');
        $data['lastMonthEnd'] = Carbon::now()->subMonth()->lastOfMonth()->format('Y-m-d');

        $data['thisYearStart'] = Carbon::now()->firstOfYear()->format('Y-m-d');
        $data['thisYearEnd'] = Carbon::now()->lastOfYear()->format('Y-m-d');

        $data['lastYearStart'] = Carbon::now()->subYear()->firstOfYear()->format('Y-m-d');
        $data['lastYearEnd'] = Carbon::now()->subYear()->lastOfYear()->format('Y-m-d');

        // set and destroy session
        if ($request->query()) {
            // set date session
            $set_session = array(
                                'ordersFromSearch' => $request->query('from'),
                                'ordersToSearch' => $request->query('to')
            );
            session()->put($set_session);
        } else {
            // destroy date session
            $destroySession = array(
                                    'ordersFromSearch',
                                    'ordersToSearch'
            );
            session()->forget($destroySession);
        }

        $data['orders'] = Order::with('user')
                                    ->whereBetweenDate($request->query())
                                    ->orderBy('id', 'desc')
                                    ->get();

        return view('orders::index', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['order'] = Order::findOrFail($id);
        return view('orders::backend/orders/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        /*$order = Order::findOrFail($id);
        $order->fill($request->input());
        $order->save();
        flash('Updated')->success()->important();
        return redirect()->back();*/
        
        $order = Order::findOrFail($id);
		$order->status = $request->status;
		
		if($request->status == 'New'){
			$order->payment_status = 1;
		}
        $order->save();
        flash('Updated')->success()->important();
        return redirect()->back();
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return back()->with('success', 'Data has been deleted.');
    }

    public function bulk_destroy(Request $request)
    {
        $data = $request->row_id;

        if($data) {

            for($x = 0; $x < count($data); $x++) {

                $order = Order::findOrFail($data[$x]);
                $order->delete();

            }

            return back()->with('success', 'Data has been deleted.');

        } else {

            return back()->with('error', 'No data.');

        }
    }

    public function view($id)
    {
        $data['title'] = 'Admin - Orders';

        $data['order'] = Order::with(['user', 'orderShippingAddress', 'orderShippingMethod', 'voucher'])->findOrFail($id);

        $data['orderDetails'] = OrderDetail::with(['product', 'packagingSize'])->where('order_id', $id)->get();

        return view('orders::view', $data);
    }

    public function download_excel(Request $request)
    {
        return \Excel::download(
            new \App\Exports\OrdersExport($request->query()),
            'orders_'.date('Ymd_His').'.xlsx'
        );
    }



}
