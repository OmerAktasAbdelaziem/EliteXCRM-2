<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('client_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('identity_front')->nullable();
            $table->string('identity_back')->nullable();
            $table->string('contract')->nullable();
            $table->string('rental')->nullable();
            $table->string('atm')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_documents');
    }
}
