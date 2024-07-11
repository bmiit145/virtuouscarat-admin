<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldInWpOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wp_orders', function (Blueprint $table) {
            $table->bigInteger('parent_id');
            $table->timestamp('date_created')->nullable();
            $table->timestamp('date_modified')->nullable();
            $table->decimal('discount_total', 10, 2)->nullable();
            $table->decimal('discount_tax', 10, 2);
            $table->decimal('shipping_total', 10, 2);
            $table->decimal('shipping_tax', 10, 2);
            $table->decimal('cart_tax', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('total_tax', 10, 2);
            $table->bigInteger('customer_id');
            $table->string('order_key');
            $table->string('billing_first_name');
            $table->string('billing_last_name');
            $table->string('billing_company')->nullable();
            $table->string('billing_address_1');
            $table->string('billing_address_2')->nullable();
            $table->string('billing_city');
            $table->string('billing_state');
            $table->string('billing_postcode');
            $table->string('billing_country');
            $table->string('billing_email');
            $table->string('billing_phone')->nullable();
            $table->string('shipping_first_name');
            $table->string('shipping_last_name');
            $table->string('shipping_company')->nullable();
            $table->string('shipping_address_1');
            $table->string('shipping_address_2')->nullable();
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_postcode');
            $table->string('shipping_country');
            $table->string('payment_method');
            $table->string('payment_method_title');
            $table->string('transaction_id')->nullable();
            $table->string('customer_ip_address');
            $table->string('customer_user_agent')->nullable();
            $table->string('created_via')->nullable();
            $table->text('customer_note')->nullable();
            $table->timestamp('date_completed')->nullable();
            $table->timestamp('date_paid')->nullable();
            $table->string('cart_hash')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wp_orders', function (Blueprint $table) {
            //
        });
    }
}
