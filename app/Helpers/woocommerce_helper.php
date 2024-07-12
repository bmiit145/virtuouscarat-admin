<?php

use App\Models\WpOrder;
use App\Models\WpOrderProduct;
use Automattic\WooCommerce\Client;

function fetchAndSyncWooCommerceOrders(Client $woocommerce)
{
    $orders = $woocommerce->get('orders');

    foreach ($orders as $order) {
        syncWooCommerceOrder($order);
    }

    return count($orders);
}

function syncWooCommerceOrder($order)
{
    WpOrder::updateOrCreate(
        ['order_id' => $order->id],
        [
            'status' => $order->status,
            'currency' => $order->currency,
            'total' => $order->total,
            'order_date' => $order->date_created,
            'parent_id' => $order->parent_id,
            'date_created' => $order->date_created,
            'date_modified' => $order->date_modified,
            'discount_total' => $order->discount_total,
            'discount_tax' => $order->discount_tax,
            'shipping_total' => $order->shipping_total,
            'shipping_tax' => $order->shipping_tax,
            'cart_tax' => $order->cart_tax,
            'total_tax' => $order->total_tax,
            'customer_id' => $order->customer_id,
            'order_key' => $order->order_key,
            'billing_first_name' => $order->billing->first_name,
            'billing_last_name' => $order->billing->last_name,
            'billing_company' => $order->billing->company,
            'billing_address_1' => $order->billing->address_1,
            'billing_address_2' => $order->billing->address_2,
            'billing_city' => $order->billing->city,
            'billing_state' => $order->billing->state,
            'billing_postcode' => $order->billing->postcode,
            'billing_country' => $order->billing->country,
            'billing_email' => $order->billing->email,
            'billing_phone' => $order->billing->phone,
            'shipping_first_name' => $order->shipping->first_name,
            'shipping_last_name' => $order->shipping->last_name,
            'shipping_company' => $order->shipping->company,
            'shipping_address_1' => $order->shipping->address_1,
            'shipping_address_2' => $order->shipping->address_2,
            'shipping_city' => $order->shipping->city,
            'shipping_state' => $order->shipping->state,
            'shipping_postcode' => $order->shipping->postcode,
            'shipping_country' => $order->shipping->country,
            'payment_method' => $order->payment_method,
            'payment_method_title' => $order->payment_method_title,
            'transaction_id' => $order->transaction_id,
            'customer_ip_address' => $order->customer_ip_address,
            'customer_user_agent' => $order->customer_user_agent,
            'created_via' => $order->created_via,
            'customer_note' => $order->customer_note,
            'date_completed' => $order->date_completed,
            'date_paid' => $order->date_paid,
            'cart_hash' => $order->cart_hash,
        ]
    );

    syncWooCommerceOrderProducts($order);
}

function syncWooCommerceOrderProducts($order)
{
    foreach ($order->line_items as $item) {
        WpOrderProduct::updateOrCreate(
            ['order_id' => $order->id, 'product_id' => $item->product_id],
            [
                'sku' => $item->sku,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->total,
                'meta_data' => json_encode($item->meta_data),
            ]
        );
    }
}

function deleteWooCommerceOrder($orderId)
{
    WpOrderProduct::where('order_id', $orderId)->delete();
    WpOrder::where('order_id', $orderId)->delete();

    return true;
}


