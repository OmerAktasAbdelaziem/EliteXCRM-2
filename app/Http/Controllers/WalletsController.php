<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\UserPermission;
use App\Http\Services\Wallet\Interfaces\WalletServiceInterface;
use App\Models\Country;
use App\Models\Wallet;

class WalletsController extends Controller {

    protected WalletServiceInterface $walletService;

    public function __construct(WalletServiceInterface $walletService) {
        $this->walletService = $walletService;
    }

    public function index(Request $request) {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        $wallets = $this->walletService->getByFilters([]);

        return view('wallets.index', compact(
            'isSuperAdmin',
            'isPipelineAdmin',
            'userAuth',
            'wallets',
        ));
    }

    public function create() {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        $countries = Country::get();
        $wallet = new Wallet();

        return view('wallets.show', compact(
            'isSuperAdmin',
            'isPipelineAdmin',
            'userAuth',
            'wallet',
            'countries',
        ));
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'type'                 => 'required|string|in:deposit,withdrawal',
            'name'                 => 'required|string|max:255',
            'address'              => 'required|string|max:255',
            'network'              => 'required|string|max:255',
            'countries'            => 'required|array',
            'countries.*'          => 'exists:countries,id',
            'english_field_names'  => 'nullable|array',
            'english_field_names.*'=> 'required_with:arabic_field_names.*|string',
            'arabic_field_names'   => 'nullable|array',
            'arabic_field_names.*' => 'required_with:english_field_names.*|string',
        ]);

        $wallet = $this->walletService->create($validated)->first();
    
        return redirect()->route('wallets.show', $wallet->id)->with('success', 'Wallet Created Successfully');
    }

    public function show($id) {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);
        
        $countries = Country::get();

        $wallet = $this->walletService->getById($id)->first();
        $wallet->load(['fields', 'countries']);

        return view('wallets.show', compact(
            'wallet',
            'userAuth',
            'isSuperAdmin',
            'isPipelineAdmin',
            'countries',
        ));
    }

    public function update(Request $request, $id) {  

        $validated = $request->validate([
            'type'                 => 'required|string|in:deposit,withdrawal',
            'name'                 => 'required|string|max:255',
            'address'              => 'required|string|max:255',
            'network'              => 'required|string|max:255',
            'countries'            => 'required|array',
            'countries.*'          => 'exists:countries,id',
            'english_field_names'  => 'nullable|array',
            'english_field_names.*'=> 'required_with:arabic_field_names.*|string',
            'arabic_field_names'   => 'nullable|array',
            'arabic_field_names.*' => 'required_with:english_field_names.*|string',
        ]);

        $this->walletService->update($id, $validated);

        return redirect()->back()->with('success', 'Wallet Updated Successfully');
    }

    public function delete($id) {
        $this->walletService->deleteByParams(['id' => $id]);
        return redirect()->route('wallets.index')->with('success', 'Wallet Deleted Successfully');
    }

}
