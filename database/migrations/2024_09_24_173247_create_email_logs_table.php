<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailLogsTable extends Migration
{
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('to_email')->nullable();
            $table->string('from_email')->nullable();
            $table->string('type')->nullable();
            $table->string('client_type')->nullable();
            $table->string('wallet_id')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('company_url')->nullable();
            $table->string('company')->nullable();
            $table->string('amount')->nullable();
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_logs');
    }
}
