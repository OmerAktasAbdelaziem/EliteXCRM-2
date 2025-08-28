<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class UserPermission extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Http\Services\Role\Interfaces\UserPermissionServiceInterface::class;
    }
}
