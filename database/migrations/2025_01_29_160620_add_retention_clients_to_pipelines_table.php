<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRetentionClientsToPipelinesTable extends Migration
{
    public function up()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->text('retention_clients')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->dropColumn('retention_clients');
        });
    }
}
