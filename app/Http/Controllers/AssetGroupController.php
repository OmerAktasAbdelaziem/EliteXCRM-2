<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetGroupController extends Controller
{
    public function index(Request $request)
    {
        $assetGroups = AssetGroup::where('pipeline_id', Auth::user()->pipeline_id)->get();
        return view('asset_group.index',compact(
            'assetGroups',
        ));
    }
    
    public function create()
    {
        $group       = new AssetGroup();
        $assets      = Asset::select('name','id')->get();
        $groupAssets = Asset::whereIn('id',$group->asset_ids??[])->get();

        return view('asset_group.show',compact(
            'groupAssets',
            'assets',
            'group',
        ));
    }
    
    public function store(Request $request)
    {
        $inputs = $request->only([
            'name',
        ]);
        $inputs['pipeline_id'] = auth()->user()->pipeline_id;

        if ($asset_ids = $request->asset_ids) {
            $is_except = false;
            $is_all = false;
            foreach ($asset_ids as $a) {
                if (str_contains($a, 'except')) {
                    $is_except = true;
                }
                elseif (str_contains($a, 'all')) {
                    $is_all = true;
                    $is_except = false;
                    break;
                }
            }
            if ($is_except) {
                unset($asset_ids['except']);
                $asset_ids = Asset::whereNotIn('id', $asset_ids)->pluck('id')->toArray();
            }
            elseif ($is_all) {
                $asset_ids = Asset::pluck('id')->toArray();
            }
            else {
                $asset_ids = $request->asset_ids;
            }
            $inputs['asset_ids'] = $asset_ids;
        }
        
        $group = AssetGroup::Create($inputs);
        foreach ($asset_ids as $asset_id) {
            $asset = Asset::find($asset_id);
        
            if ($asset) {
                $is_percentage = $asset->is_percentage ?? [];
                $is_percentage[$group->id] = $is_percentage[$group->id] ?? ($is_percentage[1] ?? null);

                $leverage = $asset->leverage ?? [];
                $leverage[$group->id] = $leverage[$group->id] ?? ($leverage[1] ?? null);
        
                $size = $asset->size ?? [];
                $size[$group->id] = $size[$group->id] ?? ($size[1] ?? null);
        
                $ask_spread = $asset->ask_spread ?? [];
                $ask_spread[$group->id] = $ask_spread[$group->id] ?? ($ask_spread[1] ?? null);
        
                $bid_spread = $asset->bid_spread ?? [];
                $bid_spread[$group->id] = $bid_spread[$group->id] ?? ($bid_spread[1] ?? null);
        
                $buy_commission = $asset->buy_commission ?? [];
                $buy_commission[$group->id] = $buy_commission[$group->id] ?? ($buy_commission[1] ?? null);
        
                $sell_commission = $asset->sell_commission ?? [];
                $sell_commission[$group->id] = $sell_commission[$group->id] ?? ($sell_commission[1] ?? null);
        
                $asset->update([
                    'sell_commission' => $sell_commission,
                    'buy_commission'  => $buy_commission,
                    'is_percentage'   => $is_percentage,
                    'ask_spread'      => $ask_spread,
                    'bid_spread'      => $bid_spread,
                    'leverage'        => $leverage,
                    'size'            => $size,
                ]);
            }
        }

        return redirect()->route('assetGroup.show',$group->id)->with('success','Group Created Successfully');
    }
    
    public function show($id)
    {
        $group = AssetGroup::findOrFail($id);
        $assets      = Asset::select('name','id')->get();
        $groupAssets = Asset::whereIn('id',$group->asset_ids??[])->get();

        return view('asset_group.show',compact(
            'groupAssets',
            'assets',
            'group',
        ));
    }
    
    public function update(Request $request, $id)
    {
        $group = AssetGroup::findOrFail($id);

        $inputs = $request->only([
            'name',
        ]);

        if ($asset_ids = $request->asset_ids) {
            $is_except = false;
            $is_all = false;
            foreach ($asset_ids as $a) {
                if (str_contains($a, 'except')) {
                    $is_except = true;
                }
                elseif (str_contains($a, 'all')) {
                    $is_all = true;
                    $is_except = false;
                    break;
                }
            }
            if ($is_except) {
                unset($asset_ids['except']);
                $asset_ids = Asset::whereNotIn('id', $asset_ids)->pluck('id')->toArray();
            }
            elseif ($is_all) {
                $asset_ids = Asset::pluck('id')->toArray();
            }
            else {
                $asset_ids = $request->asset_ids;
            }
            foreach ($asset_ids as $asset_id) {
                $asset = Asset::find($asset_id);
            
                if ($asset) {
                    $leverage = $asset->leverage ?? [];
                    $leverage[$id] = $leverage[$id] ?? ($leverage[1] ?? null);
            
                    $size = $asset->size ?? [];
                    $size[$id] = $size[$id] ?? ($size[1] ?? null);
            
                    $ask_spread = $asset->ask_spread ?? [];
                    $ask_spread[$id] = $ask_spread[$id] ?? ($ask_spread[1] ?? null);
            
                    $bid_spread = $asset->bid_spread ?? [];
                    $bid_spread[$id] = $bid_spread[$id] ?? ($bid_spread[1] ?? null);
            
                    $buy_commission = $asset->buy_commission ?? [];
                    $buy_commission[$id] = $buy_commission[$id] ?? ($buy_commission[1] ?? null);
            
                    $sell_commission = $asset->sell_commission ?? [];
                    $sell_commission[$id] = $sell_commission[$id] ?? ($sell_commission[1] ?? null);
            
                    $asset->update([
                        'sell_commission' => $sell_commission,
                        'buy_commission'  => $buy_commission,
                        'ask_spread'      => $ask_spread,
                        'bid_spread'      => $bid_spread,
                        'leverage'        => $leverage,
                        'size'            => $size,
                    ]);
                }
            }
            
            $inputs['asset_ids'] = $asset_ids;
        }

        $group->update($inputs);

        return redirect()->back()->with('success','Group Updated Successfully');
    }

    public function multiEdit(Request $request, $id)
    {
        $assets = Asset::whereIn('id', $request->asset_ids)->get();
        $inputs = [];
        foreach ($assets as $asset) {
            if ($request->is_percentage) {
                $is_percentage = $asset->is_percentage??[];
                $is_percentage[$id] = $request->is_percentage=='Active'?1:0;
                $inputs['is_percentage'] = $is_percentage;
            }
            if ($request->leverage) {
                $leverage = $asset->leverage??[];
                $leverage[$id] = $request->leverage;
                $inputs['leverage'] = $leverage;
            }
            if ($request->size) {
                $size = $asset->size??[];
                $size[$id] = $request->size;
                $inputs['size'] = $size;
            }
            if ($request->bid_spread) {
                $bid_spread = $asset->bid_spread??[];
                $bid_spread[$id] = $request->bid_spread;
                $inputs['bid_spread'] = $bid_spread;
            }
            if ($request->ask_spread) {
                $ask_spread = $asset->ask_spread??[];
                $ask_spread[$id] = $request->ask_spread;
                $inputs['ask_spread'] = $ask_spread;
            }
            $asset->update($inputs);
        }

        return redirect()->back()->with('success','Group Updated Successfully');
    }

    public function deleteAsset(Request $request, $id)
    {
        $asset_group = AssetGroup::where('pipeline_id',Auth::user()->pipeline_id)->findOrFail($id);
        $asset_ids = $asset_group->asset_ids??[];
        $asset_ids = array_diff($asset_ids, $request->asset_ids);
        $asset_group->update([
            'asset_ids' => array_values($asset_ids)
        ]);

        return redirect()->back()->with('success','Asset Deleted from the asset group Successfully');
    }
}
