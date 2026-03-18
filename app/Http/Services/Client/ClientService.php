<?php

namespace App\Http\Services\Client;

//Interfaces
use App\Http\Repositories\Client\Interfaces\ClientRepositoryInterface;
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
use App\Http\Services\Organization\Interfaces\TeamServiceInterface;
use App\Http\Services\Organization\Interfaces\PartServiceInterface;
use App\Http\Services\User\Interfaces\UserServiceInterface;
//Other
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as supportCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

//Models
use App\Models\User;
use App\Models\Action;
use App\Models\Client;
use App\Facades\UserPermission;

class ClientService implements ClientServiceInterface {

    protected $clientRepository;
    protected $teamService;
    protected $partService;
    protected $userService;

    public function __construct(ClientRepositoryInterface $clientRepository,
            TeamServiceInterface $teamService,
            PartServiceInterface $partService,
            UserServiceInterface $userService) {
        $this->clientRepository = $clientRepository;
        $this->teamService = $teamService;
        $this->partService = $partService;
        $this->userService = $userService;
    }

    public function getAll(): Collection{
        $results = $this->clientRepository->getAll();
        return $results;
    }
    
    public function getById(int $id): Collection{
        $results = $this->clientRepository->getById($id);
        return $results;
    }
    
    public function getByFilters(array $params, array $with = []): Collection{
        $results = $this->clientRepository->getByFilters($params,$with);
        return $results;
    }
    public function create(array $data): Collection {
        return $this->clientRepository->create($data);
    }
    
    public function update(int $id,array $data):int
    {
        return $this->clientRepository->update($id, $data);
    }
    
    public function updateBulk(array $ids,array $data):int
    {
        return $this->clientRepository->updateBulk($ids, $data);
    }

    public function createBulk(array $data): bool {
        return $this->clientRepository->createBulk($data);
    }

    public function deleteByParams(array $params): int {
        return $this->clientRepository->deleteByIDs($Ids);
    }
    
    public function getTeams(User $user): supportCollection
    {
        $userAuth = $user;
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);
        //$user is Authinticated User
        //dd($user->ledTeams);
       // dd($user->ledTeams);
        $teams = collect();
        if ($user->ledTeams->count() > 0) {
            foreach ($user->ledTeams as $ledTeam) {
                if (!$teams->contains($ledTeam)) {
                    $teams = $teams->merge([$ledTeam]);
                }
            }
        }
        
        if ($user->ledParts->count() > 0) {
            $ledPartTeams = $user->ledParts->load('teams')->pluck('teams')->flatten();
            foreach ($ledPartTeams as $ledPartTeam) { 
                if (!$teams->contains($ledPartTeam)) {
                    $teams = $teams->merge([$ledPartTeam]);
                }
            }
        }
        //dd($teams);
        /*if (isset($options['leads_data_show_teams']) && !empty($options['leads_data_show_teams'])) {
            //$specificTeams = Team::whereIn('id', $options['leads_data_show_teams'])->get();
            $specificTeams = $this->teamService->getByFilters([['field' => 'id', 'conditions' => ['in' => $options['leads_data_show_teams']]]]);
            foreach ($specificTeams as $specificTeam) {
                if (!$teams->contains($specificTeam)) {
                    $teams = $teams->merge([$specificTeam]);
                }
            }
        }*/
        $pipelineSupportIds = json_decode($user->pipeline->support_ids, true) ?? [];
        
