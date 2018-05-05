<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionsUsersPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('auction_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('auction_id')
              ->references('id')
              ->on('auctions')
              ->onDelete('cascade');
            $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auctions_users');
    }
}
