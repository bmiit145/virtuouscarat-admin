<?php

namespace App\Http\Controllers;

use App\Models\WpOrder;
use Illuminate\Http\Request;
use App\Models\WpOrderProduct;
use App\Helpers\woocommerce_helper;


class WooCommerceWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Verify the webhook signature if you have set a secret
        $secret = env('WOOCOMMERCE_WEBHOOK_SECRET');
        if ($secret) {
            $signature = $request->header('x-wc-webhook-signature');
            $calculatedSignature = base64_encode(hash_hmac('sha256', $request->getContent(), $secret, true));

            if ($signature !== $calculatedSignature) {
                return response()->json(['message' => 'Invalid signature'], 400);
            }
        }

        // Process the webhook payload
        $order = $request->input('order');

        syncWooCommerceOrder($order);


        return response()->json(['message' => 'Order processed'], 200);
    }
}
