<ul data-id="{{$comment->id}}">
        <li data-id="{{$comment->id}}">
                <img 
                        src="{{ asset('storage/' . $comment->user->profilepicture) }}" 
                        alt="{{ $comment->username }}" 
                        class="w-10 h-10 rounded-full object-cover"
                >
                <span class="text-gray-800 font-medium">{{ $comment->user->name }}</span>
                <span class="text-gray-800 font-medium">{{ $comment->text }}</span>
                <span class="text-gray-800 font-medium">{{ 'Score:' . $comment->score }}</span>
                <span class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($comment->date)->format('d/m/Y') }}</span>
                <span class="text-gray-800 font-medium">{{ $comment->is_pinned ? 'Pinned' : '' }}</span>
                <span class="text-gray-800 font-medium">{{ $comment->edited ? 'Edited' : '' }}</span>
        </li>
</ul>