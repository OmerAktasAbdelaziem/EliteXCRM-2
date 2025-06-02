<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPipelineIdColumnToActionsTable extends Migration
{
    public function up()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->unsignedBigInteger('pipeline_id')->default(1);
        });
    }

    public function down()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->dropColumn('pipeline_id');
        });
    }
}
