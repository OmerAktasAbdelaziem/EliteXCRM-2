<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubscriptionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
//Services
use App\Http\Services\Subscription\Interfaces\SubscriptionServiceInterface;

class SubscriptionController extends Controller {

    protected $subscriptionService;

    public function __construct(
            SubscriptionServiceInterface $subscriptionService,
    ) {
        $this->subscriptionService = $subscriptionService;
    }

    public function index() {
        /*$pipelines = Pipeline::latest()->get();
        return view('pipeline.index', compact(
                        'pipelines'
                ));*/
    }

    public function create($pipelineId) {
        //$subscription = $this->subscriptionService;
        return view('subscription.create', compact(
                        'pipelineId',
                        //'subscription',
                        //'brokers',
                        //'users',
                ));
    }
    
    

    public function store(CreateSubscriptionRequest $request) {
        
        $inputs = $request->only([
            'start_date',
            'end_date',
            'pipeline'
        ]);

        if (!empty($inputs['start_date'])) {
            $inputs['start_date'] = Carbon::parse($inputs['start_date']);
        }

        if (!empty($inputs['end_date'])) {
            $inputs['end_date'] = Carbon::parse($inputs['end_date']);
        }
        $inputs['parts_count'] = $request->parts_count;
        $inputs['teams_count'] = $request->teams_count;
        $inputs['users_count'] = $request->users_count;
        $inputs['real_accounts'] = $request->real_accounts;
        $inputs['demo_accounts'] = $request->demo_accounts;
        $inputs['active'] = $request->active??0;
        if($request->active == 1){
            $this->subscriptionService->updateByFilters([['field'=>'pipeline','conditions'=>['='=>$request->pipeline]]], ['active'=>0]);
        }

        $this->subscriptionService->create($inputs);
        return redirect()->route('pipeline.show', ['id' => $inputs['pipeline']])->with('success', 'Pipeline created successfully');
    }

    public function edit($id) {
        
            $subscription = $this->subscriptionService->getById($id)->first();
            return view('subscription.edit', compact(
                        'subscription',
                        //'subscription',
                        //'brokers',
                        //'users',
                ));
        
    }
    
    public function update($id,CreateSubscriptionRequest $request) {
        
        
        //if($request->has('submit')){die('a');
            $subscription = $this->subscriptionService->getById($id)->first();
            $inputs = $request->only([
            'start_date',
            'end_date',
        ]);

        if (!empty($inputs['start_date'])) {
            $inputs['start_date'] = Carbon::parse($inputs['start_date']);
        }

        if (!empty($inputs['end_date'])) {
            $inputs['end_date'] = Carbon::parse($inputs['end_date']);
        }
        $inputs['parts_count'] = $request->parts_count;
        $inputs['teams_count'] = $request->teams_count;
        $inputs['users_count'] = $request->users_count;
        $inputs['real_accounts'] = $request->real_accounts;
        $inputs['demo_accounts'] = $request->demo_accounts;
        $inputs['active'] = $request->active??0;
        if($request->active == 1){
            $this->subscriptionService->updateByFilters([['field'=>'pipeline','conditions'=>['='=>$subscription->pipeline]]], ['active'=>0]);
        }
        $this->subscriptionService->update($id, $inputs);
            
        return redirect()->route('pipeline.show', $subscription->pipeline)->with('success', 'Subscription updated successfully');
        //}
    }

    public function destroy($id) {
     
        $inputs = [
            'deleted_at' => Carbon::now(),
            'deleted' => 1,
        ];
        $subscription = $this->subscriptionService->getById($id)->first();
        if(isset($subscription->id)){
        $this->subscriptionService->update($subscription->id, $inputs);
        return redirect()->route('pipeline.show', $subscription->pipeline)->with('success', 'Subscription deleted successfully');
        
        }
        
        /*Action::create([
                'client_id' => $clientid,
                'user_id' => Auth::id(),
                'text' => '<span class="text-danger">Deleted the lead ' . $clientName . ' #' . $clientid . '</span>'
            ]);*/
    }
}
