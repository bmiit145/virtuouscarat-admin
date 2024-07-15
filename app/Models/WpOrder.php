<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WpOrder extends Model
{
    use HasFactory;

    protected $table = 'wp_orders';

    protected $fillable = [
        'order_id',
        'status',
        'fullfilled_status',
        'currency',
        'total',
        'order_date',
        'created_at',
        'updated_at',
        'parent_id',
        'version',
        'prices_include_tax',
        'date_created',
        'date_modified',
        'discount_total',
        'discount_tax',
        'shipping_total',
        'shipping_tax',
        'cart_tax',
        'total',
        'total_tax',
        'customer_id',
        'order_key',
        'billing_first_name',
        'billing_last_name',
        'billing_company',
        'billing_address_1',
        'billing_address_2',
        'billing_city',
        'billing_state',
        'billing_postcode',
        'billing_country',
        'billing_email',
        'billing_phone',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_company',
        'shipping_address_1',
        'shipping_address_2',
        'shipping_city',
        'shipping_state',
        'shipping_postcode',
        'shipping_country',
        'payment_method',
        'payment_method_title',
        'transaction_id',
        'customer_ip_address',
        'customer_user_agent',
        'created_via',
        'customer_note',
        'date_completed',
        'date_paid',
        'cart_hash',
    ];

    protected $dates = [
        'date_created',
        'date_modified',
        'date_completed',
        'date_paid',
    ];

    public $timestamps = true;


    public function products()
    {
        return $this->hasMany(WpOrderProduct::class, 'order_id', 'order_id');
    }

}
