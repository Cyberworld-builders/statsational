<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToAuctionItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_item', function (Blueprint $table) {
            $table->integer('bid_id')->unsigned()->nullable();
            $table->foreign('bid_id')->references('id')
                  ->on('bids')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_item', function (Blueprint $table) {
            Schema::dropIfExists('bid_id');
        });
    }
}
