<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsIntoWpProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wp_products', function (Blueprint $table) {
            $table->string('CTS')->nullable()->comment('Carat Total Weight');
            $table->string('RAP')->nullable()->comment('Rapaport Price');
            $table->string('discount')->nullable();
            $table->float('price')->nullable()->comment('Final Price of vendor');
            $table->float('discounted_price')->nullable()->comment('Discounted Price of vendor');
            $table->string('video_link')->nullable();
            $table->string('location')->nullable();
            $table->string('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
