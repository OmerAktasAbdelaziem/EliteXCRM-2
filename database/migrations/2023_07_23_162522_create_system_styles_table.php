<?php

use App\Models\SystemStyle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemStylesTable extends Migration
{
    public function up()
    {
        Schema::create('system_styles', function (Blueprint $table) {
            $table->id();
            $table->string('malePic');
            $table->string('femalePic');
            $table->timestamps();
        });

        $SystemStyle = new SystemStyle();

        $SystemStyle->malePic   = 'public/avatars/Male.png';
        $SystemStyle->femalePic = 'public/avatars/Female.png';

        $SystemStyle->save();
    }

    public function down()
    {
        Schema::dropIfExists('system_styles');
    }
}
