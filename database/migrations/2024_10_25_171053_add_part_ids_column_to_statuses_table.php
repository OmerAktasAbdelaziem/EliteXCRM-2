<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartIdsColumnToStatusesTable extends Migration
{
    public function up()
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->text('part_ids')->default('')->nullable();
        });
    }

    public function down()
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->dropColumn('part_ids');
        });
    }
}
