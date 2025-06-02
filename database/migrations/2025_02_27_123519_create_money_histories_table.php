<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('money_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('operation_id');
            $table->string('part');
            $table->string('type');
            $table->text('text');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('money_histories');
    }
}
