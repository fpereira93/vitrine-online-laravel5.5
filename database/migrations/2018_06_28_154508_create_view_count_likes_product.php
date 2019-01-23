<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewCountLikesProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            create view count_likes_product
            as
            select 
                product."idProduct",
                count(like_heart.product) as likes
            from
                product
                left join like_heart_product like_heart on (like_heart.product = product."idProduct")
            group by product."idProduct";
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW count_likes_product");
    }
}