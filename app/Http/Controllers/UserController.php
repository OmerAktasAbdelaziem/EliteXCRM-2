<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\Pipeline;
use App\Models\OldRole;
use App\Models\Text;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

//Services
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
use App\Http\Services\Role\Interfaces\RoleServiceInterface;
//use App\Http\Services\User\Interfaces\UserServiceInterface;
use UserPermission;

class UserController extends Controller
{
    protected $clientService;
    protected $roleService;
    //protected $userService;
    public function __construct(
            ClientServiceInterface $clientService,
            RoleServiceInterface $roleService,
            //UserServiceInterface $userService,
            ) {
        $this->clientService = $clientService;
        $this->roleService = $roleService;
        //$this->userService = $userService;
        
    }
    public function index()
    {
        $isSuperAdmin = UserPermission::isSuperAdmin(Auth::user());
        //$clients_controller = new ClientsController;
        //$user_controller    = new UserController;
        //$options            = $this->userService->getUserOptions(Auth::user());//$user_controller->get_user_options();
        $teams              = $this->clientService->getTeams(Auth::user());//$clients_controller->getTeams($options);
        //if (Auth::id() == 644033 || Auth::id() == 298274) {
        if($isSuperAdmin){
            $deleted_users = User::WithPipeline()->where('deleted',true)->get();
            $users         = $this->clientService->getUsers($teams, Auth::user())->where('deleted', '!=', true);//$clients_controller->getUsers($teams);
        }else{
            $pipelineSupportIds = json_decode(Auth::user()->pipeline->support_ids, true) ?? [];
            $pipelineSupportIds = array_merge($pipelineSupportIds, [644033,298274]);
            $users = $this->clientService->getUsers($teams, Auth::user())->whereNotIn('id',$pipelineSupportIds)->where('deleted', '!=', true);//$clients_controller->getUsers($teams)->whereNotIn('id',$pipelineSupportIds);
            $deleted_users = User::WithPipeline()->where('deleted',true)->whereNotIn('id',$pipelineSupportIds)->get();
        }

        return view('user.index',compact(
            'deleted_users',
            'users',
        ));
    }
    
    public function create()
    {
        //$clients_controller = new ClientsController;
        //$user_controller    = new UserController;
        //$options            = $this->userService->getUserOptions(Auth::user());//$user_controller->get_user_options();
        $teams              = $this->clientService->getTeams(Auth::user());//$clients_controller->getTeams($options);
        $roles = $this->roleService->getByFilters([
            ['field'=>'pipeline','conditions'=>['='=>Auth::user()->pipeline_id]],
            ['field'=>'guard_name','conditions'=>['='=>'web']],
        ]);

        return view('user.create',compact(
                'teams',
                'roles'
                ));
    }
    
    public function store(CreateUserRequest $request)
    {
        $inputs = $request->only([
            'channel_name',
            'first_name',
            'last_name',
            'username',
            'team_id',
            'gender',
            'email',
        ]);

        $id       = $this->generateUniqueCode();
        $Password = $request->input('password');

        $inputs = array_merge($inputs, [
            'password' => Hash::make($Password),
            'id'       => $id
        ]);

        if (Auth::user()->pipeline->user_limit && Auth::user()->pipeline->user_limit <= User::where('pipeline_id', Auth::user()->pipeline_id)->count()) {
            return redirect()->route('user.index')->with('fail','User Limit Reached');
        }

        $user = User::create($inputs);
        if($request->input('role') !== null){
$role   = $request->input('role');
 $user->assignRole($role, Auth::user()->pipeline_id);
        }
        Text::create([
            'user_id' => $id,
            'text' => $Password
        ]);

        return redirect()->route('user.index')->with('success','User Created Successfully');
    }
    
    public function show($id)
    {
        $isSuperAdmin = UserPermission::isSuperAdmin(Auth::user());
        //$clients_controller = new ClientsController;
        //$user_controller    = new UserController;
        //$options            = $this->userService->getUserOptions(Auth::user());//$user_controller->get_user_options();
        $teams              = $this->clientService->getTeams(Auth::user());//$clients_controller->getTeams($options);
        //$roles              = OldRole::latest()->get();
        $roles = $this->roleService->getByFilters([
            ['field'=>'pipeline','conditions'=>['='=>Auth::user()->pipeline_id]],
            ['field'=>'guard_name','conditions'=>['='=>'web']],
        ]);

        //if (Auth::id() == 644033 || Auth::id() == 298274) {
        if ($isSuperAdmin) {
            //$user = User::WithPipeline()->findOrfail($id);
            $user = User::findOrfail($id);
        }else{
            $pipelineSupportIds = json_decode(Auth::user()->pipeline->support_ids, true) ?? [];
            $pipelineSupportIds = array_merge($pipelineSupportIds, [644033,298274]);
            $user = User::WithPipeline()->whereNotIn('id',$pipelineSupportIds)->findOrfail($id);
        }
        
        if ($user->lastseen_at != null) {
            if(Carbon::parse($user->lastseen_at)->diffInMinutes(Carbon::now()) <= 5){
                $status='online';
                $status_text='Active';
            }
            else {
                $status_text='Offline';
                $status='offline';
            }
        }else {
            $status_text='Offline';
            $status='offline';
        }
        $currentRole = $user->rolesInPipeline(Auth::user()->pipeline_id)->first();
     
        return view('user.show',compact(
            'status_text',
            'status',
            'teams',
            'roles',
                'currentRole',
            'user',
        ));
    }
    
