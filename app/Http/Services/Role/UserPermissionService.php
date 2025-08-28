<?php

namespace App\Http\Services\Role;

//Interfaces
use App\Http\Services\Role\Interfaces\UserPermissionServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

//Permissions
use App\Models\User;




class UserPermissionService implements UserPermissionServiceInterface {
protected static array $cache = [];
    public function hasRoleInPipeline(User $user,int $pipelineId , string $roleName): bool
    {
        //return $user->roles()->where('name', $roleName)->exists();
       
        
        /*echo $user->id.'<br>'.$pipelineId.'<br>'.$roleName.'<br>';
        dd($user->roles()
    ->when($roleName !== 'system_super_admin', function ($query) use ($pipelineId) {
        $query->wherePivot('pipeline_id', $pipelineId);
    })
    ->where('name', $roleName)
    ->toSql());*/
       // echo $roleName;die;
        return $user->roles()
    ->when($roleName !== 'system_super_admin', function ($query) use ($pipelineId) {
        $query->wherePivot('pipeline_id', $pipelineId);
    })
    ->where('name', $roleName)
    ->exists();
    }
    
    
    public function isSuperAdmin(User $user): bool
    {
        $cacheKey = $user->id . '-0-system_super_admin';

        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }
        
        $result = $this->hasRoleInPipeline($user, 0,'system_super_admin');
        
        return self::$cache[$cacheKey] = $result;
    }

    public function hasPermissionInPipeline(User $user, int $pipelineId, string $permissionName): bool
{
        $cacheKey = $user->id . '-' . $pipelineId . '-' . $permissionName;

        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }
        
       /* dd($user->rolesInPipeline($pipelineId)
                ->whereHas('permissions', function ($q) use ($permissionName, $pipelineId) {
                    $q->where('name', $permissionName)
                      ->where('pipeline', $pipelineId); 
                })->toSql());*/
    $result = $user->rolesInPipeline($pipelineId)
                ->whereHas('permissions', function ($q) use ($permissionName, $pipelineId) {
                    $q->where('name', $permissionName)
                      ->where('pipeline', $pipelineId);
                })
                ->exists();
                return self::$cache[$cacheKey] = $result;
}

public function hasPermissionOfMultiInPipeline(User $user, int $pipelineId, array $permissionNames): bool
{
    return $user->rolesInPipeline($pipelineId)
                ->whereHas('permissions', function ($q) use ($permissionNames, $pipelineId) {
                    $q->whereIn('name', $permissionNames) 
                      ->where('pipeline', $pipelineId);
                })
                ->exists();
}
    
    
    
}