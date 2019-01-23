<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableContainerProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_product', function (Blueprint $table) {
             $table->increments('idContainerProduct');

             $table->integer('container')->unsigned();
             $table->foreign('container')->references('idContainer')->on('container');

             $table->integer('product')->unsigned();
             $table->foreign('product')->references('idProduct')->on('product');

             $table->unique(['container', 'product']);

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
        Schema::dropIfExists('container_product');
    }
}
