<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManualNextFieldToAuctionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('auctions', function (Blueprint $table) {
        $table->tinyInteger('manual_next');
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
        $table->dropColumn('manual_next');
    });
  }
}
