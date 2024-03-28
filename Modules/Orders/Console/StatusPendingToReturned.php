<?php

namespace Modules\Orders\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Orders\Models\Order;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class StatusPendingToReturned extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'orders:status--pending-to-returned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order status pending to returned.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 1. Select orders where status is pending
        if ($orders = Order::where('status', Order::$statusPending)->get()) {
            foreach ($orders as $order) {
                // 1.1 If order created_at + 24 hours >= today
                if ($order->created_at->addHours(24)->lte(Carbon::now())) {
                    // 1.1.1 Update orders
                    $order->status = Order::$statusReturned;
                    $order->save();

                    if ($order->orderDetails) {
                        foreach ($order->orderDetails as $orderDetail) {
                            // 1.1.2 Update product set quantity (increase quantity)
                            if ($product = $orderDetail->product) {
                                $product->quantity += $orderDetail->quantity;
                                $product->save();
                            }
                        }
                    }
                    
                    // email notif returned
					$url ='https://www.haldinfoods.com/api-haldin-agent/api/kirim_email_batal_oc_sales/'.$order->id;
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					$data = curl_exec($ch);
					curl_close($ch);  
					
                    // 1.1.3 Add log
                    $message = 'Order id: '.$order->id.', status: '.$order->status;
                    \Log::info($message);
                    $this->info($message);
                }
            }
        }
    }
}
