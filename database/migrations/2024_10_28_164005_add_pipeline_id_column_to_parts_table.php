<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPipelineIdColumnToPartsTable extends Migration
{
    public function up()
    {
        Schema::table('parts', function (Blueprint $table) {
            $table->unsignedBigInteger('pipeline_id')->default(1);
        });
    }

    public function down()
    {
        Schema::table('parts', function (Blueprint $table) {
            $table->dropColumn('pipeline_id');
        });
    }
}
