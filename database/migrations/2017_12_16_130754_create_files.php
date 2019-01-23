<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
             $table->increments('FileId');
             $table->integer('IdentifierModule')->unsigned();
             $table->string('ModuleName', 100);
             $table->string('Name', 100);
             $table->string('OriginalName', 100);
             $table->string('MimeType', 100);
             $table->integer('Size')->unsigned();
             $table->string('Description', 255)->nullable()->default(null);
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
        Schema::dropIfExists('files');
    }
}
