<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

//Models
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // first level
        $superAdmin = Role::create(['name' => 'system_super_admin','pipeline'=>0, 'guard_name' => 'web']);

        // second level: pipeline owner
//        $companyAdmin = Role::create(['name' => 'pipeline_admin', 'guard_name' => 'web']);

        // third level: trader
//	$trader = Role::create(['name' => 'trader', 'guard_name' => 'web']);

	$permissions = [
           /* 'leads_show',
'leads_create',
'leads_edit',
'leads_delete',
'leads_list',
'leads_cards_comments',
'leads_main_tp',
'trading_create',
'trading_manage',
'leads_main_tp_demo',
'mainTp_can_update',
'mainTp_cards_comments',
'mainTp_cards_chat',
'users_show',
'users_create',
'users_edit',
'users_delete',
'parts_view',
'parts_create',
'parts_edit',
'parts_sender_parts',
'teams_view',
'teams_create',
'teams_edit',
'teams_delete',
'reports_view',
'reports_create',
'reports_edit',
'reports_export',
'retention_view',
'retention_create',
'retention_delete',
'requests_page_view',
'status_view',
'status_create',
'status_edit',
'status_delete',
'roles_view',
'roles_create',
'roles_edit',
'roles_delete',
'emails_view',
'emails_template_create',
'emails_template_edit',
'emails_sender_emails',
'banks_view',
'banks_create',
'banks_edit',
'banks_delete',
'assets_view',
'assets_create',
'assets_edit',
'assets_delete',
'asset_groups_view',
'asset_groups_create',
'asset_groups_edit',
'asset_groups_delete',
'pipeline_view',
'pipeline_create',
'pipeline_edit',
'pipeline_delete'*/
        ];

       /* foreach ($permissions as $perm) {
            Permission::create(['name' => $perm,'pipeline'=>0, 'guard_name' => 'web']);
        }*/

        // give all permissions to superadmin
//	$superAdmin->givePermissionTo(Permission::all());
$pipeLineId = 0;
	
$user = User::find(644033);
$user->assignRoleWithPipeline('system_super_admin',$pipeLineId);

$user = User::find(298274);
$user->assignRoleWithPipeline('system_super_admin',$pipeLineId);


//$user->assignRole('pipeline_admin');
//$role = Role::where('name', 'pipeline_admin')->first();

//$user->roles()->attach($role->id, ['pipeline_id' => $pipeLineId]);

    }
}
