<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveAuctionIdFromItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign('items_auction_id_foreign');
            $table->dropColumn('auction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
          $table->integer('auction_id')->unsigned();
          $table->foreign('auction_id')
            ->references('id')
            ->on('auctions')
            ->onDelete('cascade');
        });
    }
}
