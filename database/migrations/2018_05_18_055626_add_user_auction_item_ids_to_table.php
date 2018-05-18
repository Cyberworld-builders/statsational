<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserAuctionItemIdsToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bids', function (Blueprint $table) {
          $table->decimal('amount', 13, 4);

          $table->integer('user_id')->unsigned();
          $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

          $table->integer('item_id')->unsigned();
          $table->foreign('item_id')
            ->references('id')
            ->on('items')
            ->onDelete('cascade');

          $table->integer('auction_id')->unsigned();
          $table->foreign('auction_id')
            ->references('id')
            ->on('auctions')
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
        Schema::table('bids', function (Blueprint $table) {
          // $table->dropForeign('items_auction_id_foreign');
          $table->dropColumn('amount');
          $table->dropColumn('user_id');
          $table->dropColumn('item_id');
          $table->dropColumn('auction_id');

        });
    }
}