    public function edit(Request $request, $id)
    {
        $employee = User::find(Auth::user()->id);

        $request->validate([
            'firstname' => ['required' , 'string'],
            'lastname'  => ['required' , 'string'],
            'phone1'    => ['required' , 'string'],
            'phone2'    => ['nullable' , 'string'],
            'email'     => ['required' , 'string', 'email', 'max:255', 'unique:users,email,'.Auth::id().',id,pipeline_id,'.Auth::user()->pipeline_id ],
            'country'   => ['required' , 'string'],
            'city'      => ['nullable' , 'string'],
            'address'   => ['nullable' , 'string'],
        ]);

        $firstname = $request->input('firstname');
        $lastname  = $request->input('lastname');
        $phone1    = $request->input('phone1');
        $phone2    = $request->input('phone2');
        $email     = $request->input('email');
        $country   = $request->input('country');
        $city      = $request->input('city');
        $address   = $request->input('address');
        //$role   = $request->input('role');

        $employee->first_name = $firstname;
        $employee->last_name  = $lastname;
        $employee->phone1     = $phone1;
        $employee->phone2     = $phone2;
        $employee->email      = $email;
        $employee->country    = $country;
        $employee->city       = $city;
        $employee->address    = $address;
        $employee->updated_by = Auth::id();

      
        //$employee->assignRole($role, Auth::user()->pipeline_id);
        
        $employee->save();

        return redirect('/user-profile');
    }
    
    public function update(Request $request, $id)
    {
        $employee = User::WithPipeline()->findOrFail($id);
        $request->validate([
            'first_name' => ['required' , 'string'],
            'last_name'  => ['nullable' , 'string'],
            'username'   => ['required' , 'string', 'unique:users,username,'.$id.',id,pipeline_id,'.Auth::user()->pipeline_id],
            'password'   => ['required' , 'string', 'min:8', 'regex:/[!@#$%^&*(),.?":{}|<>]/'],
            'team_id'    => ['nullable' , 'numeric', 'exists:teams,id'],
            'gender'     => ['required' , 'string'],
            'email'      => ['nullable' , 'string', 'unique:users,email,'.$id.',id,pipeline_id,'.Auth::user()->pipeline_id],
        ]);

        $inputs = $request->only([
            'channel_name',
            'first_name',
            'last_name',
            'username',
            'team_id',
            'gender',
            'email',
        ]);

        $Password = $request->input('password');

        $inputs = array_merge($inputs, [
            'password' => Hash::make($Password),
            'password_changed_at' => now(),
        ]);
        if($request->input('role') !== null){
        $role   = $request->input('role');
 $employee->assignRole($role, Auth::user()->pipeline_id);
        }
        $employee->update($inputs);

        if ($employee->text) {
            $text = Text::find($employee->text->id);
            $text->update(['text'=>$Password]);
        } else {
            Text::create([
                'user_id' => $employee->id,
                'text'    => $Password
            ]);
        }
        if (Auth::id() == $employee->id) {
            Auth::logoutOtherDevices($Password);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('status', 'Password changed. Please log in again.');
        }

        return redirect()->route('user.show',$id);
    }
    
    public function destroy(Request $request)
    {
        $isSuperAdmin = UserPermission::isSuperAdmin(Auth::user());
        $userids = $request->input('userid', []);

        foreach ($userids as $userid) {
            if ($userid != Auth::id()) {
                $supportCheck = Pipeline::where('support_ids', 'LIKE', '%"'.$userid.'"%')->get();
                if ($supportCheck->count() <= 0 || $isSuperAdmin) {
                    User::destroy($userid);
                }
            }
        }
        return redirect()->route('user.index');
    }

    public function generateUniqueCode()
    {
        do {
            $referal_code = random_int(100000, 999999);
        } while (User::where("id", $referal_code)->first());

        return $referal_code;
    }
    
