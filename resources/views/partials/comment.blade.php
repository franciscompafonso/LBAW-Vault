<ul data-id="{{$comment->id}}">
        <li data-id="{{$comment->id}}">
                <img 
                        src="{{ asset('images/profile-pictures/' . $comment->user->profilepicture) }}" 
                        alt="{{ $comment->user->username }}" 
                        class="w-10 h-10 rounded-full object-cover"
                >
                <span class="text-gray-800 font-medium">{{ $comment->user->name }}</span>
                <span class="text-gray-800 font-medium">{{ $comment->text }}</span>
                <span class="text-gray-800 font-medium">{{ 'Score: ' . $comment->score }}</span>
                <span class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($comment->date)->format('d/m') }}</span>
                <span class="text-gray-800 font-medium">{{ $comment->is_pinned ? 'Pinned' : '' }}</span>
                <span class="text-gray-800 font-medium">{{ $comment->edited ? 'Edited' : '' }}</span>
                
                @if ($comment->comment_by_id === Auth::id())
                        <form action="{{ route('comments.delete', $comment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-charcoal text-white px-6 py-3 rounded-md font-semibold shadow-lg hover:bg-keppel transition-colors duration-200">
                                        X
                                </button>
                        </form>
                @endif
        </li>
</ul>