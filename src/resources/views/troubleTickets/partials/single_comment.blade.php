<tr>
    <td style="width: 15%;">
        <img class="img-circle"
             src="{{$comment->author->picture}}"
             width="60"
             height="60"
             alt="profile">
        <br/>
        <small><i class="fa fa-user-o fa-fw"></i> {!! $comment->author->full_name !!}</small>
    </td>
    <td>
        <ul class="list-inline">
            <li class="list-inline-item">
                <small>
                    <i class="fa fa-clock-o fa-fw"></i> {{$comment->created_at->diffForHumans()}}
                </small>
            </li>
            @if($comment->is_private)
                <li class="list-inline-item">
                    <small>
                        <i class="fa fa-lock fa-fw"></i> @lang('Utility::attributes.comments.private')
                    </small>
                </li>
            @endif
        </ul>
        {!! nl2br($comment->body) !!}
    </td>
</tr>
