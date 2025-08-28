<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//Models
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // first level
        $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);

        // second level: pipeline owner
        $companyAdmin = Role::create(['name' => 'pipeline_admin', 'guard_name' => 'web']);

        // third level: trader
	$trader = Role::create(['name' => 'trader', 'guard_name' => 'web']);

	$permissions = [
            'lead.create', 'lead.edit', 'lead.delete', 'lead.export',
            'lead.show','lead.list'
        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm, 'guard_name' => 'web']);
        }

        // give all permissions to superadmin
	$superAdmin->givePermissionTo(Permission::all());

	$user = User::find(644033);
$pipeLineId = 1;

$user->assignRoleWithPipeline('super_admin',$pipeLineId);

//$user->assignRole('pipeline_admin');
//$role = Role::where('name', 'pipeline_admin')->first();

//$user->roles()->attach($role->id, ['pipeline_id' => $pipeLineId]);

    }
}
