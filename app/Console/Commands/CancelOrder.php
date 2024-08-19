<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CancelOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel order that has not been paid after 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::where('status', 'PENDING')
            ->where('created_at', '<=', now()->subDay())
            ->get();

        foreach ($orders as $order) {
            $order->status = 'CANCELLED';
            $order->save();
        }
    }
}
