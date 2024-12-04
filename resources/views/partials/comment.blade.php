<ul class="space-y-4">
    <li class="p-4 space-y-4 bg-white border border-gray-200 rounded-lg shadow-md">
        <!-- Profile Image, Username, and Date -->
        <div class="grid items-center grid-cols-4 gap-4">
            <div class="flex items-center col-span-2 space-x-2">
                <img 
                    src="{{ asset($comment->user->profilepicture) }}" 
                    alt="{{ $comment->user->username }}" 
                    class="object-cover w-12 h-12 rounded-full"
                >
                <a href="{{ route('user.profile', ['id' => $comment->user->id]) }}" 
                class="text-lg font-semibold text-gray-900 font-montserrat">
                    {{ $comment->user->displayName }}
                </a>
            </div>

            <div class="col-span-2 text-right">
                <span class="text-sm text-gray-500 font-montserrat">{{ \Carbon\Carbon::parse($comment->date)->format('d/m/Y') }}</span>
            </div>
        </div>

        <!-- Content and Actions -->
        <div class="grid items-center grid-cols-3 gap-4">

            <div class="col-span-2">
                <p class="text-left text-gray-800 font-montserrat">{{ $comment->text }}</p>
            </div>

            <div class="text-right">
                @if (auth()->guard('admin')->check())
                    <form action="{{ route('admin.comment.delete', $comment->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-200 transition-colors duration-200 bg-transparent border-none font-montserrat hover:text-red-600 hover:bg-transparent focus:outline-none">
                            X
                        </button>
                    </form>
                @elseif ($comment->comment_by_id === Auth::id())
                    <form action="{{ route('comments.delete', $comment->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-200 transition-colors duration-200 bg-transparent border-none font-montserrat hover:text-red-600 hover:bg-transparent focus:outline-none">
                            X
                        </button>
                    </form>
                @else
                    <div class="flex items-center justify-end space-x-2">
                        <!-- Like button form -->
                        @if (Auth::check())
                            <form action="{{ route('comment.like') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                                @if ($comment->feedback(Auth::id()) == 1)
                                    <button 
                                        type="submit"
                                        class="text-green-500 transition-colors duration-200 border-none like-btn hover:text-gray-500 focus:text-green-500 hover:bg-transparent focus:bg-transparent focus:outline-none" aria-label="Like this comment">
                                        <i class="fas fa-thumbs-up"></i>
                                    </button>
                                @else
                                    <button 
                                        type="submit"
                                        class="text-gray-500 transition-colors duration-200 border-none like-btn hover:text-green-500 focus:text-green-500 hover:bg-transparent focus:bg-transparent focus:outline-none" aria-label="Like this comment">
                                        <i class="fas fa-thumbs-up"></i>
                                    </button>
                                @endif
                            </form>
                            
                            <!-- Dislike button form -->
                            <form action="{{ route('comment.dislike') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                                @if ($comment->feedback(Auth::id()) == -1)
                                    <button 
                                        type="submit"
                                        class="text-red-500 transition-colors duration-200 border-none dislike-btn hover:text-gray-500 focus:text-red-500 hover:bg-transparent focus:bg-transparent focus:outline-none" 
                                        aria-label="Dislike this comment">
                                        <i class="fas fa-thumbs-down"></i>
                                    </button>
                                @else
                                    <button 
                                        type="submit"
                                        class="text-gray-500 transition-colors duration-200 border-none dislike-btn hover:text-red-500 focus:text-red-500 hover:bg-transparent focus:bg-transparent focus:outline-none" 
                                        aria-label="Dislike this comment">
                                        <i class="fas fa-thumbs-down"></i>
                                    </button>
                                @endif
                            </form>
                        @else
                            <a href="{{ url('/login') }}" title="Login"
                                class="text-gray-500 transition-colors duration-200 border-none like-btn hover:text-green-500 focus:text-green-500 hover:bg-transparent focus:bg-transparent focus:outline-none" aria-label="Like this comment">
                                <i class="fas fa-thumbs-up"></i>
                            </a>
                            <a href="{{ url('/login') }}" title="Login"
                                class="text-gray-500 transition-colors duration-200 border-none dislike-btn hover:text-red-500 focus:text-red-500 hover:bg-transparent focus:bg-transparent focus:outline-none" 
                                aria-label="Dislike this comment">
                                <i class="fas fa-thumbs-down"></i>
                            </a>
                        @endif

                        <span 
                            class="font-semibold font-montserrat text-lg 
                                {{ $comment->score > 0 ? 'text-green-500' : ($comment->score < 0 ? 'text-red-500' : 'text-gray-500') }}">
                            ({{ $comment->score }})
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Display Photos if they exist -->
        @if ($comment->photos->isNotEmpty())
        <div class="grid grid-cols-3 gap-4 mt-4">
            @foreach ($comment->photos as $photo)
                <div class="w-full h-32 overflow-hidden bg-gray-100 rounded-lg">
                    <a href="{{ route('photos.show', $photo->id) }}">
                        <img 
                                src="{{ asset($photo->path) }}" 
                                alt="Comment Photo"
                                class="object-cover w-full h-full cursor-pointer"
                        >
                   </a>
                </div>
            @endforeach
        </div>
        @endif
    
        <!-- Reply Button -->
        @if (Auth::check())
        <div class="mt-2 text-left">
            <button 
                class="text-blue-500 transition-colors duration-200 border-none font-montserrat hover:text-blue-800 hover:bg-transparent focus:bg-transparent focus:outline-none"
                onclick="toggleReplyForm({{ $comment->id }})">
                Reply
            </button>
        </div>
        @endif

        <!-- Reply Form -->
        <div id="reply-form-{{ $comment->id }}" class="hidden mt-4">
            <form action="{{ route('comments.reply', $comment->id) }}" method="POST">
                @csrf
                <div class="flex items-center space-x-2">
                    <textarea name="text" class="w-full p-2 text-black border border-gray-300 rounded-lg" placeholder="Write your reply..." required></textarea>
                </div>
                <div class="mt-2 text-right">
                    <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-lg">Submit Reply</button>
                </div>
            </form>
        </div>

        <!-- Display Replies if they exist -->
        @if ($comment->replies->isNotEmpty())
            <ul class="pl-6 mt-4 space-y-4">
            @foreach ($comment->replies as $reply)
                <li class="p-4 space-y-4 bg-white border border-gray-200 rounded-lg shadow-md">
                    <div class="grid items-center grid-cols-4 gap-4">
                        <div class="flex items-center space-x-2">
                            <img 
                                src="{{ asset($reply->user->profilepicture) }}" 
                                alt="{{ $reply->user->username }}" 
                                class="object-cover w-12 h-12 rounded-full"
                            >
                            <a href="{{ route('user.profile', ['id' => $reply->user->id]) }}" 
                                class="text-lg font-semibold text-gray-900 font-montserrat">
                                    {{ $reply->user->username }}
                            </a>
                        </div>
                        <div class="col-span-3 text-right">
                            <span class="text-sm text-gray-500 font-montserrat">{{ \Carbon\Carbon::parse($reply->date)->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="grid items-center grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <p class="text-left text-gray-800 font-montserrat">{{ $reply->text }}</p>
                        </div>
                    </div>
                </li>
            @endforeach
            </ul>
        @endif
    </li>
</ul>

<!-- JavaScript to Toggle Reply Form -->
<script>
    function toggleReplyForm(commentId) {
        const form = document.getElementById('reply-form-' + commentId);
        form.classList.toggle('hidden'); // Toggles visibility of the form
    }
</script>
