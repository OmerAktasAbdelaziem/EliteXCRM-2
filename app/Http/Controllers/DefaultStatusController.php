<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\UserPermission;
use App\Http\Services\DefaultStatus\Interfaces\DefaultStatusServiceInterface;
use App\Models\DefaultStatus;

class DefaultStatusController extends Controller {

    protected DefaultStatusServiceInterface $defaultStatusService;

    public function __construct(DefaultStatusServiceInterface $defaultStatusService) {
        $this->defaultStatusService = $defaultStatusService;
    }

    public function index(Request $request) {
        $userAuth = Auth::user();
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);

        $defaultStatuses = $this->defaultStatusService->getByFilters([]);

        return view('default_status.index', compact(
            'isSuperAdmin',
            'userAuth',
            'defaultStatuses',
        ));
    }

    public function create() {
        $userAuth = Auth::user();
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);

        $defaultStatus = new DefaultStatus();

        return view('default_status.show', compact(
            'isSuperAdmin',
            'userAuth',
            'defaultStatus',
        ));
    }

    public function store(Request $request) {
        $inputs = $request->only([
            'name',
        ]);

        $defaultStatus = $this->defaultStatusService->create($inputs)->first();
    
        return redirect()->route('default-status.show', $defaultStatus->id)->with('success', 'Default Status Created Successfully');
    }

    public function show($id) {
        $userAuth = Auth::user();
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        
        $defaultStatus = $this->defaultStatusService->getById($id)->first();

        return view('default_status.show', compact(
            'defaultStatus',
            'userAuth',
            'isSuperAdmin',
        ));
    }

    public function update(Request $request, $id) {     
        $inputs = $request->only([
            'name',
        ]);
     
        $this->defaultStatusService->update($id, $inputs);

        return redirect()->back()->with('success', 'Default Status Updated Successfully');
    }

    public function delete($id) {
        $this->defaultStatusService->deleteByParams(['id' => $id]);
        return redirect()->route('default-status.index')->with('success', 'Default Status Deleted Successfully');
    }

}
