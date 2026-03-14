<?php
namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasRolesWithPipeline
{
    public function roles()
{
    return $this->belongsToMany(
        Role::class,
        'rl_model_has_roles',  
        'model_id',            
        'role_id'             
    )->wherePivot('model_type',  static::class);
}

    public function rolesInPipeline($pipelineId = null)
    {
        $query = $this->roles();
        if ($pipelineId !== null) {
            $query->wherePivot('pipeline_id', $pipelineId);
        }
       /* $bindings = $query->getBindings();
dd($bindings);
        dd($query->toSql());*/
        
        return $query;
    }

   /* public function permissionsFromRolesInPipeline($pipelineId = null)
{
    $query = $this->rolesInPipeline($pipelineId)
                  ->with('permissions');

    return $query;
}*/
    
    public function assignRole(int $roleId, ?int $pipelineId = null)
    {
        //$roles = is_array($roles) ? $roles : [$roles];
$role = Role::findOrFail($roleId);
        //foreach ($roles as $role) {
            /*if (is_string($role)) {
                $role = Role::findOrCreate($role, $this->getDefaultGuardName(), $pipelineId);
            }

            $this->roles()->syncWithoutDetaching([$role->id => ['pipeline' => $pipelineId]]);*/

            
        //}

        $this->roles()->sync([
        $role->id => [
            'pipeline_id' => $pipelineId,
            'model_type'  => self::class,
        ],
    ]);

    return $this;
    }
    
    

    protected function getDefaultGuardName(): string
    {
        return property_exists($this, 'guard_name') ? $this->guard_name : config('auth.defaults.guard');
    }
}