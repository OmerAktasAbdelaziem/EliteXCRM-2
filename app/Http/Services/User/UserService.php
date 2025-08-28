<?php

namespace App\Http\Services\User;

//Interfaces
use App\Http\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Http\Services\User\Interfaces\UserServiceInterface;
//use App\Http\Services\Role\Interfaces\RoleServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;

//Models
use App\Models\User;
use App\Models\OldRole;

class UserService implements UserServiceInterface {

    protected $userRepository;
   // protected $roleService;

    public function __construct(UserRepositoryInterface $userRepository,
          //  RoleServiceInterface $roleService
            ) {
        $this->userRepository = $userRepository;
      //  $this->roleService = $roleService;
    }

    public function getAll(): Collection{
        $results = $this->userRepository->getAll();
        return $results;
    }
    
    public function getById(int $id): Collection{
        $results = $this->userRepository->getById($id);
        return $results;
    }
    
    public function getByFilters(array $params, array $with = []): Collection{
        $results = $this->userRepository->getByFilters($params,$with);
        return $results;
    }
    public function create(array $data): Collection {
        return $this->userRepository->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        return $this->userRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->userRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->userRepository->createBulk($data);
    }

    public function deleteByParams(array $params): int {
        return $this->userRepository->deleteByIDs($Ids);
    }
    public function getUserOptions(User $user):array
    {
        $userOptions = [];
        $teamOptions = [];
        $partOptions = [];
        $roleIds     = json_decode($user->role_ids, true) ?? [];
        $userRole    = OldRole::whereIn('id', $roleIds)->first();//$userRole    = $this->roleService->getByFilters([['field'=>'id','conditions' => ['in' => $roleIds]]])->first();//
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

        $pipelineSupportIds = json_decode($user->pipeline->support_ids, true) ?? [];

        if (in_array($user->id, $pipelineSupportIds) || $user->id == 644033 || $user->id == 298274) {
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

        if ($user->pipeline->co_id == $user->id) {
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