    public function delete(Request $request,$id = null)
    {
        $isSuperAdmin = UserPermission::isSuperAdmin(Auth::user());
        if ($id) {
            if ($id != Auth::id()) {
                $supportCheck = Pipeline::where('support_ids', 'LIKE', '%"'.$id.'"%')->get();
                if ($supportCheck->count() > 0 || $isSuperAdmin) {
                    return redirect()->back()->with('error', 'User is assigned as support to a pipeline');
                }

                $user = User::WithPipeline()->find($id);
                $user->deleted = true;
                $user->username = $user ->username.'-#-deleted-#-'.Carbon::now();
                $user->email = $user ->email.'-#-deleted-#-'.Carbon::now();
                $user->save();
            }
        }else{
            $userids = $request->input('userid', []);

            foreach ($userids as $userid) {
                if ($userid != Auth::id()) {
                    $supportCheck = Pipeline::where('support_ids', 'LIKE', '%"'.$userid.'"%')->get();
                    if ($supportCheck->count() <= 0 || $isSuperAdmin) {
                        $user = User::WithPipeline()->find($userid);
                        $user ->deleted = true;
                        $user->username = $user ->username.'-#-deleted-#-'.Carbon::now();
                        $user->email = $user ->email.'-#-deleted-#-'.Carbon::now();
                        $user ->save();
                    }
                }
            }
        }
        return redirect()->route('user.index');
    }

    public function restore(Request $request)
    {
        $userids = $request->input('userid', []);

        foreach ($userids as $userid) {
            $user = User::WithPipeline()->find($userid);
            $user ->deleted = false;
            $user ->save();
        }
        return redirect()->route('user.index');
    }

    public function userprofile()
    {
        return view('user-profile');
    }

