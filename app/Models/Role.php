<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Contracts\Role as RoleContract;
use App\Traits\HasRolesWithPipeline;


class Role extends SpatieRole
{
  public static function findOrCreateWithPipeline(string $name, ?string $guardName = null, ?int $pipelineId = null): RoleContract
    {
        $guardName = $guardName ?? config('auth.defaults.guard');

        $role = static::where('name', $name)
            ->where('guard_name', $guardName)
            ->where('pipeline', $pipelineId)
            ->first();

        if (! $role) {
            /*$role = static::create([
                'name' => $name,
                'guard_name' => $guardName,
                'pipeline' => $pipelineId,
            ]);*/
             $role = static::query()->insertGetId([
                'name' => $name,
                'guard_name' => $guardName,
                'pipeline' => $pipelineId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $role = static::find($role);
        }

        return $role;
    }
    public function permissions(): BelongsToMany
{
    return $this->belongsToMany(Permission::class, 'rl_role_has_permissions', 'role_id', 'permission_id');
}
}