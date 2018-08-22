<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_item', function (Blueprint $table) {
          $table->integer('item_id')->unsigned()->nullable();
          $table->foreign('item_id')->references('id')
                ->on('items')->onDelete('cascade');

          $table->integer('auction_id')->unsigned()->nullable();
          $table->foreign('auction_id')->references('id')
                ->on('auctions')->onDelete('cascade');
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
        Schema::dropIfExists('auction_item');
    }
}
