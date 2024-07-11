<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');       
            $table->text('short_description');
            $table->float('regular_price');
            $table->float('sale_price');
            $table->string('sku');
            $table->integer('stock_status')->comment('1 . In Stock , 2 . Out Of Stoke , 3 . On backOrder');
            $table->string('igi_certificate');
            $table->string('main_photo');
            $table->text('photo_gallery');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_products');
    }
}