    public function get_user_options()
    {
        $isSuperAdmin = UserPermission::isSuperAdmin(Auth::user());
        $user        = Auth::user();
        $userOptions = [];
        $teamOptions = [];
        $partOptions = [];
        $roleIds     = json_decode($user->role_ids, true) ?? [];
        $userRole    = OldRole::whereIn('id', $roleIds)->first();

        if ($userRole && is_string($userRole->options)) {
            $userOptions = json_decode($userRole->options, true);
        }
        if ($user->team?->role && is_string($user->team->role->options)) {
            $teamOptions = json_decode($user->team->role->options, true);
        }
        if ($user->team?->part?->role && is_string($user->team->part->role->options)) {
            $partOptions = json_decode($user->team->part->role->options, true);
        }

        $adminPipeline = [
            "leads_create"=>"1","leads_list"=>"1","leads_show"=>"1","leads_delete"=>"1","leads_main_tp"=>"1","leads_smart"=>"1","retention"=>"1","requests"=>"1","reports_list"=>"1","overview"=>"1","users_create"=>"1","users_list"=>"1","users_delete"=>"1","users_show"=>"1","users_update"=>"1","parts_create"=>"1","parts_list"=>"1","parts_show"=>"1","parts_update"=>"1","teams_create"=>"1","teams_list"=>"1","teams_show"=>"1","teams_update"=>"1","status_create"=>"1","status_list"=>"1","status_show"=>"1","status_update"=>"1","status_delete"=>"1","roles_create"=>"1","roles_list"=>"1","roles_show"=>"1","roles_update"=>"1","roles_delete"=>"1","settings"=>"1","emails_sender_emails"=>"1","emails_template_list"=>"1","emails_template_create"=>"1","emails_template_show"=>"1","emails_template_update"=>"1","emails_template_delete"=>"1","emails_send"=>"1","sender_email_list"=>"1","sender_email_create"=>"1","sender_email_show"=>"1","sender_email_update"=>"1","sender_email_delete"=>"1","bank_create"=>"1","bank_list"=>"1","bank_show"=>"1","bank_update"=>"1","asset_create"=>"1","asset_list"=>"1","asset_show"=>"1","asset_update"=>"1","asset_delete"=>"1","assetGroup_create"=>"1","assetGroup_list"=>"1","assetGroup_show"=>"1","assetGroup_update"=>"1","assetGroup_delete"=>"1","leads_table_lead_id"=>"1","leads_table_smart_id"=>"1","leads_table_name"=>"1","leads_table_country"=>"1","leads_table_email"=>"1","leads_table_phone"=>"1","leads_table_account_type"=>"1","leads_table_user_id"=>"1","leads_table_status"=>"1","leads_table_ftd_date"=>"1","leads_table_created_at"=>"1","leads_table_source"=>"1","leads_table_teams"=>"1","leads_table_created_by"=>"1","leads_tabs_all_leads"=>"1","leads_tabs_b2b"=>"1","leads_tabs_new"=>"1","leads_tabs_actions"=>"1","leads_tabs_history"=>"1","leads_tabs_hot"=>"1","leads_data_show_unassigned_leads"=>"1","leads_data_show_teams"=>["3","2","1"],"leads_can_update"=>"1","leads_can_renew"=>"1","leads_first_name"=>"1","leads_last_name"=>"1","leads_email"=>"1","leads_phone1"=>"1","leads_phone2"=>"1","leads_status"=>"1","leads_ftd"=>"1","leads_user_id"=>"1","leads_country"=>"1","leads_age"=>"1","leads_account_type"=>"1","leads_asset_group"=>"1","leads_show_first_name"=>"1","leads_show_last_name"=>"1","leads_show_email"=>"1","leads_show_phone1"=>"1","leads_show_phone2"=>"1","leads_show_status"=>"1","leads_show_ftd"=>"1","leads_show_enabled"=>"1","leads_show_user_id"=>"1","leads_show_username"=>"1","leads_show_pass"=>"1","leads_show_usdt"=>"1","leads_show_ftd_amount"=>"1","leads_show_account_type"=>"1","leads_show_asset_group"=>"1","leads_show_country"=>"1","leads_show_first_owner"=>"1","leads_show_team"=>"1","leads_show_lda"=>"1","leads_show_first_comment_date"=>"1","leads_show_first_comment_owner"=>"1","leads_show_assigned_date"=>"1","leads_show_ftd_date"=>"1","leads_show_created_date"=>"1","leads_show_modified_date"=>"1","leads_show_registration_date"=>"1","leads_show_age"=>"1","smart_can_update"=>"1","smart_first_name"=>"1","smart_last_name"=>"1","smart_phone1"=>"1","smart_phone2"=>"1","smart_email"=>"1","smart_username"=>"1","smart_country"=>"1","smart_amount"=>"1","smart_bonus"=>"1","smart_pass"=>"1","smart_show_first_name"=>"1","smart_show_last_name"=>"1","smart_show_phone1"=>"1","smart_show_phone2"=>"1","smart_show_email"=>"1","smart_show_username"=>"1","smart_show_country"=>"1","smart_show_amount"=>"1","smart_show_bonus"=>"1","smart_show_pass"=>"1","mainTp_can_update"=>"1","mainTp_yes_no"=>"1","mainTp_first_name"=>"1","mainTp_last_name"=>"1","mainTp_phone1"=>"1","mainTp_phone2"=>"1","mainTp_email"=>"1","mainTp_enabled"=>"1","mainTp_username"=>"1","mainTp_pass"=>"1","mainTp_usdt"=>"1","mainTp_country"=>"1","mainTp_actions_send_email"=>"1","mainTp_actions_create_money_transaction"=>"1","mainTp_actions_create_request"=>"1","mainTp_actions_open_order"=>"1","mainTp_actions"=>"1","mainTp_actions_Requests"=>"1","mainTp_actions_login_as_client"=>"1","leads_actions_open_smmart"=>"1","leads_actions_open_real"=>"1","leads_actions_open_demo"=>"1","leads_actions_actions"=>"1","smart_actions_send_email"=>"1","leads_cards_comments"=>"1","leads_cards_actions"=>"1","leads_cards_accounts"=>"1","leads_add_comments"=>"1","leads_update_comments"=>"1","leads_delete_comments"=>"1","smart_cards_comments"=>"1","smart_cards_actions"=>"1","smart_add_comments"=>"1","smart_update_comments"=>"1","smart_delete_comments"=>"1","mainTp_cards_comments"=>"1","mainTp_cards_actions"=>"1","mainTp_money_trx_update"=>"1","mainTp_money_trx_delete"=>"1","mainTp_add_comments"=>"1","mainTp_update_comments"=>"1","mainTp_delete_comments"=>"1"
        ];

        $pipelineSupportIds = json_decode(Auth::user()->pipeline->support_ids, true) ?? [];

        if (in_array(Auth::id(), $pipelineSupportIds) || $isSuperAdmin) {
            $adminPipeline = array_merge($adminPipeline,[
                'pipeline_create' => 1,
                'pipeline_update' => 1,
                'pipeline_list'   => 1,
                'pipeline_show'   => 1,
                'subscription_create' => 1,
                'subscription_update' => 1,
                'subscription_list'   => 1,
                'subscription_show'   => 1,
                'subscription_delete'   => 1,
                
            ]);
            $userOptions = array_merge($userOptions,$adminPipeline);
        }

        if (Auth::user()->pipeline->co_id == Auth::id()) {
            $userOptions = array_merge($userOptions,$adminPipeline);
        }

        $options = array_merge(
            $userOptions,
            $teamOptions,
            $partOptions,
        );

        return $options;
    }
}
