<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBidIncrementFieldToAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('auctions', function (Blueprint $table) {
          $table->integer('bid_increment');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('auctions', function (Blueprint $table) {
          $table->dropColumn('bid_increment');
      });
    }
}
