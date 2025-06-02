<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdColumnToPipelinesTable extends Migration
{
    public function up()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }
}
