<?php

namespace App\Jobs;

use App\Http\Controllers\WooCommerceProductController;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class bulkStatusChange implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id, $status;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id , $status = 'hidden')
    {
        $this->user_id=$user_id;
        $this->status=$status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get all wp_product_id from user_id which are not null
        $user=User::find($this->user_id);
        $wp_product_ids = $user->products()->whereNotNull('wp_product_id')->pluck('wp_product_id')->toArray();

        foreach ($wp_product_ids as $wp_product_id) {
            $response = WooCommerceProductController::changeProductVisibility($wp_product_id, $this->status);

            $error = null;
            if (is_object($response) && isset($response->error)) {
                $error = $response->error;
            } elseif (is_array($response) && isset($response['error'])) {
                $error = $response['error'];
            }

            if ($error) {
                Log::error('Failed to update product visibility', [
                    'wp_product_id' => $wp_product_id,
                    'error' => $error
                ]);
            } else {
                Log::info('Product visibility updated successfully', [
                    'wp_product_id' => $wp_product_id,
                    'status' => $this->status
                ]);
            }
        }
    }
}
