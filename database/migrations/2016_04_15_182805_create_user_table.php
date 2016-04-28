<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 255)->unique();
            $table->string('email', 255)->unique();
            $table->string('password');
            $table->string('website', 255);
            $table->string('title', 255);
            $table->string('phone', 20);
            $table->boolean('isAdmin');
            $table->integer('num_followers');
            $table->integer('num_followings');
            $table->integer('avatar_id')->unsigned()->nullable();
            $table->integer('cover_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('avatar_id')->references('id')->on('file_entry')->onDelete('set null');
            $table->foreign('cover_id')->references('id')->on('file_entry')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user');
    }
}
