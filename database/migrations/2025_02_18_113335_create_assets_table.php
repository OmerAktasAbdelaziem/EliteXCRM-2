<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('symbol');
            $table->string('currency');
            $table->unsignedFloat('bid_price');
            $table->unsignedFloat('ask_price');
            $table->text('size')->nullable();
            $table->text('leverage')->nullable();
            $table->text('ask_spread')->nullable();
            $table->text('bid_spread')->nullable();
            $table->text('buy_commission')->nullable();
            $table->text('sell_commission')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assets');
    }
}
