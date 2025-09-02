<style>
    @media screen and (min-width: 1285px) {
        .nav-container {
            width: 50%;
        }
    }
</style>
<div class="nav-container" style="top: 0;z-index: 10;">
    <div class="mobile-topbar-header">
        <div>
            <img src="{{ url('assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">EliteX CRM</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <nav class="topbar-nav">
        <ul class="metismenu" id="menu">
            @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'leads_list') || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'leads_create'))
                <li>
                    <a href="javascript:;">
                        <div class="parent-icon"><i class='bx bx-user'></i>
                        </div>
                        <div class="menu-title">Leads</div>
                    </a>
                    <ul class="d-none">
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'leads_list'))
                            <li>
                                <a href="{{ route('client.index') }}"><i class="bx bx-user"></i>Leads List</a>
                            </li>
                        @endif
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'leads_create'))
                            <li>
                                <a href="{{ route('client.create') }}"><i class="bx bx-user-check"></i>New Lead</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            
            @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'users_list') || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'users_create'))
                <li>
                    <a href="javascript:;">
                        <div class="parent-icon"><i class='bx bx-user-circle'></i>
                        </div>
                        <div class="menu-title">Users</div>
                    </a>
                    <ul class="d-none">
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'users_list'))
                            <li>
                                <a href="{{ route('user.index') }}"><i class="bx bx-user-circle"></i>Users List</a>
                            </li>
                        @endif
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'users_create'))
                            <li> <a href="{{ route('user.create') }}"><i class="bx bx-user-plus"></i>New User</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'parts_list') || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'parts_create'))
                <li>
                    <a href="javascript:;">
                        <div class="parent-icon"><i class='bx bx-grid-alt'></i>
                        </div>
                        <div class="menu-title">Parts</div>
                    </a>
                    <ul class="d-none">
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'parts_list'))
                            <li>
                                <a href="{{ route('part.index') }}"><i class="bx bx-grid-alt"></i>Parts List</a>
                            </li>
                        @endif
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'parts_create'))
                            <li>
                                <a href="{{ route('part.create') }}"><i class="bx bx-plus-circle"></i>New part</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'teams_list') || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'teams_create'))
                <li>
                    <a href="javascript:;">
                        <div class="parent-icon"><i class='bx bx-user-pin'></i>
                        </div>
                        <div class="menu-title">Teams</div>
                    </a>
                    <ul class="d-none">
                        
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'teams_list') )
                            <li>
                                <a href="{{ route('team.index') }}"><i class="bx bx-user-pin"></i>Teams List</a>
                            </li>
                        @endif
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'teams_create'))
                            <li>
                                <a href="{{ route('team.create') }}"><i class="bx bx-plus-circle"></i>New Team</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'reports_view'))
            
                <li>
                    <a href="javascript:;">
                        <div class="parent-icon"><i class='bx bx-notepad'></i>
                        </div>
                        <div class="menu-title">Reports</div>
                    </a>
                    <ul class="d-none">
                        <li> <a href="{{ route('reports.index') }}"><i class="bx bx-receipt"></i>Lead reports</a>
                        </li>
                    </ul>
                </li>
            @endif
            @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionOfMultiInPipeline(Auth::user(), Auth::user()->pipeline_id, ['parts_list','parts_create','requests_page_view','status_list','roles_list','emails_template_list','retention_view','bank_list','asset_list','assetGroup_list']))
            
                <li>
                    <a href="javascript:;">
                        <div class="parent-icon"><i class='bx bx-grid-alt'></i>
                        </div>
                        <div class="menu-title">Administration</div>
                        @if ($totalRequests > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">@if ($totalRequests > 99) +99 @else {{$totalRequests}} @endif<span class="visually-hidden">unread messages</span></span>
                        @endif
                    </a>
                    <ul class="d-none">
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'retention_view'))
                        
                            <li>
                                <a href="{{ route('main_tp.retention') }}"><i class="bx bx-dollar"></i>Retention</a>
                            </li>
                        @endif
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'requests_page_view'))
                            <li>
                                <a href="{{ route('request.index') }}">
                                    <i class="bx bx-coin"></i>Requests
                                    @if ($totalRequests > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">@if ($totalRequests > 99) +99 @else {{$totalRequests}} @endif<span class="visually-hidden">unread messages</span></span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'status_list'))
                        
                            <li>
                                <a href="{{ route('status.index') }}"><i class="bx bx-support"></i>Status</a>
                            </li>
                        @endif
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'roles_list'))
                            <li>
                                <a href="{{ route('role.index') }}"><i class="bx bx-sitemap"></i>Roles</a>
                            </li>
                        @endif
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'emails_template_list'))
                            <li>
                                <a href="{{ route('emails.index') }}"><i class="bx bx-mail-send"></i>Emails</a>
                            </li>
                        @endif
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'bank_list'))
                            <li>
                                <a href="{{ route('bank.index') }}"><i class="bx bx-money"></i>Banks</a>
                            </li>
                        @endif
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'asset_list'))
                        
                            <li>
                                <a href="{{ route('asset.index') }}"><i class="bx bx-wallet-alt"></i>Assets</a>
                            </li>
                        @endif
                        @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'assetGroup_list'))
                            <li>
                                <a href="{{ route('assetGroup.index') }}"><i class="bx bx-shield-quarter"></i>Asset Groups</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @php
                $supportCheck = \App\Models\Pipeline::where('support_ids', 'LIKE', '%"'.Auth::id().'"%')->get();
            @endphp
            @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'pipeline_list') || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'pipeline_create')  || $supportCheck->count() > 0)
                <li>
                    <a href="javascript:;">
                        <div class="parent-icon"><i class='bx bx-layer'></i>
                        </div>
                        <div class="menu-title">Pipelines</div>
                    </a>
                    <ul class="d-none">
                        @if (UserPermission::isSuperAdmin(Auth::user())  || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'pipeline_list'))
                            <li>
                                <a href="{{ route('pipeline.index') }}"><i class="bx bx-layer"></i>Pipelines List</a>
                            </li>
                        @endif
                        @if (UserPermission::isSuperAdmin(Auth::user())  || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'pipeline_create'))
                            <li>
                                <a href="{{ route('pipeline.create') }}"><i class="bx bx-layer-plus"></i>New Pipeline</a>
                            </li>
                        @endif
                        @if (UserPermission::isSuperAdmin(Auth::user())  || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'pipeline_view'))
                            @foreach ($nav_pipelines as $pipeline)
                                <li>
                                    <a href="{{ route('pipeline.switch', $pipeline->id) }}"><i class="bx bx-layer"></i>{{$pipeline->name}}</a>
                                </li>
                            @endforeach
                        @elseif($supportCheck->count() > 0)
                            <li>
                                <a href="{{ route('pipeline.switch', 1) }}"><i class="bx bx-layer"></i>Main Pipeline</a>
                            </li>
                            @foreach ($supportCheck as $pipeline)
                                <li>
                                    <a href="{{ route('pipeline.switch', $pipeline->id) }}"><i class="bx bx-layer"></i>{{$pipeline->name}}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </li>
            @endif
            
            @if (UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'statistics_view'))
                <li>
                    <a href="{{ route('user.stats') }}">
                        <div class="parent-icon"><i class='bx bx-bar-chart-alt-2'></i>
                        </div>
                        <div class="menu-title">Statistics</div>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>