<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupportIdsToPipelinesTable extends Migration
{
    public function up()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->text('support_ids')->nullable();
            $table->unsignedBigInteger('co_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->dropColumn('support_ids');
            $table->dropColumn('co_id');
        });
    }
}
