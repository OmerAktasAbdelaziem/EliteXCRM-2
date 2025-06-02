<?php

namespace App\Http\Controllers;

use App\Models\MoneyTrx;

class RequestController extends Controller
{
    public function index()
    {
        $request_data = $this->get_all_request_data();

        return view('request.index',compact(
            'request_data',
        ));
    }

    public function get_all_request_data()
    {
        $request_data = MoneyTrx::whereHas('client', function ($query) {
            if (auth()->user()) {
                $query->where('pipeline_id', auth()->user()->pipeline_id);
            }else{
                $query->where('pipeline_id', 1);
            }
        })->where('status', 'pending')->get();
        
        return $request_data;
    }
}
