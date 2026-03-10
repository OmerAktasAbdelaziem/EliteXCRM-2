<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\UserPermission;

class BankController extends Controller {

    public function index() {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        $banks = Bank::latest()->get();

        return view('bank.index', compact(
                        'isSuperAdmin',
                        'isPipelineAdmin',
                        'pipelineId',
                        'userAuth',
                        'banks',
                ));
    }

    public function create() {
        $bank = new Bank();

        return view('bank.show', compact(
                        'bank',
                ));
    }

    public function store(Request $request) {
        $inputs = $request->only([
            'beneficiary_address',
            'beneficiary_country',
            'aba_routing_number',
            'beneficiary_name',
            'account_number',
            'swift_code',
            'country',
            'address',
            'iban',
            'type',
            'name',
            'bic',
        ]);

        Bank::Create($inputs);

        return redirect()->route('bank.index')->with('success', 'Bank Created Successfully');
    }

    public function show($id) {
        $bank = Bank::findOrFail($id);

        return view('bank.show', compact(
                        'bank',
                ));
    }

    public function update(Request $request, $id) {
        $bank = Bank::findOrFail($id);

        $inputs = $request->only([
            'beneficiary_address',
            'beneficiary_country',
            'aba_routing_number',
            'beneficiary_name',
            'account_number',
            'swift_code',
            'country',
            'address',
            'iban',
            'type',
            'name',
            'bic',
        ]);

        $bank->update($inputs);

        return redirect()->back()->with('success', 'Bank Updated Successfully');
    }

    public function delete($id) {
        $status = Bank::findOrFail($id);
        $status->delete();

        return redirect()->route('bank.index')->with('success', 'Bank Deleted Successfully');
    }
}
