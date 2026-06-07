<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//Service
use App\Http\Services\Asset\AssetGroupService;
use App\Http\Services\Asset\AssetService;
use App\Facades\UserPermission;

class AssetGroupController extends Controller {

    protected $assetGroupService;
    protected $assetService;

    public function __construct(
            AssetGroupService $assetGroupService,
            AssetService $assetService
    ) {
        $this->assetGroupService = $assetGroupService;
        $this->assetService = $assetService;
    }

    public function index(Request $request) {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        $assetGroups = AssetGroup::where('pipeline_id', Auth::user()->pipeline_id)->get();
        return view('asset_group.index', compact(
                        'isSuperAdmin',
                        'isPipelineAdmin',
                        'pipelineId',
                        'userAuth',
                        'assetGroups',
                ));
    }

    public function create() {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        $group = new AssetGroup();
        $assets = Asset::select('name', 'id')->get();
        $assetGroupAssignments = Asset::whereIn('id', $group->assetAssignments->pluck('asset') ?? [])->get();

        return view('asset_group.show', compact(
                        'assetGroupAssignments',
                        'assets',
                        'group',
                        'pipelineId',
                        'isSuperAdmin',
                        'isPipelineAdmin',
                        'userAuth',
                ));
    }

    public function store(Request $request) {




        //uncomment this code to handle old data of assets & asset groups just for once and then remove it
       /* $groups = AssetGroup::all();
        try {
            foreach ($groups as $group) {
                if (!empty($group->asset_ids)) {
                    $assets = Asset::whereIn('id', $group->asset_ids)->get();
                    foreach ($assets as $asset) {
//                if($asset->id == 2){
//                    print_r([
//                    'asset_group'=>$group->id,'asset'=>$asset->id??null,
//                        'size'=>$asset->size[$group->id]??null,
//                    'leverage'=>$asset->leverage[$group->id]??null,
//                    'bid_spread'=>$asset->bid_spread[$group->id]??null,
//                    'ask_spread'=>$asset->ask_spread[$group->id]??null,
//                    'buy_commission'=>$asset->buy_commission[$group->id]??null,
//                    'sell_commission'=>$asset->sell_commission[$group->id]??null,
//                    'is_percentage'=>$asset->is_percentage[$group->id]??null]);echo '<br><br><br><br><br><br>';
//                }
                        if (isset($asset)) {
                            $this->assetGroupService->createAssetGroupAssignment([
                                'asset_group' => $group->id, 'asset' => $asset->id ?? null,
                                'size' => $asset->size[$group->id] ?? null,
                                'leverage' => $asset->leverage[$group->id] ?? null,
                                'bid_spread' => $asset->bid_spread[$group->id] ?? null,
                                'ask_spread' => $asset->ask_spread[$group->id] ?? null,
                                'buy_commission' => $asset->buy_commission[$group->id] ?? null,
                                'sell_commission' => $asset->sell_commission[$group->id] ?? null,
                                'is_percentage' => $asset->is_percentage[$group->id] ?? null]
                            );
                        }
                    }
                }
            }
        } catch (QueryException $e) {
            print_r($group);
            die;
        }die('done');*/
        //end of uncomment this code to handle old data of assets & asset groups just for once and then remove it



        /*
          $inputs = $request->only([
          'name',
          ]);
          $inputs['pipeline_id'] = auth()->user()->pipeline_id;
          //dd($request->asset_ids);
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

         */


        $inputs = $request->only([
            'name',
            'default'
        ]);
        $inputs['pipeline_id'] = auth()->user()->pipeline_id;
        if($request->default == 1){
        $ifDefaultExist = $this->assetGroupService->getByFilters([
            ['field' => 'pipeline_id', 'conditions' => ['=' => auth()->user()->pipeline_id]],
            ['field' => 'default',   'conditions' => ['=' => 1]],
        ])->first();
        if(isset($ifDefaultExist->default) && $ifDefaultExist->default == 1){
            $this->assetGroupService->update($ifDefaultExist->id,['default'=>0]);
        }
        }
//dd($request->asset_ids);
        $group = $this->assetGroupService->create($inputs)->first();
        if ($asset_ids = $request->asset_ids) {
            $is_except = false;
            $is_all = false;
            foreach ($asset_ids as $a) {
                if (str_contains($a, 'except')) {
                    $is_except = true;
                } elseif (str_contains($a, 'all')) {
                    $is_all = true;
                    $is_except = false;
                    break;
                }
            }
            if ($is_except) {
                unset($asset_ids['except']);
                //$asset_ids = Asset::whereNotIn('id', $asset_ids)->pluck('id')->toArray();
                $asset_ids = $this->assetService->getByFilters([['field' => 'id', 'conditions' => ['notIn' => $asset_ids]]])->pluck('id')->toArray();
            } elseif ($is_all) {
                //$asset_ids = Asset::pluck('id')->toArray();
                $asset_ids = $this->assetService->getAll()->pluck('id')->toArray();
            } else {
                $asset_ids = $request->asset_ids;
            }
            //$inputs['asset_ids'] = $asset_ids;
        
        //percentage
        //leverage
        //size
        //ask_spread
        //bid_spread
        //buy_commission
        //sell_commission
        //$group = AssetGroup::Create($inputs);
        
        foreach ($asset_ids as $asset_id) {
            //$asset = Asset::find($asset_id);
            $asset = $this->assetService->getByFilters([['field' => 'id', 'conditions' => ['=' => $asset_id]]])->first();
            $asset->load(['groupAssignments']);
            //dd($asset);die('d');
            if ($asset) {

                //$firstGroup->load(['groupAssignments']);
                //$asset->groupAssignments[0]->is_percentage
                if (!$asset->groupAssignments->where('asset_group', $group->id)->isEmpty()) {
                    $assetAssignment = $asset->groupAssignments->where('asset_group', $group->id)->first();
                    $create = 0;
                } else {
                    $assetAssignment = $asset->groupAssignments->where('asset_group', 1)->first();
                    $create = 1;
                }
                $data = [];
                $data['asset'] = $asset->id;
                $data['asset_group'] = $group->id;
                $data['is_percentage'] = $assetAssignment->is_percentage ?? null;
                $data['leverage'] = $assetAssignment->leverage ?? null;
                $data['size'] = $assetAssignment->size ?? null;
                $data['ask_spread'] = $assetAssignment->ask_spread ?? null;
                $data['bid_spread'] = $assetAssignment->bid_spread ?? null;
                $data['buy_commission'] = $assetAssignment->buy_commission ?? null;
                $data['sell_commission'] = $assetAssignment->sell_commission ?? null;

                if ($create) {
                    $this->assetGroupService->createAssetGroupAssignment($data);
                } else {
                    $this->assetGroupService->updateAssetGroupAssignment($assetAssignment->id, $data);
                }



                /* $is_percentage = $asset->groupAssignments>first()->is_percentage ?? '';echo $is_percentage;die;
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
                  ]); */
            }
        }
}
        return redirect()->route('assetGroup.show', $group->id)->with('success', 'Group Created Successfully');
    }

