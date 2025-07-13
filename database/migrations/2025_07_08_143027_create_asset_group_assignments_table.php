<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetGroupAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_group_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset');
            $table->unsignedBigInteger('asset_group');

            $table->integer('size')->nullable();
            $table->integer('leverage')->nullable();
            $table->integer('bid_spread')->nullable();
            $table->integer('ask_spread')->nullable();
            $table->integer('buy_commission')->nullable();
            $table->integer('sell_commission')->nullable();
            $table->integer('is_percentage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_group_assignments');
    }
}
