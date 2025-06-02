		@extends("layouts.app")
        @section("style")
            <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
            <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
        @endsection
		@section("wrapper")
            <div class="page-wrapper">
                <div class="page-content">
                    <div class="chat-wrapper my-5">
                        <div class="chat-sidebar">
                            <div class="chat-sidebar-header">
                                <div class="mb-3"></div>
                                <div class="input-group input-group-sm"> <span class="input-group-text bg-transparent"><i class='bx bx-search'></i></span>
                                    <input type="text" class="form-control" placeholder="People, groups, & messages"> <span class="input-group-text bg-transparent"><i class='bx bx-dialpad'></i></span>
                                </div>
                            </div>
                            <div class="chat-sidebar-content">
                                <div class="tab-content " id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-Chats">
                                        <div class="chat-list">
                                            <div class="list-group list-group-flush">
                                            <ul class="p-0">
                                                @foreach ($messages as $message)
                                                    @php
                                                        if ($message->sender->lastseen_at != null) {
                                                            if(Carbon\Carbon::parse($message->sender->lastseen_at)->diffInMinutes(Carbon\Carbon::now()) <= 5){
                                                                $userstatus='online';
                                                                $userstatus_text='Active';
                                                            }
                                                            else {
                                                                $userstatus_text='Offline';
                                                                $userstatus='offline';
                                                            }
                                                        }else {
                                                            $userstatus_text='Offline';
                                                            $userstatus='offline';
                                                        }
                                                    @endphp
                                                    <li>
                                                        <a href="{{ route('messages.show',$message->id) }}" class="list-group-item">
                                                            <div class="d-flex position-relative">
                                                                <div class="chat-user-{{$userstatus}}">
                                                                    @if ($message->sender->gender && $message->sender->gender == 'Female')
                                                                        <img src="{{  Storage::disk('local')->url($system->femalePic) }}" width="42" height="42" class="rounded-circle" alt="" />
                                                                    @else
                                                                        <img src="{{  Storage::disk('local')->url($system->malePic) }}" width="42" height="42" class="rounded-circle" alt="" />
                                                                    @endif
                                                                </div>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <h6 class="mb-0 chat-title">{{$message->sender->first_name}} {{$message->sender->last_name}}</h6>
                                                                    <p class="mb-0 chat-msg">{{substr($message->text, 0, 25)}}.</p>
                                                                </div>
                                                                <div class="chat-time">{{ $message->formatted_date }}</div>
                                                                @if ($message->read == false)
                                                                    <span class="position-absolute top-0 start-100 translate-middle badge border border-light rounded-circle bg-danger p-1">
                                                                        <span class="visually-hidden">unread messages</span>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="email-sidebar-header d-grid mt-2"  style="border-bottom: none"> <a href="javascript:;" class="btn btn-primary compose-mail-btn mt-2">
                                        <i class='bx bx-plus me-2'></i>New Message</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-header d-flex align-items-center">
                            <form action="{{ route('archive.store',$message->id) }}" method="POST" name="archiveform" id="archiveform">
                                @csrf
                            </form>
                            <div class="chat-toggle-btn"><i class='bx bx-menu-alt-left'></i>
                            </div>
                            <div>
                                <h4 class="mb-1 font-weight-bold">{{$message->sender->first_name}} {{$message->sender->last_name}} ({{$message->sender->username}})</h4>
                                <div class="list-inline d-sm-flex mb-0 d-none"> <a href="javascript:;" class="list-inline-item d-flex align-items-center text-secondary"><small class='bx bxs-circle me-1 chart-{{$status}}'></small>{{$status_text}} Now</a>
                                </div>
                            </div>
                            <div class="chat-top-header-menu ms-auto"> <a href="javascript:;" onclick="$('#archiveform').submit();"><i class="bx bx-archive-in"></i></a>
                            </div>
                        </div>
                        <div class="chat-content">
                            <div class="chat-content-leftside">
                                <div class="d-flex">
                                    @if ($deleted_user->gender && $deleted_user->gender == 'Female')
                                        <img src="{{  Storage::disk('local')->url($system->femalePic) }}" width="48" height="48" class="rounded-circle" alt="" />
                                    @else
                                        <img src="{{  Storage::disk('local')->url($system->malePic) }}" width="48" height="48" class="rounded-circle" alt="" />
                                    @endif
                                    <div class="flex-grow-1 ms-2">
                                        <p class="mb-0 chat-time">{{$message->sender->first_name}}, {{$message->created_at}}</p>
                                        <p class="chat-left-msg">{{$message->text}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-footer d-flex align-items-center">
                            <div class="flex-grow-1 pe-2">
                                <div class="input-group">	<span class="input-group-text"><i class='bx bx-message'></i></span>
                                    <form action="{{ route('message.store',$message->sender->id ) }}" class="hidden" id="newmassage" name="newmassage" method="POST">
                                        @csrf
                                    </form>
                                    <textarea id="" name="text" rows="1" class="form-control" form="newmassage" placeholder="Type a message" style="resize: none;" required></textarea>
                                    
                                    <span class="input-group-text text-primary"><button type="submit" form="newmassage" class="input-group-text text-primary" style="border:0;outline:none"><i class='bx bxs-send'></i></button></span>
                                </div>
                            </div>
                        </div>
                        <!--start chat overlay-->
                        <div class="overlay chat-toggle-btn-mobile"></div>
                        <!--end chat overlay-->
                        <!--start compose mail-->
                        <div class="compose-mail-popup">
                            <div class="card">
                                <div class="card-header bg-dark text-white py-2 cursor-pointer">
                                    <div class="d-flex align-items-center">
                                        <div class="compose-mail-title">New Message</div>
                                        <div class="compose-mail-close ms-auto">x</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="email-form">
                                        <div class="mb-3">
                                            <form action="{{ route('messages.send') }}" method="POST" name="newmessage" id="newmessage">
                                                @csrf
                                            </form>
                                            <div class="input-group">
                                                <select class="single-select form-select" name="recipient_id" form="newmessage" required>
                                                    <option value="" selected>To :</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{$user->id}}">{{$user->username}} ({{$user->first_name}} {{$user->last_name}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <textarea class="form-control" name="text" form="newmessage" placeholder="Message" rows="10" cols="10"></textarea>
                                        </div>
                                        <div class="mb-0">
                                            <div class="d-flex align-items-center">
                                                <div class="">
                                                    <div class="btn-group">
                                                        <button type="submit" form="newmessage" class="btn btn-primary">Send</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		@endsection

@section("script")
<script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
<script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
<script src="{{ url('assets/js/message.js?v2.944') }}"></script>
@endsection
