<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDiscountIntoWpProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wp_products', function (Blueprint $table) {
            $table->decimal('discount', 8, 2)->nullable()->default(0)->change();
            $table->decimal('discounted_price', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wp_products', function (Blueprint $table) {
            $table->dropColumn('discount');
            $table->dropColumn('discounted_price');
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('discounted_price', 8, 2)->nullable();
        });
    }
}
