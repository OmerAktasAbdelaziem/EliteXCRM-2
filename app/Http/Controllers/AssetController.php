<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Asset;
use Illuminate\Support\Facades\Http;
use RadicalLoop\Eod\Facades\Eod;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $assets = Asset::whereNotNull('created_at');
        $currencies = Asset::select('currency')->distinct()->pluck('currency');
        if ($filters = $request->get('filters', [])) {
            if ($name = Arr::get($filters, 'name')) {
                $assets->where('name', 'like', "%$name%");
            }
            if ($symbol = Arr::get($filters, 'symbol')) {
                $assets->where('symbol', 'like', "%$symbol%");
            }
            if ($currency = Arr::get($filters, 'currency')) {
                $assets->where('currency', $currency);
            }
            if ($category = Arr::get($filters, 'category')) {
                $assets->where('category', $category);
            }
            if ($is_active = Arr::get($filters, 'is_active')) {
                $assets->where('is_active', $is_active);
            }
        }
        $assets = $assets->paginate(200);
        $filters = collect($filters);
        return view('asset.index',compact(
            'currencies',
            'filters',
            'assets',
        ));
    }
    
    public function create()
    {
        $asset = new Asset();

        return view('asset.show',compact(
            'asset',
        ));
    }
    
    public function store(Request $request)
    {
        $inputs = $request->only([
            'currency',
            'category',
            'symbol',
            'name',
        ]);
        $inputs['bid_price'] = 0.00;
        $inputs['ask_price'] = 0.00;

        if ($request->img) {
            $img = $request->file('img')->store('public/img');
            $inputs['img'] = str_replace('public/', 'storage/', $img);
        }
        
        Asset::Create($inputs);
        
        // Cache::put('all_assets', Asset::all());

        return redirect()->route('asset.index')->with('success','Asset Created Successfully');
    }
    
    public function show($id)
    {
        $asset = Asset::findOrFail($id);

        return view('asset.show',compact(
            'asset',
        ));
    }
    
    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $inputs = $request->only([
            'currency',
            'category',
            'symbol',
            'name',
        ]);

        $inputs['is_active'] = $request->is_active ? 1 : 0;

        if ($request->img) {
            $img = $request->file('img')->store('public/img');
            $inputs['img'] = str_replace('public/', 'storage/', $img);
        }

        $asset->update($inputs);
        
        // Cache::put('all_assets', Asset::all());

        return redirect()->back()->with('success','Asset Updated Successfully');
    }

    public function multiEdit(Request $request)
    {
        $inputs = [];
        if ($request->category) {
            $inputs['category'] = $request->category;
        }
        if ($request->is_active) {
            $inputs['is_active'] = $request->is_active == 'yes' ? 1 : 0;
        }

        if ($request->category || $request->is_active) {
            $assets = Asset::whereIn('id',$request->asset_ids)->get();
            foreach ($assets as $asset) {
                $asset->update($inputs);
            }
            
            // Cache::put('all_assets', Asset::all());
        }

        return redirect()->back()->with('success','Asset Updated Successfully');
    }

    public function delete($id)
    {
        $asset = Asset::findOrFail($id);
        $asset->delete();
        
        // Cache::put('all_assets', Asset::all());

        return redirect()->route('asset.index')->with('success','Asset Deleted Successfully');
    }
}
