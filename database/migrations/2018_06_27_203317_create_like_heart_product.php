<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikeHeartProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('like_heart_product', function (Blueprint $table) {
             $table->increments('idLikeHeartProduct');

             $table->string('ip_address', 100);

             $table->integer('product')->unsigned();
             $table->foreign('product')->references('idProduct')->on('product')->onDelete('cascade');

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
        Schema::dropIfExists('like_heart_product');
    }
}
