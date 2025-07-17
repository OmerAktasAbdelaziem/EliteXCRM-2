<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets_history', function (Blueprint $table) {
		$table->id();
		$table->string('name');
            $table->string('type')->nullable();
            $table->string('category')->nullable();
            $table->string('symbol');
            $table->string('currency');
            $table->double('bid_price')->unsigned();
            $table->double('ask_price')->unsigned();
            $table->double('last_bid')->unsigned()->default(0);
	    $table->double('last_ask')->unsigned()->default(0);
	    //$table->bigInteger('created_at');
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
        Schema::dropIfExists('assets_history');
    }
}
