<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('currency')->default('USD');
            $table->string('country');
            $table->string('type');
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('beneficiary_country')->nullable();
            $table->string('beneficiary_address')->nullable();
            $table->string('aba_routing_number')->nullable();
            $table->string('iban')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bic')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('pipeline_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
