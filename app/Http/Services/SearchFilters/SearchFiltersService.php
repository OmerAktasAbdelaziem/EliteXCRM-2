<?php

namespace App\Http\Services\SearchFilters;

use App\Facades\UserPermission;
use App\Http\Services\SearchFilters\Interfaces\SearchFiltersServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SearchFiltersService implements SearchFiltersServiceInterface {

    protected string $filtersSessionKey = 'search_filters';
    protected string $sortSessionKey = 'search_sort';
    protected array $order = [
        'by' => 'created_at', 
        'dir' => 'desc', 
    ];

    public function applyFilters(Builder $query, ?array $filters = []): Builder {
        $userAuth = Auth::user();
        $pipelineId = $userAuth->pipeline_id;
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($userAuth, $pipelineId);

        if (!empty($filters)) {
            $query->where(function ($query) use ($filters, $isSuperAdmin, $isPipelineAdmin, $pipelineId) {

                if ($id = Arr::get($filters, 'id')) {
                    $query->where('id', 'like', $id . '%');
                }

                if ($smart = Arr::get($filters, 'smart')) {
                    if ($smart == 'Active') {
                        $query->where('smart_user_id', '!=', null)->where('smart_user_id', '!=', '');
                    } else {
                        $query->where(function ($q) {
                            $q->where('smart_user_id', null)->orWhere('smart_user_id', '');
                        });
                    }
                }

                if ($enabled = Arr::get($filters, 'enabled')) {
                    if ($enabled == 'Active') {
                        $query->whereNotNull('broker_id')->where('account_type', 'Real');
                    } else {
                        $query->where(function ($q) {
                            $q->whereNull('broker_id')->orWhere('account_type', '!=', 'Real');
                        });
                    }
                }

                if ($textQuery = strtolower(Arr::get($filters, 'name'))) {
                    $query->where(DB::raw("
                        LOWER(CONCAT_WS(' ', COALESCE(first_name, ''), COALESCE(last_name, '')))
                    "), 'like', '%' . $textQuery . '%');
                }

                if ($countries = Arr::get($filters, 'country')) {
                    $countryParts = [];
                    $isExcept = false;

                    if (is_array($countries)) {
                        foreach ($countries as $country) {
                            if (str_contains($country, 'except')) {
                                $isExcept = true;
                                $country = str_replace('except', '', $country);
                            }
                            $countryParts = array_merge($countryParts, explode(',', $country));
                        }
                    }

                    if ($isExcept) {
                        $query->where(function ($q) use ($countryParts) {
                            $q->whereNotIn('country', array_map('trim', $countryParts))->orWhere('country', null);
                        });
                    } else {
                        $query->whereIn('country', array_map('trim', $countryParts));
                    }
                }


                if ($mail = Arr::get($filters, 'mail')) {
                    $query->where('email', 'like', '%' . $mail . '%');
                }

                if ($phone = Arr::get($filters, 'phone')) {
                    $query->where(function ($q) use ($phone) {
                        $q->where('phone1', 'like', '%' . $phone . '%')->orWhere('phone2', 'like', '%' . $phone . '%');
                    });
                }

                if ($type = Arr::get($filters, 'type')) {
                    $query->where('account_type', $type);
                }

                if ($user = Arr::get($filters, 'user')) {

                    $isUnassigned = false;
                    foreach ($user as $key => $u) {
                        if (str_contains($u, 'unassigned')) {
                            unset($user[$key]);
                            $isUnassigned = true;
                        }
                    }
                    $isExcept = false;
                    foreach ($user as $key => $u) {
                        if (str_contains($u, 'except')) {
                            unset($user[$key]);
                            $isExcept = true;
                        }
                    }
                    if ($isExcept) {
                        $query->where(function ($q) use ($user, $isSuperAdmin, $isPipelineAdmin, $pipelineId, $isUnassigned) {
                            $q->whereNotIn('user_id', $user);
                            if ($isUnassigned || $isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), $pipelineId, 'show_unassigned_leads')) {
                                $q->where('user_id', '!=' , null);
                            }

                        });
                    } else {
                        $query->where(function ($q) use ($user) {
                            $q->whereIn('user_id', $user);
                        });

                    }
                }

                if ($status = Arr::get($filters, 'status')) {
                    $isExcept = false;
                    foreach ($status as $s) {
                        if (str_contains($s, 'except')) {
                            $isExcept = true;
                        }
                    }
                    if ($isExcept) {
                        $query->whereNotIn('sales_status', $status);
                    } else {
                        $query->whereIn('sales_status', $status);
                    }
                }

                if ($fromDate = Arr::get($filters, 'ftd_fromTo')) {
                    $dates = preg_split('/\s*-\s*/', trim($fromDate));

                    if (isset($dates[0]) && !empty($dates[0])) {
                        $formattedFromDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
                        $query->where('ftd_date', '>=', $formattedFromDate);
                    }

                    if (isset($dates[1]) && !empty($dates[1])) {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('ftd_date', '<=', $formattedToDate);
                    } else {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('ftd_date', '<=', $formattedToDate);
                    }
                }

                if ($fromDate = Arr::get($filters, 'first_comment_at_fromTo')) {
                    $dates = preg_split('/\s*-\s*/', trim($fromDate));

                    if (isset($dates[0]) && !empty($dates[0])) {
                        $formattedFromDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
                    } else{
                        $formattedFromDate = null;
                    }

                    if (isset($dates[1]) && !empty($dates[1])) {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
                    } else {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
                    }

                    $query->whereHas('comments', function ($q) use ($formattedFromDate, $formattedToDate) {
                        $q->whereBetween('created_at', [$formattedFromDate, $formattedToDate]);
                    });
                }

                if ($fromDate = Arr::get($filters, 'assigned_at_fromTo')) {
                    $dates = preg_split('/\s*-\s*/', trim($fromDate));

                    if (isset($dates[0]) && !empty($dates[0])) {
                        $formattedFromDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
                        $query->where('assigned_at', '>=', $formattedFromDate);
                    }

                    if (isset($dates[1]) && !empty($dates[1])) {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('assigned_at', '<=', $formattedToDate);
                    } else {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('assigned_at', '<=', $formattedToDate);
                    }
                }

                if ($fromDate = Arr::get($filters, 'modified_at_fromTo')) {
                    $dates = preg_split('/\s*-\s*/', trim($fromDate));

                    if (isset($dates[0]) && !empty($dates[0])) {
                        $formattedFromDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
                        $query->where('updated_at', '>=', $formattedFromDate);
                    }

                    if (isset($dates[1]) && !empty($dates[1])) {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('updated_at', '<=', $formattedToDate);
                    } else {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('updated_at', '<=', $formattedToDate);
                    }
                }

                if ($fromDate = Arr::get($filters, 'reg_at_fromTo')) {
                    $dates = preg_split('/\s*-\s*/', trim($fromDate));

                    if (isset($dates[0]) && !empty($dates[0])) {
                        $formattedFromDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
                        $query->where('reg_date', '>=', $formattedFromDate);
                    }

                    if (isset($dates[1]) && !empty($dates[1])) {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('reg_date', '<=', $formattedToDate);
                    } else {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('reg_date', '<=', $formattedToDate);
                    }
                }

                if ($fromDate = Arr::get($filters, 'created_fromTo')) {
                    $dates = preg_split('/\s*-\s*/', trim($fromDate));

                    if (isset($dates[0]) && !empty($dates[0])) {
                        $formattedFromDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
                        $query->where('created_at', '>=', $formattedFromDate);
                    }
                    if (isset($dates[1]) && !empty($dates[1]) && $dates[1] != "") {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('created_at', '<=', $formattedToDate);
                    } else {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('created_at', '<=', $formattedToDate);
                    }
                }

                if ($source = Arr::get($filters, 'source')) {
                    $isExcept = false;
                    foreach ($source as $s) {
                        if (str_contains($s, 'except')) {
                            $isExcept = true;
                        }
                    }
                    if ($isExcept) {
                        $query->where(function ($q) use ($source) {
                            $q->whereNotIn('source', $source)->orWhere('source', null)->orWhere('source', '');
                        });
                    } else {
                        $query->whereIn('source', $source);
                    }
                }

                if ($teams = Arr::get($filters, 'teams')) {
                    $query->whereHas('user', function ($query) use ($teams) {
                        $query->where('team_id', $teams);
                    });
                }

                if ($created_by = Arr::get($filters, 'created_by')) {
                    $isExcept = false;
                    foreach ($created_by as $s) {
                        if (str_contains($s, 'except')) {
                            $isExcept = true;
                        }
                    }
                    if ($isExcept) {
                        $query->where(function ($q) use ($created_by) {
                            $q->whereNotIn('created_by', $created_by)->orWhere('created_by', null)->orWhere('created_by', '');
                        });
                    } else {
                        $query->whereIn('created_by', $created_by);
                    }
                }
            });
        }

        return $query;
    }

    public function applySort(Builder $query): Builder {

        $sort = $this->getSetSort();

        if($sort && isset($sort['by'])){
            $by = $sort['by'];
            $dir = $sort['dir'] ?? 'desc';

            if ($by == 'created_at' || $by == 'ftd_date' || $by == 'first_name') {
                $query = $query->orderBy($by, $dir);
            } elseif ($by == 'team') {
                $query = $query->leftJoin('users', 'clients.user_id', '=', 'users.id')
                        ->leftJoin('teams', 'users.team_id', '=', 'teams.id')
                        ->select('clients.*', DB::raw('COALESCE(teams.name, "") as team_name'))
                        ->orderBy('team_name', $dir);
            }
            
        }

        return $query->orderBy('id', $dir);

    }

    public function getSetSort(?string $by = null, string $dir = 'desc'): array
    {
        if ($by) {
            $this->order = [
                'by' => $by, 
                'dir' => $dir, 
            ];
            // Session::put($this->sortSessionKey, $order);
        }

        // if (Session::has($this->sortSessionKey)) {
        //     return Session::get($this->sortSessionKey, []);
        // }

        return $this->order;
    }


    public function getPrev(Builder $query, $current)
    {
        $sort = $this->getSetSort();

        $by = $sort['by'];
        $dir = $sort['dir'] ?? 'desc';
        $reverseDir = $dir === 'asc' ? 'desc' : 'asc';

        return $query
            ->where(function ($q) use ($by, $reverseDir, $current) {
                if ($reverseDir === 'desc') {
                    $q->where($by, '<', $current->{$by})
                        ->orWhere(function ($q) use ($by, $current) {
                            $q->where($by, $current->{$by})
                                ->where('id', '<', $current->id);
                        });
                } else {
                    $q->where($by, '>', $current->{$by})
                        ->orWhere(function ($q) use ($by, $current) {
                            $q->where($by, $current->{$by})
                                ->where('id', '>', $current->id);
                        });
                }
            })
            ->orderBy($by, $reverseDir)
            ->orderBy('id', $reverseDir)
            ->first();

    }


    public function getNext(Builder $query, $current)
    {
        $sort = $this->getSetSort();

        $by = $sort['by'];
        $dir = $sort['dir'] ?? 'desc';

        return $query
            ->where(function ($q) use ($by, $dir, $current) {
                if ($dir === 'desc') {
                    $q->where($by, '<', $current->{$by})
                        ->orWhere(function ($q) use ($by, $current) {
                            $q->where($by, $current->{$by})
                                ->where('id', '<', $current->id);
                        });
                } else {
                    $q->where($by, '>', $current->{$by})
                        ->orWhere(function ($q) use ($by, $current) {
                            $q->where($by, $current->{$by})
                                ->where('id', '>', $current->id);
                        });
                }
            })
            ->orderBy($by, $dir)
            ->orderBy('id', $dir)
            ->first();
    }

    public function clear(){
        Session::remove($this->filtersSessionKey);
        Session::remove($this->sortSessionKey);
    }

}
