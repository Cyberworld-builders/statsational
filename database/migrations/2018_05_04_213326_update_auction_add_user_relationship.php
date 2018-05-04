<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAuctionAddUserRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('auctions', function (Blueprint $table) {
        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')
          ->references('id')
          ->on('users')
          ->onDelete('cascade');
        $table->string('name');
        $table->dateTime('start_time');
        $table->boolean('private')->default(false);
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
        $table->dropTable('user_id');
        $table->dropTable('name');
        $table->dropTable('start_time');
        $table->dropTable('private');
      });
    }
}
