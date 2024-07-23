<?php

namespace App\Http\Controllers;

use App\Models\WpOrder;
use Illuminate\Http\Request;
use App\Models\WpOrderProduct;
use App\Helpers\woocommerce_helper;

class WooCommerceWebhookController extends Controller
{
    private function verifySignature(Request $request)
    {
        $secret = env('WOOCOMMERCE_WEBHOOK_SECRET');
        if ($secret) {
            $signature = $request->header('x-wc-webhook-signature');
            $calculatedSignature = base64_encode(hash_hmac('sha256', $request->getContent(), $secret, true));
            if ($signature !== $calculatedSignature) {
                return false;
            }
        }
        return true;
    }

    private function processWebhook($webhookTopic, $orderObject)
    {
        switch ($webhookTopic) {
            case 'order.deleted':
                deleteWooCommerceOrder($orderObject->id);
                $message = 'Order deleted';
                break;
            case 'order.restored':
                syncWooCommerceOrder($orderObject);
                $message = 'Order restored';
                break;
            default:
                syncWooCommerceOrder($orderObject);
                $message = 'Order processed';
                break;
        }
        return response()->json(['message' => $message], 200);
    }

    public function handle(Request $request)
    {
        if (!$this->verifySignature($request)) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $webhookTopic = $request->header('X-WC-Webhook-Topic');
        $orderObject = json_decode(json_encode($request->all()), false);

        return $this->processWebhook($webhookTopic, $orderObject);
    }
}
