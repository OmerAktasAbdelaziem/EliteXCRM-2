<div class="card" style="height:570px;background-color: gainsboro">
    <div class="card-body comment-container">
        <form class="ajax-form" method="POST" action="{{ route('client-chat.store',$client->id) }}">
            @csrf
            <textarea class="form-control d-none comment" id="chat" name="message" placeholder="Type Message..." rows="3"></textarea>
            @error('message')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <div class="row mt-2">
                <div class="col">
                    {{ $chat->count() }} Support Chat
                </div>

                <div class="col justify-content-end d-flex">
                    @if ($add)
                        <button type="submit" class="submit_comment btn my-0 px-0 d-none" style="background-color: transparent"><i class="text-success bx bx-check h6 mb-0"></i></button>
                        <button type="button" class="plus_comment btn my-0 px-0" style="background-color: transparent"><i class="text-primary bx bx-plus h6 mb-0"></i></button>
                        <button type="button" class="x_comment btn my-0 px-0 d-none" style="background-color: transparent"><i class="text-danger bx bx-x h6 mb-0"></i></button>
                    @endif
                </div>
            </div>
        </form>
        
        <hr class="my-0" />
        <div class="chat-content m-0 ps ps--active-y" style="padding:15px;height: 470px !important;">
            @foreach ($chat as $message)
                <div class="@if($message->user_id) chat-content-leftside @else chat-content-rightside @endif">
                    <div class="d-flex">
                        <div class="flex-grow-1 ms-2">
                            <form class="ajax-form" method="POST" action="{{ route('client-chat.update',$message->id) }}">
                                @csrf
                                @method('PUT')
                                @if ($message->user_id)
                                    <p class="mb-0 chat-time">{{$message->user->first_name}} ({{$message->user->username}}), {{ date('d/m/Y H:i', strtotime($message->created_at)) }}</p>
                                @else
                                    <p class="mb-0 chat-time">{{$message->client->first_name}} ({{$message->client->username}}), {{ date('d/m/Y H:i', strtotime($message->created_at)) }}</p>
                                @endif
                                <textarea class="form-control border-0" name="message" placeholder="Type Message..." readonly style="resize: none;cursor: default; @if ($message->user_id) background-color:#4848e5;color: white; @else background-color:orange; @endif">{{$message->message}}</textarea>
                                @error('message')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @if ($update)
                                    <button type="button" class="border-0 edit-comment " style="background-color: transparent"><i class="text-primary bx bx-edit h5 mb-0"></i></button>
                                    <button type="submit" class="border-0 submit-comment d-none" style="background-color: transparent"><i class="text-primary bx bx-check h5 mb-0"></i></button>
                                    <button type="button" data-comment="{{$message->message}}" class="border-0 cancel-comment d-none" style="background-color: transparent"><i class="text-primary bx bx-x h5 mb-0"></i></button>
                                @endif
                                @if ($delete)
                                    <button type="submit" form="delete_chat_form{{$message->id}}" class="border-0 submit-comment" style="background-color: transparent"><i class="text-danger bx bx-trash h5 mb-0"></i></button>
                                @endif
                            </form>
                            @if ($delete) 
                                <form id="delete_chat_form{{$message->id}}" class="ajax-form" action="{{ route('client-chat.delete', $message->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <br>
            @endforeach
        </div>
        <hr>
    </div>
</div>