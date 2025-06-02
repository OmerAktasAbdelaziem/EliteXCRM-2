<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSenderEmailsTable extends Migration
{
    public function up()
    {
        Schema::create('sender_emails', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('email');
            $table->string('username');
            $table->string('password');
            $table->string('host');
            $table->string('port');
            $table->string('encryption');
            $table->unsignedBigInteger('pipeline_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sender_emails');
    }
}
