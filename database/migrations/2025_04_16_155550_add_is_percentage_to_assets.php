<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPercentageToAssets extends Migration
{
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->text('is_percentage')->nullable();
        });
    }

    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('is_percentage');
        });
    }
}
