<?php

use App\Models\Broker;
use App\Models\Pipeline;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokersTable extends Migration
{
    public function up()
    {
        Schema::create('brokers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Broker::create(['name' => 'ARK']);
        Broker::create(['name' => 'Phoenix']);
        
        Schema::table('pipelines', function (Blueprint $table) {
            $table->unsignedBigInteger('broker_id')->nullable();
        });
        Pipeline::findOrFail(1)->update(['broker_id' => 1]);
    }

    public function down()
    {
        Schema::dropIfExists('brokers');
        Schema::table('pipelines', function (Blueprint $table) {
            $table->dropColumn('broker_id');
        });
    }
}
