<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AJUSTESREFERENTEIMAGEMPERFILUSUARIO extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table){
            $table->integer('AvatarFileId')->unsigned()->nullable();
            $table->foreign('AvatarFileId')->references('FileId')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table){
            $table->dropForeign('users_avatarfileid_foreign'); //ver depois
            $table->dropColumn('AvatarFileId');
        });
    }
}
