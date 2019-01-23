<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
             $table->increments('idProduct');

             $table->string('name', 50);
             $table->string('description', 1000);
             $table->integer('stock');

             $table->integer('category')->unsigned();
             $table->foreign('category')->references('idCategory')->on('category');

             $table->integer('brand')->unsigned();
             $table->foreign('brand')->references('idBrand')->on('brand');

             $table->float('price');

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
        Schema::dropIfExists('product');
    }
}
