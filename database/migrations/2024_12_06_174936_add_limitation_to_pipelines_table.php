<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLimitationToPipelinesTable extends Migration
{
    public function up()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->integer('part_limit')->nullable();
            $table->integer('team_limit')->nullable();
            $table->integer('user_limit')->nullable();
        });
    }

    public function down()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->dropColumn('part_limit');
            $table->dropColumn('team_limit');
            $table->dropColumn('user_limit');
        });
    }
}
