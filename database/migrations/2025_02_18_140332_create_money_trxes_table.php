<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyTrxesTable extends Migration
{
    public function up()
    {
        Schema::create('money_trxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('broker_id');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedFloat('amount');
            $table->string('type');
            $table->string('status')->default('pending');
            $table->text('comment')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->text('bank_details')->nullable();
            $table->text('usdt')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('money_trxes');
    }
}
