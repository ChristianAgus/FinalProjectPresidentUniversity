<?php

namespace App\Exports\Orders;

use Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\MsProduct;
use Carbon\Carbon;

class OrdersReport implements FromView, WithEvents
{
    
    public function view(): View
    {
        if (request()->has('fromExcel') && request()->has('toExcel')) {
            $stoks = Order::orderBy('id', 'DESC')
                ->where('status', 'Closed')
                ->whereBetween('created_at', [request()->input('fromExcel') . ' 00:00:00', request()->input('toExcel') . ' 23:59:59']);
            $data['master'] = $stoks->get();
        
            return view('backend.master.history.excelInOut', $data);
        } else {
            return redirect()->back();
        }
    }
    
    


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A:U')->applyFromArray([
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ]
                ]);
            },
        ];
    }
    
}