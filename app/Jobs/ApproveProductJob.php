<?php

namespace App\Jobs;

use App\Http\Controllers\WooCommerceProductController;
use App\Models\WpProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ApproveProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Find the product and lock it for update
            $product  = WpProduct::where('id', $this->productId)->lockForUpdate()->first();

            if (!$product) {
                // Log if the product is not found
                \Log::error('Product not found: ' . $this->productId);
                DB::rollBack();
                $product->is_processing = 0;
                $product->save();
                DB::commit();
//                $this->output->error('Product not found: ' . $this->productId);
                return;
            }

            // Check if the product is already approved
            if ($product->is_approvel) {
                $product->is_processing = 0;
                $product->save();
                \Log::info('Product already approved: ' . $this->productId);
                DB::commit();

//                $this->output->info('Product already approved: ' . $this->productId);
                return;
            }

            // Update the approval status
            $product->is_approvel = 1;
            $product->is_processing = 0;

            // Save the product
            $product->save();

            // Commit the transaction
            DB::commit();

            // Send data to WooCommerce
            $response = WooCommerceProductController::sendDataToWooCommerce($product);

            // Check if there is an error
            if (is_array($response) && isset($response['error'])) {
                DB::rollBack();
                $product->is_processing = 0;
                $product->save();
                DB::commit();
                \Log::error('Failed to send product to WooCommerce: ' . $response['error']);
            }else {
                // Return success response
                \Log::info('Product approved and sent to WooCommerce successfully: ' . $this->productId);

                $this->output->success('Product already approved: ' . $this->productId);

            }
            $product->is_processing = 0;
            $product->save();
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction on exception
            DB::rollBack();
            \Log::error('Approval Error: ' . $e->getMessage());
        }
    }
}
