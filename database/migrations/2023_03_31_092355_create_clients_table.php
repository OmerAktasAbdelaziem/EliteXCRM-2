<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('phone1');
            $table->string('phone2')->default("")->nullable();
            $table->string('email')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->default("")->nullable();
            $table->string('address')->default("")->nullable();
            $table->string('language')->nullable();
            $table->string('experience')->default("")->nullable();
            $table->string('job_title')->default("")->nullable();
            $table->string('account_type')->default("")->nullable();
            $table->string('sales_status')->default("New");
            $table->boolean('is_ftd')->default(0);
            $table->timestamp('ftd_date')->nullable();
            $table->string('gender')->nullable();
            $table->unsignedInteger('age')->nullable();
            $table->string('birth')->nullable();
            $table->text('notes')->default("")->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
