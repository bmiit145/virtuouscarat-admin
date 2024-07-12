<?php

namespace App\Http\Controllers;

use App\Models\WpOrder;
use Illuminate\Http\Request;
use App\Models\WpOrderProduct;
use App\Helpers\woocommerce_helper;


class WooCommerceWebhookController extends Controller
{
    function arrayToObject($array) {
        return json_decode(json_encode($array), false);
    }

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

        // Log signatures for debugging
//         \Log::info('Received Signature: ' . $signature);
//         \Log::info('Calculated Signature: ' . $calculatedSignature);
//         \Log::info('Request Content: ' . $request->getContent());


        $webhookTopic = $request->header('X-WC-Webhook-Topic');
       if ($webhookTopic == 'order.deleted') {
            $order = $request->all();
            $orderObject = $this->arrayToObject($order);
            deleteWooCommerceOrder($orderObject->id);

            return response()->json(['message' => 'Order deleted'], 200);
        }elseif ($webhookTopic == 'order.restored') {
           $order = $request->all();
           $orderObject = $this->arrayToObject($order);
           syncWooCommerceOrder($orderObject);

           return response()->json(['message' => 'Order restored'], 200);
       }

        // Process the webhook payload
        $order = $request->all();

        // Log order data for debugging
        \Log::info('Order Data: ' . json_encode($order));

        $orderObject = $this->arrayToObject($order);
        syncWooCommerceOrder($orderObject);

        return response()->json(['message' => 'Order processed'], 200);
    }
}

