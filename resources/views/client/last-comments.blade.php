<div class="card-body">
    <div class="chat-content m-0 ps ps--active-y comments" style="padding:15px;height: 600px !important;">
        @foreach ($comments as $comment)
            <div class="chat-content-leftside">
                <div class="d-flex">
                    <div class="flex-grow-1 ms-2">
                        <form class="ajax-form" method="POST" action="{{ route('client-comments.update',$comment->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <p class="mb-0 chat-time"><a @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'users_show')) href="{{ route('user.show',$comment->user_id ) }}" @endif>{{$comment->user->first_name}} ({{$comment->user->username}})</a>, {{ date('d/m/Y H:i', strtotime($comment->created_at)) }} ,<a @if ($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_show')) href="{{ route('client.show',$comment->client_id ) }}" @endif>{{$comment->client->first_name}}</a></p>
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
                                <button type="submit" form="delete_form_{{$comment->id}}" class="border-0 submit-comment" style="background-color: transparent"><i class="text-danger bx bx-trash h5 mb-0"></i></button>
                            @endif
                        </form>
                        @if ($delete) 
                            <form id="delete_form_{{$comment->id}}" class="ajax-form" action="{{ route('client-comments.delete', $comment->id) }}" method="POST">
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
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    let users_show = @json(($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'users_show')));
    let leads_show = @json(($isSuperAdmin || UserPermission::hasPermissionInPipeline($userAuth, $pipelineId, 'leads_show')));
    let update = @json($update);
    let deleteRole = @json($delete);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    setInterval(() => {
        const isEditing = $('.comments textarea').is(':not([readonly])');
        if (!isEditing) {
            $.ajax({
                url: '/overview/getLastComments',
                type: 'GET',
                success: function(response) {
                    if (Array.isArray(response) && response.length > 0) {
                        $('.comments').empty();
                        
                        let commentsHtml = '';

                        response.forEach(comment => {
                            commentsHtml += `
                                <div class="chat-content-leftside">
                                    <div class="d-flex">
                                        <div class="flex-grow-1 ms-2">
                                            <form class="ajax-form" method="POST" action="/clients/${comment.id}/update">
                                                <input type="hidden" name="_token" value="${csrfToken}">
                                                <input type="hidden" name="_method" value="PUT">
                                                <p class="mb-0 chat-time">
                                                    <a ${users_show ? `href="/user/${comment.user_id}"` : ''}>${comment.user.first_name} (${comment.user.username})</a>, 
                                                    ${new Date(comment.created_at).toLocaleDateString('en-GB')} ${new Date(comment.created_at).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' })},
                                                    <a ${leads_show ? `href="/client/${comment.client_id}"` : ''}>${comment.client.first_name}</a>
                                                </p>
                                                <textarea class="form-control border-0" name="comment" placeholder="Type Comment..." readonly style="resize: none; cursor: default;">${comment.comment}</textarea>
                                                ${comment.errors?.comment ? `<div class="text-danger">${comment.errors.comment}</div>` : ''}
                                                ${update ? `
                                                    <button type="button" class="border-0 edit-comment" style="background-color: transparent"><i class="text-primary bx bx-edit h5 mb-0"></i></button>
                                                    <button type="submit" class="border-0 submit-comment d-none" style="background-color: transparent"><i class="text-primary bx bx-check h5 mb-0"></i></button>
                                                    <button type="button" data-comment="${comment.comment}" class="border-0 cancel-comment d-none" style="background-color: transparent"><i class="text-primary bx bx-x h5 mb-0"></i></button>
                                                ` : ''}
                                                ${deleteRole ?`
                                                    <button type="submit" form="delete_form_${comment.id}" class="border-0 submit-comment" style="background-color: transparent"><i class="text-danger bx bx-trash h5 mb-0"></i></button>
                                                ` : ''}
                                            </form>
                                            ${deleteRole ? `
                                                <form id="delete_form_${comment.id}" class="ajax-form" action="/clients/${comment.id}/delete/" method="POST">
                                                    <input type="hidden" name="_token" value="${csrfToken}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                </form>
                                            ` : ''}
                                        </div>
                                    </div>
                                </div>
                                <br>
                            `;
                        });
                        
                        $('.comments').append(commentsHtml);
                        function autoResize($textarea) {
                            $textarea.css('height', 'auto');
                            $textarea.css('height', $textarea[0].scrollHeight + 'px');
                        }
                        $('textarea').each(function() {
                            autoResize($(this));
                    
                            $(this).on('input', function() {
                                autoResize($(this));
                            });
                        });
                    } else {
                        console.warn('No comments found or response is not an array.');
                    }
                },
                error: function(error) {
                    console.error('Error fetching comments:', error);
                }
            });
        }
    }, 5000);
</script>
