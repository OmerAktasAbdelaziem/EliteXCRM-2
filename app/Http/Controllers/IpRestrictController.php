<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\UserPermission;
use App\Http\Services\IpRestrict\Interfaces\IpRestrictServiceInterface;
use App\Models\User;

class IpRestrictController extends Controller {

    protected IpRestrictServiceInterface $ipRestrictService;

    public function __construct(IpRestrictServiceInterface $ipRestrictService) {
        $this->ipRestrictService = $ipRestrictService;
    }

    /**
     * Show the IP restrictions configurations for the authenticated user's pipeline.
     */
    public function show() {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;

        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        $currentIps     = $this->ipRestrictService->getCurrentIps($pipelineId);
        $exemptedUsers  = $this->ipRestrictService->getExemptedUsers($pipelineId);
        $pipelineUsers  = User::where('pipeline_id',  $pipelineId)->where('deleted', false)->get();

        return view('ip_restrict.show', compact(
            'userAuth' ,
            'isSuperAdmin',
            'isPipelineAdmin',
            'currentIps',
            'exemptedUsers',
            'pipelineUsers'
        ));
    }

    /**
     * Save/Sync Multiple IP connections and User exceptions for the client pipeline profile context.
     */
    public function store(Request $request) {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;

        $request->validate([
            'ip_addresses'     => 'nullable|array',
            'ip_addresses.*'   => 'required|string',
            'exempted_users'   => 'nullable|array',
            'exempted_users.*' => 'required|integer|exists:users,id',
        ]);

        $this->ipRestrictService->updatePipelineConfigurations(
            $pipelineId,
            $request->input('ip_addresses', []),
            $request->input('exempted_users', [])
        );
    
        return redirect()->back()->with('success', 'IP Settings and Exemptions updated successfully!');
    }
}
