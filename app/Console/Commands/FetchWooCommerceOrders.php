<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Automattic\WooCommerce\Client;
use App\Models\WpOrder;

class FetchWooCommerceOrders extends Command
{
    protected $signature = 'fetch:woocommerce-orders';
    protected $description = 'Fetch orders from WooCommerce and store them in the wp_orders table';
    protected $woocommerce;

    public function __construct(Client $woocommerce)
    {
        parent::__construct();
        $this->woocommerce = $woocommerce;
    }

    public function handle()
    {

        $orders = $this -> woocommerce->get('orders');
        foreach ($orders as $order) {
            WpOrder::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'status' => $order->status,
                    'currency' => $order->currency,
                    'total' => $order->total,
                    'order_date' => $order->date_created,
                ]
            );
        }

        $this->info('WooCommerce orders have been successfully fetched and stored.');
    }
}
