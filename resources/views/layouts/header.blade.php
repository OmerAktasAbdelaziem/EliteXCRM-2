    <!--start header wrapper-->
    <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                    </div>
                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center">
                            <li>
                                <form action="{{ route('client.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control border-start-0" id="gb_filter" name="gb_filter" value="{{ $gb_filter ?? '' }}" placeholder="Search" />
                                    </div>
                                </form>
                            </li>
                            <li class="nav-item dropdown dropdown-large text-white">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if ($notifications->count() != 0)
                                        @if ($notifications->count() > 99)
                                            <span class="alert-count">+99</span>
                                        @else
                                            <span class="alert-count">{{$notifications->count()}}</span>
                                        @endif
                                    @endif
                                    <i class='bx bx-bell text-white'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Notifications</p>
                                            <a href="{{ route('notification.mark_all_as_read') }}" class="msg-header-clear ms-auto">Marks all as read</a>
                                        </div>
                                    </a>
                                    <div class="header-notifications-list">
                                        @foreach ($notifications as $contact)
                                            @php
                                                $notified_at = Carbon\Carbon::parse($contact->notified_at);
                                                
                                                $now = Carbon\Carbon::now();
                                                
                                                $durationInSeconds = $now->diffInSeconds($notified_at);
                                                
                                                if ($durationInSeconds < 60) {
                                                    $duration = $durationInSeconds . ' Sec';
                                                } elseif ($durationInSeconds < 3600) {
                                                    $duration = round($durationInSeconds / 60) . ' Min';
                                                } elseif ($durationInSeconds < 86400) {
                                                    $duration = round($durationInSeconds / 3600) . ' Hour';
                                                } else {
                                                    $duration = round($durationInSeconds / 86400) . ' Days';
                                                }
                                            @endphp
                                            <a class="dropdown-item" href="{{ route('client.show', ['client' => $contact->id, 'from_notifi' => 1]) }}">
                                                <div class="d-flex align-items-center">
                                                    <div class="notify bg-light-primary text-primary"><i class="bx bx-group"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="msg-name">
                                                            {{$contact->first_name}} {{$contact->last_name}}
                                                            <span class="msg-time float-end">{{$duration}} ago</span>
                                                        </h6>
                                                        <p class="msg-info">
                                                            You have a new lead. <br>
                                                            click to view
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                        <div class="text-center msg-footer"></div>
                                </div>
                            </li>
                            {{-- messages --}}
                        </ul>
                    </div>
                    <div class="user-box dropdown">
                        <div class="setting-gear"><a href ="{{route('settings.index')}}"><i class="fa-solid fa-gear"></i></a></div>
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if (Auth::user()->gender && Auth::user()->gender == 'Female')
                                <img src="{{  Storage::disk('local')->url($system->femalePic) }}" class="user-img" alt="user avatar">
                            @else
                                <img src="{{  Storage::disk('local')->url($system->malePic) }}" class="user-img" alt="user avatar">
                            @endif
                            <div class="user-info ps-3">
                                <p class="user-name mb-0">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</p>
                                <p class="designattion mb-0">{{Auth::user()->username}}</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end profile-dropdown">
                            
                            @if(UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'overview') )
                                <li>
                                    <a class="dropdown-item" href="{{ route('overview.index') }}"><i class="bx bx-stats"></i><span>Overview</span></a>
                                </li>
                            @endif
                            @if(UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'settings') )
                                <li>
                                    <a class="dropdown-item" href="{{ route('settings.index') }}"><i class="bx bx-cog"></i><span>Settings</span></a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ url('user-profile') }}"><i class="bx bx-user"></i><span>Profile</span></a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
    
    <!-- Page wrapper end -->
