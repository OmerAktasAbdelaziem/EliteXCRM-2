<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsdtToPipelines extends Migration
{
    public function up()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->string('usdt')->nullable();
        });
    }

    public function down()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->dropColumn('usdt');
        });
    }
}
