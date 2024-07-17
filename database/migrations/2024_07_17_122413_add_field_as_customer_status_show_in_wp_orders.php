<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldAsCustomerStatusShowInWpOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wp_orders', function (Blueprint $table) {
            $table->boolean('customer_status_show')->default(1)->after('fullfilled_status')->comment('0: Not show, 1: Show');
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
            $table->dropColumn('customer_status_show');
        });
    }
}
