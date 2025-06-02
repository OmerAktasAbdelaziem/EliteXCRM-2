<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('asset_groups', function (Blueprint $table) {
            $table->id();
            $table->text('asset_ids')->nullable();
            $table->unsignedBigInteger('pipeline_id');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asset_groups');
    }
}
