<?php
namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Contracts\Permission as PermissionContract;


class Permission extends SpatiePermission
{
    protected $table = 'rl_permissions';
    
    public static function findOrCreateWithPipeline(string $name, ?string $guardName = null, ?int $pipelineId = null): PermissionContract
    {
        $guardName = $guardName ?? config('auth.defaults.guard');

        $permission = static::where('name', $name)
            ->where('guard_name', $guardName)
            ->where('pipeline', $pipelineId)
            ->first();

        if (! $permission) {
            $permission = static::query()->insertGetId([
                'name' => $name,
                'guard_name' => $guardName,
                'pipeline' => $pipelineId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $permission = static::find($permission);
        }

        return $permission;
    }
}
