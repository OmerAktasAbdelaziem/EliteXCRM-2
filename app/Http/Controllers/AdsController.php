<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\UserPermission;
use App\Http\Services\Ad\Interfaces\AdHandlerServiceInterface;
use App\Models\AdHandler;
use App\Models\ClientQuestion;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class AdsController extends Controller {

    protected AdHandlerServiceInterface $adHandlerService;

    public function __construct(AdHandlerServiceInterface $adHandlerService) {
        $this->adHandlerService = $adHandlerService;
    }

    public function index(Request $request) {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        
        $ads = $this->adHandlerService->getByFilters([
            ['field' => 'pipeline_id', 'conditions' => ['=' => $pipelineId]],
        ], ['fields']);

        return view('ad.index', compact(
            'isSuperAdmin',
            'isPipelineAdmin',
            'pipelineId',
            'userAuth',
            'ads',
        ));
    }

    public function create() {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        $ad = new AdHandler();

        return view('ad.show', compact(
            'pipelineId',
            'isSuperAdmin',
            'isPipelineAdmin',
            'userAuth',
            'ad',
        ));
    }

    public function store(Request $request) {
        $inputs = $request->only([
            'sheet_name',
            'sheet_url',
            'sheet_country',
            'fields',
        ]);
        $inputs['pipeline_id'] = auth()->user()->pipeline_id;

        $ad = $this->adHandlerService->create($inputs)->first();
    
        return redirect()->route('ads.show', $ad->id)->with('success', 'Ad Created Successfully');
    }

    public function show($id) {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);
        
        $ad = $this->adHandlerService->getById($id)->first();
        $ad->load(['fields']);

        $fields = [
            'first_name'     => 'First Name',
            'last_name'      => 'Last Name',
            'campaign'       => 'Campaign',
            'country'        => 'Country',
            'phone1'         => 'Phone1',
            'phone2'         => 'Phone2',
            'sales_status'   => 'Status',
            'source'         => 'Source',
            'gender'         => 'Gender',
            'email'          => 'Email',
            'age'            => 'Age',
            'ad'             => 'Ad',
            'form_id'             => 'form id',
        ];

        $questions = ClientQuestion::all();
        foreach ($questions as $question) {
            $fields[$question->id] = $question->question_text;
        }

        $defaultHeaders = [
            'first_name'     => '_اسم_حضرتك',
            // 'last_name'      => '',
            // 'campaign'       => '',
            // 'country'        => '',
            'phone1'         => 'phone_number',
            'phone2'         => 'رقم_هاتف_واتس_اب',
            // 'sales_status'   => '',
            // 'source'         => '',
            // 'gender'         => '',
            'email'          => 'email',
            'age'            => 'العمر',
            'ad'             => 'ad_name',
            'form_id'        => 'form_id',
        ];

        $response = Http::get($ad->sheet_xlsx_url);

        $tempPath = storage_path('app/temp_sheet.xlsx');
        if(!$response->failed()){
            file_put_contents($tempPath, $response->body());
            $data = Excel::toArray([], $tempPath);
        }

        $sheet = $data[0] ?? [];
        $headers = $sheet[0] ?? [];


        return view('ad.show', compact(
            'ad',
            'userAuth',
            'pipelineId',
            'isSuperAdmin',
            'isPipelineAdmin',
            'headers',
            'fields',
            'defaultHeaders'
        ));
    }

    public function update(Request $request, $id) {     
        $inputs = $request->only([
            'sheet_name',
            'sheet_url',
            'sheet_country',
            'fields',
        ]);

        // $inputs['pipeline_id'] = auth()->user()->pipeline_id;

     
        $this->adHandlerService->update($id, $inputs);

        return redirect()->back()->with('success', 'Ad Updated Successfully');
    }

    public function delete($id) {
        $this->adHandlerService->deleteByParams(['id' => $id]);
        return redirect()->route('ads.index')->with('success', 'Ad Deleted Successfully');
    }

}
