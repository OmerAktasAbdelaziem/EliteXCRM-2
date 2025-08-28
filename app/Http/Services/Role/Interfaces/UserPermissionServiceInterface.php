<?php
namespace App\Http\Services\Role\Interfaces;

//Other
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;


interface UserPermissionServiceInterface{
   public function hasRoleInPipeline(User $user,int $pipeline , string $roleName): bool;
   public function hasPermissionInPipeline(User $user,int $pipeline , string $permissionName): bool;
   public function isSuperAdmin(User $user): bool;
   public function hasPermissionOfMultiInPipeline(User $user, int $pipelineId, array $permissionNames): bool;
}