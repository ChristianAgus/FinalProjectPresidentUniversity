<?php

namespace Modules\Orders\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Orders\Models\Order;
use Modules\Orders\Models\OrderTransactionStatus;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class StatusReceivedToCompleted extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'orders:status--received-to-completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order status received to completed.';

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
        // 1. Select orders where status is received
        if ($orders = Order::where('status', Order::$statusReceived)->get()) {
            foreach ($orders as $order) {
                // 1.1 If order updated_at + 48 hours >= today
                if ($order->updated_at->addHours(48)->lte(Carbon::now())) {
                    // 1.1.1 Update orders
                    $order->status = Order::$statusCompleted;
                    $order->save();

                    // 1.1.2 Add log
                    $message = 'Order id: '.$order->id.', status: '.$order->status;
                    \Log::info($message);
                    $this->info($message);
                }
            }
        }
    }
}
