<?php
namespace App\Jobs;

use Automattic\WooCommerce\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class UpdateWooCommerceProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;

    protected $sku;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sku, $data)
    {
        $this->sku = $sku;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $woocommerce = app(Client::class);

        try {
            // Retrieve the product by SKU
            $product = $woocommerce->get('products', ['sku' => $this->sku]);
            Log::info("Product", $product);
            if (count($product) > 0) {
                $productId = $product[0]->id;
                // Update the product details
                $response = $woocommerce->put('products/' . $productId, $this->data, ['force' => true]);
                Log::info("Response");
            } else {
                Log::error('Product not found');
            }
        } catch (\Exception $e) {
            // Handle exception or log error message
            Log::error('WooCommerce API Error: ' . $e->getMessage());
        }
    }
}
