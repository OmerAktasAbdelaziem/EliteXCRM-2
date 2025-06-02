<?php

use App\Models\Text;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AddRoleIdColumnToUserTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
        });

        $user = new User();

        $id = random_int(100000, 999999);

        $user->id         = $id;
        $user->first_name = 'Elitex';
        $user->last_name  = 'Admin';
        $user->username   = 'ElitexAdmin';
        $user->email      = 'admin@Elitex.com';
        $user->password   = Hash::make('Elitex@admin159');
        $user->role_id    = 1;
        $user->gender     = 'Male';

        $user->save();

        $text = new Text();
            
        $text->user_id = $id;
        $text->text = 'Elitex@admin159';

        $text->save();
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
