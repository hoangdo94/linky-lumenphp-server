<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta', function (Blueprint $table) {
            $table->increments('id');
            $table->string('link', 255);
            $table->integer('thumb_id')->unsigned()->nullable();
            $table->string('thumb_url', 255);
            $table->string('title', 255)->default('No title');
            $table->string('description', 4000)->default('No description');
            $table->timestamps();
            $table->foreign('thumb_id')->references('id')->on('file_entry')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('meta');
    }
}
