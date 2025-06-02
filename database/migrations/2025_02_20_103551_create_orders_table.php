<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('broker_id');
            $table->string('currency');
            $table->unsignedFloat('amount');
            $table->unsignedFloat('open_price');
            $table->unsignedFloat('close_price')->nullable();
            $table->unsignedFloat('required_margin');
            $table->float('pnl')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->string('type');
            $table->string('ref_currency');
            $table->text('comment')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
