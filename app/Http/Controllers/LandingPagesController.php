<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetLeadsRequest;
use App\Models\Client;
use Illuminate\Support\Arr;

class LandingPagesController extends Controller
{
    public function LeadCapture(GetLeadsRequest $request,$source = null, $pipeline_id = 1)
    {
        $inputs = $request->only([
            'appointment_date',
            'company_name',
            'first_name',
            'how_money',
            'last_name',
            'country',
            'message',
            'phone1',
            'email',
            'age',
            'ad',
        ]);

        if ($request->is_have_invest != null && $request->is_have_invest == 'نعم') {
            $inputs = array_merge($inputs, [
                'is_have_invest' => 1,
            ]);
        } elseif ($request->is_have_invest != null && $request->is_have_invest == 'لا') {
            $inputs = array_merge($inputs, [
                'is_have_invest' => 0,
            ]);
        }

        if ($request->is_have_money != null && $request->is_have_money == 'نعم') {
            $inputs = array_merge($inputs, [
                'is_have_money' => 1,
            ]);
        } elseif ($request->is_have_money != null && $request->is_have_money == 'لا') {
            $inputs = array_merge($inputs, [
                'is_have_money' => 0,
            ]);
        }

        if ($request->a7a != null && $request->a7a == 'نعم') {
            $inputs = array_merge($inputs, [
                'a7a' => 1,
            ]);
        } elseif ($request->a7a != null && $request->a7a == 'لا') {
            $inputs = array_merge($inputs, [
                'a7a' => 0,
            ]);
        }

        if ($request->is_25 != null && $request->is_25 == 'نعم') {
            $inputs = array_merge($inputs, [
                'is_25' => 1,
            ]);
        } elseif ($request->is_25 != null && $request->is_25 == 'لا') {
            $inputs = array_merge($inputs, [
                'is_25' => 0,
            ]);
        }

        if ($request->is_have_time != null && $request->is_have_time == 'نعم') {
            $inputs = array_merge($inputs, [
                'is_have_time' => 1,
            ]);
        } elseif ($request->is_have_time != null && $request->is_have_time == 'لا') {
            $inputs = array_merge($inputs, [
                'is_have_time' => 0,
            ]);
        }

        if ($request->jubna_campaign_id != null) {
            $inputs = array_merge($inputs, [
                'campaign' => $request->jubna_campaign_id,
            ]);
        }
        if ($request->campaign != null) {
            $inputs = array_merge($inputs, [
                'campaign' => $request->campaign,
            ]);
        }           
        
        if ($request->has('fields')) {
            $inputs = [];
            $fields = $request->fields;
    
            $inputs = [
                'first_name' => Arr::get($fields, 'first_name.value'),
                'country'    => Arr::get($fields, 'country.value'),
                'phone1'     => Arr::get($fields, 'phone1.value'),
                'email'      => Arr::get($fields, 'email.value'),
            ];
    
            $convertToBoolean = function ($field) use ($fields) {
                return Arr::get($fields, "$field.value") == 'نعم' ? 1 : 0;
            };
    
            $inputs['is_have_invest'] = $convertToBoolean('is_have_invest');
            $inputs['is_have_money'] = $convertToBoolean('is_have_money');
            $inputs['is_have_time']  = $convertToBoolean('is_have_time');
            $inputs['is_25']         = $convertToBoolean('is_25');
    
            $campaign = Arr::get($fields, 'jubna_campaign_id.value') ?: Arr::get($fields, 'campaign.value');
            if ($campaign) {
                $inputs['campaign'] = $campaign;
            }
        }

        if ($request->source != null) {
            $inputs = array_merge($inputs, [
                'source' => $request->source,
            ]);
        }else{
            $inputs = array_merge($inputs, [
                'source' => $source,
            ]);
        }

        $inputs = array_merge($inputs, [
            'pipeline_id' => $pipeline_id,
        ]);

        
        Client::create($inputs);

        return 1;
    }
}