        if (in_array($user->id, $pipelineSupportIds) || $isPipelineAdmin || $isSuperAdmin) {
            $teams = $this->teamService->getByFilters([['field' => 'created_at', 'conditions' => ['order' => 'desc']]]);
            //$teams = Team::latest()->get();
        }
        //dd($teams);
        return $teams;
        
    }
    
    public function getUsers(supportCollection $teams,User $user): supportCollection
    {

        $userAuth = $user;
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        //$user is Authinticated User
        $with = ['pipeline'];
        //$users = collect();

$pipelineSupportIds = json_decode($user->pipeline->support_ids, true) ?? [];


$filters = [
    ['field' => 'deleted', 'conditions' => ['!=' => true]],
    ['field' => 'team_id', 'conditions' => ['in' => $teams->pluck('id')]],
    ['field' => 'id', 'conditions' => ['=' => $user->id, 'or' => true]],
];


if (in_array($user->id, $pipelineSupportIds) || $isPipelineAdmin || $isSuperAdmin) {

    $filters = [
        ['field' => 'deleted', 'conditions' => ['!=' => true]],
        ['field' => 'created_at', 'conditions' => ['order' => 'desc']]
    ];


   // if(!$isSuperAdmin){
        $filters[] = ['field' => 'pipeline_id', 'conditions' => ['=' => $pipelineId]];
     //}
}


$users = $this->userService->getByFilters($filters, $with);

        //dd($users);
        return $users;
        
    }
    
    public function getParts(supportCollection $teams,User $user): supportCollection
    {
        $userAuth = $user;
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);
     //$user is Authinticated User
         $params = [];
        $parts = collect();
        $params[] = ['field' => 'id','conditions' => ['in' => $teams->pluck('part_id')->toArray()]];
        
     
        if ($user->team) {
            $params[] = [
        'field' => 'id',
        'conditions' => ['in' => [$user->team->part_id],'or' => true]];
        }
        $params[] = ['field' => 'created_at','conditions' => ['order' => 'desc']];
        $parts = $this->partService->getByFilters($params);
        //dd($user->team);
        $pipelineSupportIds = json_decode($user->pipeline->support_ids, true) ?? [];
        if (in_array($user->id, $pipelineSupportIds) || $isPipelineAdmin || $isSuperAdmin) {
            $parts = $this->partService->getByFilters([['field' => 'created_at', 'conditions' => ['order' => 'desc']]]);
        }
        return $parts;
    }
    public function multiEdit(Request $request,User $user): RedirectResponse
    {
        $request->validate([
            'account_type' => ['nullable', 'string'],
            'sales_status' => ['nullable', 'string'],
            'ftd_amount'   => ['nullable', 'numeric'],
            'client_id'    => ['required', 'string'],
            'user_id'      => ['nullable'],
            'country'      => ['nullable', 'string'],
            'is_ftd'       => ['nullable', 'string'],
        ]);
        $inputs = array_filter(
            $request->only([
                'account_type',
                'sales_status',
                'ftd_amount',
                'country',
            ]),
            function ($value, $key) use ($request) {
                return $request->has($key) && !is_null($value);
            },
            ARRAY_FILTER_USE_BOTH
        );
            $clientIdsString = $request->input('client_id', '');
        $clientIds = explode(',', $clientIdsString);
        $clients = $this->getByFilters([['field'=>'id','conditions'=>['in'=>$clientIds]]]);
        if ($request->user_id == 'no') {
            $inputs = array_merge($inputs, [
                'user_id' => null,
            ]);
        }if ($request->user_id && $request->user_id != 'no') {
            $inputs = array_merge($inputs, [
                'user_id' => $request->user_id,
            ]);
        }
        foreach ($clients as $client) {
            foreach ($inputs as $field => $value) {
                if ($client->$field != $value) {
                    if ($field == 'user_id') {
                        $inputs = array_merge($inputs, [
                            'is_notified' => 1,
                            'notified_at' => now(),
                        ]);
                        $old_user = $client->user?->username;
                        $new_user = $this->userService->getById($value)->first();//User::find($value);
                        Action::create([
                            'client_id' => $client->id,
                            'user_id'   => $user->id,
                            'text'      => 'Updated <strong>Assigned user</strong> From <span class="text-danger">' . $old_user . '</span> To <span class="text-primary">' . $new_user->username . '</span>'
                        ]);
                    }else{
                        Action::create([
                            'client_id' => $client->id,
                            'user_id'   => $user->id,
                            'text'      => 'Updated <strong>' . ucfirst(str_replace('_', ' ', $field)) . '</strong> From <span class="text-danger">' . $client->$field . '</span> To <span class="text-primary">' . $value . '</span>'
                        ]);
                    }
                }
            }
        }
        $this->updateBulk($clientIds, $inputs);
        if ($request->user_id && !empty($request->user_id)) {
            //TODO:this update should be handeled at repository
            Client::whereIn('id', $clientIds)->whereNull('first_owner')->update([
                'first_owner' => $request->user_id,
                'assigned_at' => Carbon::now(),
            ]);
        }
        if ($request->is_ftd == 'Active') {
            foreach ($clients as $client) {
                if ($client->is_ftd != 1) {
                    Action::create([
                        'client_id' => $client->id,
                        'user_id'   => Auth::id(),
                        'text'      => 'Updated <strong>FTD</strong> From <span class="text-danger">' . $client->is_ftd . '</span> To <span class="text-primary">1</span>'
                    ]);
                }
            }
            Client::whereIn('id', $clientIds)->whereNull('ftd_date')->update([
                'ftd_date' => Carbon::now(),
                'is_ftd'   => 1,
            ]);
        }
        if ($request->is_ftd == 'InActive') {
            foreach ($clients as $client) {
                if ($client->is_ftd != 0) {
                    Action::create([
                        'client_id' => $client->id,
                        'user_id'   => Auth::id(),
                        'text'      => 'Updated <strong>FTD</strong> From <span class="text-danger">' . $client->is_ftd . '</span> To <span class="text-primary">0</span>'
                    ]);
                }
            }
            Client::whereIn('id', $clientIds)->whereNull('ftd_date')->update([
                'ftd_date' => null,
                'is_ftd'   => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Leads has been updated successfully.');
    }
}