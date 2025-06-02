@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
@endsection
@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sent Messages</h5>
                    <p class="card-text text-center">You didn't send any message yet!</p>
                    <br>
                    <div class="email-sidebar-header d-grid mt-2 mx-auto text-center w-50"  style="border-bottom: none"> <a href="javascript:;" class="btn btn-primary mx-auto text-center compose-mail-btn mt-2 w-50">
                        <i class='bx bx-plus me-2'></i>New Message</a>
                    </div>
                </div>
            </div>
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
@endsection

@section("script")
<script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
<script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
<script src="{{ url('assets/js/message.js?v2.944') }}"></script>
@endsection