    public function show($id) {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);
        
        
        
        //$group = AssetGroup::findOrFail($id);
        $group = $this->assetGroupService->getById($id)->first();
        //$assets      = Asset::select('name','id')->get();
        $assets = $this->assetService->getAll();
        //$groupAssets = Asset::whereIn('id',$group->asset_ids??[])->get();
        $group->load(['assetAssignments.relatedAsset']); //dd($group);
        $groupAssets = $group->assetAssignments->pluck('asset')->toArray();
        $assetGroupAssignments = $group->assetAssignments;
        //dd($groupAssets);

        return view('asset_group.show', compact(
                        'assetGroupAssignments',
                        'assets',
                        'groupAssets',
                        'group',
                'userAuth',
        'pipelineId',
        'isSuperAdmin',
        'isPipelineAdmin',
                ));
    }

    public function update(Request $request, $id) {
        //$group = AssetGroup::findOrFail($id);
        $group = $this->assetGroupService->getById($id)->first();
        $group->load(['assetAssignments']);
        $groupAssets = [];
        foreach ($group->assetAssignments as $assetAssignment) {
            $groupAssets[] = $assetAssignment->asset;
        }


        $inputs = $request->only([
            'name',
            'default',
        ]);
        $inputs['pipeline_id'] = auth()->user()->pipeline_id;
        if($request->default == 1){
            $ifDefaultExist = $this->assetGroupService->getByFilters([
                ['field' => 'pipeline_id', 'conditions' => ['=' => auth()->user()->pipeline_id]],
                ['field' => 'default',   'conditions' => ['=' => 1]],
            ])->first();
            if(isset($ifDefaultExist->default) && $ifDefaultExist->default == 1){
                $this->assetGroupService->update($ifDefaultExist->id,['default'=>0]);
            }
            }else{
                $ifDefaultExist = $this->assetGroupService->getByFilters([
                    ['field' => 'pipeline_id', 'conditions' => ['=' => auth()->user()->pipeline_id]],
                    ['field' => 'default',   'conditions' => ['=' => 1]],
                    ['field' => 'id',   'conditions' => ['!=' => $id]],
                ])->count();
                if($ifDefaultExist == 0){
                return redirect()->back()->with('error', 'At least one Asset group should be default, choose another default the retry');
                }
            }

        if ($asset_ids = $request->asset_ids) {
            $is_except = false;
            $is_all = false;
            foreach ($asset_ids as $a) {
                if (str_contains($a, 'except')) {
                    $is_except = true;
                } elseif (str_contains($a, 'all')) {
                    $is_all = true;
                    $is_except = false;
                    break;
                }
            }
            if ($is_except) {
                unset($asset_ids['except']);
                //$asset_ids = Asset::whereNotIn('id', $asset_ids)->pluck('id')->toArray();
                $asset_ids = $this->assetService->getByFilters([['field' => 'id', 'conditions' => ['notIn' => $asset_ids]]])->pluck('id')->toArray();
            } elseif ($is_all) {
                //$asset_ids = Asset::pluck('id')->toArray();
                $asset_ids = $this->assetService->getAll()->pluck('id')->toArray();
            } else {
                $asset_ids = $request->asset_ids;
            }
            $this->assetGroupService->update($id, $inputs);
            $groupAssetsToDelete = array_diff($groupAssets, $asset_ids);
            //print_r($groupAssetsToDelete);die;
            if (!empty($groupAssetsToDelete)) {
                $this->assetGroupService->deleteAssetGroupAssignment(['asset_group' => $group->id, 'asset' => $groupAssetsToDelete]);
            }

            foreach ($asset_ids as $asset_id) {

                //$asset = Asset::find($asset_id);
                $asset = $this->assetService->getByFilters([['field' => 'id', 'conditions' => ['=' => $asset_id]]])->first();
                $asset->load(['groupAssignments']);

                if ($asset) {



                    //$firstGroup->load(['groupAssignments']);
                    //$asset->groupAssignments[0]->is_percentage
                    if (!$asset->groupAssignments->where('asset_group', $group->id)->isEmpty()) {
                        $assetAssignment = $asset->groupAssignments->where('asset_group', $group->id)->first();
                        $create = 0;
                    } else {
                        $assetAssignment = $asset->groupAssignments->where('asset_group', 1)->first();
                        $create = 1;
                    }
                    $data = [];
                    $data['asset'] = $asset->id;
                    $data['asset_group'] = $group->id;
                    $data['is_percentage'] = $assetAssignment->is_percentage ?? null;
                    $data['leverage'] = $assetAssignment->leverage ?? null;
                    $data['size'] = $assetAssignment->size ?? null;
                    $data['ask_spread'] = $assetAssignment->ask_spread ?? null;
                    $data['bid_spread'] = $assetAssignment->bid_spread ?? null;
                    $data['buy_commission'] = $assetAssignment->buy_commission ?? null;
                    $data['sell_commission'] = $assetAssignment->sell_commission ?? null;

                    if ($create) {
                        $this->assetGroupService->createAssetGroupAssignment($data);
                    } else {
                        $this->assetGroupService->updateAssetGroupAssignment($assetAssignment->id, $data);
                    }



                    /*
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
                      ]); */
                }
            }

            $inputs['asset_ids'] = $asset_ids;
        }

        $group->update($inputs);

        return redirect()->back()->with('success', 'Group Updated Successfully');
    }

    public function multiEdit(Request $request, $id) {
        //echo $id.'<br>';print_r($request->assetGroupAssignment_ids);die;
        //$assets = Asset::whereIn('id', $request->assetGroupAssignment_ids)->get();
        $assetGroupAssignmentIds = $request->assetGroupAssignment_ids;
        //$inputs = [];
        foreach ($assetGroupAssignmentIds as $assetGroupAssignmentId) {
            $isPercentage = $request->is_percentage ?? null;
            $isPercentage = $isPercentage === 'Active' ? 1 : 0;
            $this->assetGroupService->updateAssetGroupAssignment($assetGroupAssignmentId, [
                'is_percentage' => $isPercentage,
                'leverage' => $request->leverage ?? null,
                'size' => $request->size ?? null,
                'bid_spread' => $request->bid_spread ?? null,
                'ask_spread' => $request->ask_spread ?? null,
            ]);
            /* if ($request->is_percentage) {
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
              $asset->update($inputs); */
        }

        return redirect()->back()->with('success', 'Group Updated Successfully');
    }

    public function deleteAsset(Request $request, $id) {
//        $group = $this->assetGroupService->getById($id)->first();
//        $group->load(['assetAssignments']);
//        $assetAssignmentsArray = [];
//        foreach($group->assetAssignments??[] as $assetAssignment){
//            $assetAssignmentsArray[] = $assetAssignment->id;
//            //
//        }
        if ($request->assetGroupAssignment_ids) {
            $this->assetGroupService->deleteAssetGroupAssignment(['id' => $request->assetGroupAssignment_ids]);
        }
        //$this->assetGroupService->deleteByParams(['id'=>$group->id]);


        /* $asset_group = AssetGroup::where('pipeline_id',Auth::user()->pipeline_id)->findOrFail($id);
          $asset_ids = $asset_group->asset_ids??[];
          $asset_ids = array_diff($asset_ids, $request->asset_ids);
          $asset_group->update([
          'asset_ids' => array_values($asset_ids)
          ]); */

        return redirect()->back()->with('success', 'Asset Deleted from the asset group Successfully');
    }
}
