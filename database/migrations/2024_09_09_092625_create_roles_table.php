<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $roles = ['Admin', 'Sales', 'Sales_Leader', 'Retantion', 'Retantion_Leader', 'Desk1 Leader', 'Desk2 Leader'];

        foreach ($roles as $roleName) {
            $role = new Role();
            $role->name = $roleName;
            $role->save();
        }

    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
