<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\MsProduct;

use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $pendingOrdersCount = Order::where('status', 'Order')->count();
        $canceledOrdersCount = Order::where('status', 'Canceled')->count();
        $closedOrdersCount = Order::where('status', 'Closed')->count();
        $totalOrdersCount = Order::count();
        $startDate = Carbon::now()->subDays(7)->startOfDay();

        $orderDetails = OrderDetail::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(qty) as total_qty'))
            ->groupBy('date')
            ->get();
    
        $labels = $orderDetails->pluck('date');
        $data = $orderDetails->pluck('total_qty');

        $startMonth = Carbon::now()->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();
        $totalQtyThisMonth = OrderDetail::whereBetween('created_at', [$startMonth, $endMonth])
            ->sum('qty');
        
        $startWeek = Carbon::now()->startOfWeek();
        $endWeek = Carbon::now()->endOfWeek();
        $totalQtyThisWeek = OrderDetail::whereBetween('created_at', [$startWeek, $endWeek])
            ->sum('qty');
            
        
        $totalGrandThisWeek = Order::whereBetween('created_at', [$startWeek, $endWeek])
            ->sum('grand_total');
        $totalGrandThisMonth = Order::whereBetween('created_at', [$startMonth, $endMonth])
            ->sum('grand_total');

        $orderDetails = Order::where('created_at', '>=', $startDate)
        ->select(DB::raw('DATE(created_at) as dates'), DB::raw('SUM(grand_total) as total_grand'))
        ->groupBy('dates')
        ->get();
    
        $dtorder = $orderDetails->pluck('dates');
        $ttlgrd = $orderDetails->pluck('total_grand');
        

        $average = $totalQtyThisMonth / $totalQtyThisWeek;
        $average1 = $totalGrandThisMonth / $totalGrandThisWeek;
        return view('backend.home', compact('pendingOrdersCount', 'canceledOrdersCount', 'closedOrdersCount', 'totalOrdersCount','labels', 'data', 'totalQtyThisMonth', 'totalQtyThisWeek', 'average', 'average1', 'dtorder', 'ttlgrd', 'totalGrandThisWeek', 'totalGrandThisMonth'));
    }
    


}
