<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('follower_id')->unsigned();
            $table->primary(['user_id', 'follower_id']);
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('follower_id')->references('id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('follow');
    }
}
