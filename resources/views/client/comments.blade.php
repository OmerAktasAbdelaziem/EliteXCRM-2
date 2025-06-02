<div class="card" style="height:700px">
    <div class="card-body comment-container">
        <form class="ajax-form" method="POST" action="{{ route('client-comments.store', $client->id ?? '') }}">
            @csrf
            <textarea class="form-control d-none comment" id="comment" name="comment" placeholder="Type Comment..." rows="3"></textarea>
            @error('comment')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <div class="row mt-2">
                <div class="col">
                    {{ $comments->count() }} Comments
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
        <div class="chat-content m-0 ps ps--active-y" style="padding:15px;height: 600px !important;">
            @foreach ($comments as $comment)
                <div class="chat-content-leftside">
                    <div class="d-flex">
                        <div class="flex-grow-1 ms-2">
                            <form class="ajax-form" method="POST" action="{{ route('client-comments.update',$comment->id) }}">
                                @csrf
                                @method('PUT')
                                <p class="mb-0 chat-time">{{$comment->user->first_name}} ({{$comment->user->username}}), {{ date('d/m/Y H:i', strtotime($comment->created_at)) }}</p>
                                <textarea class="form-control border-0" name="comment" placeholder="Type Comment..." readonly style="resize: none;cursor: default;">{{$comment->comment}}</textarea>
                                @error('comment')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @if ($update)
                                    <button type="button" class="border-0 edit-comment " style="background-color: transparent"><i class="text-primary bx bx-edit h5 mb-0"></i></button>
                                    <button type="submit" class="border-0 submit-comment d-none" style="background-color: transparent"><i class="text-primary bx bx-check h5 mb-0"></i></button>
                                    <button type="button" data-comment="{{$comment->comment}}" class="border-0 cancel-comment d-none" style="background-color: transparent"><i class="text-primary bx bx-x h5 mb-0"></i></button>
                                @endif
                                @if ($delete)
                                    <button type="submit" form="delete_form{{$comment->id}}" class="border-0 submit-comment" style="background-color: transparent"><i class="text-danger bx bx-trash h5 mb-0"></i></button>
                                @endif
                            </form>
                            @if ($delete) 
                                <form id="delete_form{{$comment->id}}" class="ajax-form" action="{{ route('client-comments.delete', $comment->id) }}" method="POST">
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