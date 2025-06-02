<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamRequest;
use App\Models\Part;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        $clients_controller = new ClientsController;
        $user_controller    = new UserController;
        $options            = $user_controller->get_user_options();
        $teams              = $clients_controller->getTeams($options);

        return view('team.index',compact(
            'teams'
        ));
    }
    
    public function create()
    {
        $clients_controller = new ClientsController;
        $usersWithoutTeam   = User::WithPipeline()->whereNull('team_id')->get();
        $user_controller    = new UserController;
        $options            = $user_controller->get_user_options();
        $teams              = $clients_controller->getTeams($options);
        $users              = $clients_controller->getUsers($teams);
        $parts              = $clients_controller->getParts($teams);
        $team               = new Team;
        $users              = $users->merge($usersWithoutTeam);

        return view('team.show',compact(
            'parts',
            'users',
            'team',
        ));
    }
    
    public function store(CreateTeamRequest $request)
    {
        $inputs = $request->only([
            'leader_id',
            'part_id',
            'name',
        ]);

        if (Auth::user()->pipeline->team_limit &&Auth::user()->pipeline->team_limit <= Team::where('pipeline_id', Auth::user()->pipeline_id)->count()) {
            return redirect()->route('team.index')->with('fail','Team Limit Reached');
        }

        $team = Team::Create($inputs);

        if ($request->members) {
            User::WithPipeline()->whereIn('id', $request->members)->update(['team_id' => $team->id]);
        }

        return redirect()->route('team.index')->with('success','Team Created Successfully');;
    }
    
    public function show($id)
    {
        $team  = Team::findOrfail($id);
        $clients_controller = new ClientsController;
        $usersWithoutTeam   = User::WithPipeline()->whereNull('team_id')->get();
        $user_controller    = new UserController;
        $options            = $user_controller->get_user_options();
        $teams              = $clients_controller->getTeams($options);
        $users              = $clients_controller->getUsers($teams);
        $parts              = $clients_controller->getParts($teams);
        $users              = $users->merge($usersWithoutTeam);

        return view('team.show',compact(
            'parts',
            'users',
            'team',
        ));
    }
    
    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        $request->validate([
            'leader_id' => ['required' , 'numeric' , 'exists:users,id'],
            'members.*' => ['nullable' , 'numeric' , 'min:1' , 'exists:users,id'],
            'members'   => ['nullable'],
            'part_id'   => ['nullable' , 'numeric' , 'exists:parts,id'],
            'name'      => ['required' , 'string' , 'unique:teams,name,'.$id.',id,pipeline_id,'.Auth::user()->pipeline_id],
        ]);

        $inputs = $request->only([
            'leader_id',
            'part_id',
            'name',
        ]);

        $team->update($inputs);

        User::WithPipeline()->where('team_id', $team->id)->update(['team_id' => null]);

        if ($request->members) {
            User::WithPipeline()->whereIn('id', $request->members)->update(['team_id' => $team->id]);
        }

        return redirect()->back()->with('success','Team Updated Successfully');
    }
    
}
