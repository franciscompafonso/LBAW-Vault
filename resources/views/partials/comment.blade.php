<script src="{{ url('js/partials/comment.js') }}" defer></script>
<ul class="space-y-4">
    <li class="p-4 space-y-4 bg-white border border-gray-200 rounded-lg shadow-md">
        <!-- Profile Image, Username, and Date -->
        <div class="grid items-center grid-cols-4 gap-4">
            <div class="flex items-center col-span-2 space-x-2">
                <img 
                    src="{{ asset('images/profile-pictures/' . ($comment->user->profilepicture?? App\Models\User::DEFAULT_PIC)) }}" 
                    alt="{{ $comment->user->username }}" 
                    class="object-cover w-12 h-12 rounded-full"
                >
                @if(!$comment->user->isdeleted)
                    <a href="{{ route('user.profile', ['username' => $comment->user->username]) }}" 
                    class="text-lg font-semibold text-gray-900 font-montserrat">
                        {{ $comment->user->name }}
                    </a>
                @else
                    <span class="text-lg font-semibold text-gray-500 font-montserrat">
                        {{ $comment->user->name }}
                    </span>
                @endif
            </div>

            <div class="col-span-2 text-right">
                <span class="text-sm text-gray-500 font-montserrat">{{ \Carbon\Carbon::parse($comment->date)->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <!-- Content and Actions -->
        <div class="grid items-center grid-cols-3 gap-4">

            <div class="col-span-2">
                <p class="text-left text-gray-800 font-montserrat">{{ $comment->text }}</p>
            </div>

            <div class="text-right">
                @if (auth()->guard('admin')->check() || auth()->check() && auth()->user()->can('delete', $comment))
                    <form id="deleteCommentForm" action="{{ route('comments.delete', $comment->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-200 transition-colors duration-200 border-transparent hover:text-red-600 hover:border-transparent hover:bg-transparent focus:bg-transparent focus:border-transparent" aria-label="Delete this comment">
                            X
                        </button>
                    </form>
                @endif

                <div class="flex items-center justify-end space-x-2">
                    <!-- Like button form -->
                    @can('like', $comment)
                        <form id="likeForm" action="{{ route('comment.like', $comment->id) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                            @if ($comment->feedback(Auth::id()) == 1)
                                <button 
                                    type="submit" aria-label="Like this comment"
                                    class="text-green-500 transition-colors duration-200 border-none like-btn hover:text-gray-500 focus:text-green-500 hover:bg-transparent focus:bg-transparent focus:outline-none" aria-label="Like this comment">
                                    <i class="fas fa-thumbs-up"></i>
                                </button>
                            @else
                                <button 
                                    type="submit" aria-label="Like this comment"
                                    class="text-gray-500 transition-colors duration-200 border-none like-btn hover:text-green-500 focus:text-green-500 hover:bg-transparent focus:bg-transparent focus:outline-none" aria-label="Like this comment">
                                    <i class="fas fa-thumbs-up"></i>
                                </button>
                            @endif
                        </form>
                    
                    <!-- Dislike button form -->
                        <form id="dislikeForm" action="{{ route('comment.dislike', $comment->id) }}" method="POST" class="inline">
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
                            class="text-gray-500 transition-colors duration-200 border-none like-btn hover:text-green-500 focus:text-green-500 hover:bg-transparent focus:bg-transparent focus:outline-none"
                            aria-label="Like this comment">
                            <i class="fas fa-thumbs-up"></i>
                        </a>
                        <a href="{{ url('/login') }}" title="Login"
                            class="text-gray-500 transition-colors duration-200 border-none dislike-btn hover:text-red-500 focus:text-red-500 hover:bg-transparent focus:bg-transparent focus:outline-none" 
                            aria-label="Dislike this comment">
                            <i class="fas fa-thumbs-down"></i>
                        </a>
                    @endcan

                    <span 
                        class="font-semibold font-montserrat text-lg 
                            {{ $comment->score > 0 ? 'text-green-500' : ($comment->score < 0 ? 'text-red-500' : 'text-gray-500') }}">
                        ({{ $comment->score }})
                    </span>
                </div>
            </div>
        </div>

        <!-- Display Photos if they exist -->
        @if ($comment->photos->isNotEmpty())
        <div class="grid grid-cols-3 gap-4 mt-4">
            @foreach ($comment->photos as $photo)
                <div class="w-full h-32 overflow-hidden bg-gray-100 rounded-lg">
                    <a href="{{ route('photos.show', $photo->id) }}">
                        <img 
                            src="{{ asset('images/others/' . $photo->path) }}" 
                            alt="Comment Photo"
                            class="object-cover w-full h-full cursor-pointer"
                        >
                   </a>
                </div>
            @endforeach
        </div>
        @endif
    
        <!-- Reply Button -->
        @can('reply', $comment)
            <div class="mt-2 text-left">
                <button 
                    class="transition-colors duration-200 border-none text-ultraViolet font-montserrat hover:text-blue-800 hover:bg-transparent focus:bg-transparent focus:outline-none"
                    aria-label="Reply to this comment"
                    onclick="toggleReplyForm({{ $comment->id }})">
                    Reply
                </button>
            </div>
        @endcan

        <!-- Reply Form -->
        <div id="reply-form-{{ $comment->id }}" class="hidden mt-4">
            <form id="replyCommentForm" action="{{ route('comments.reply', $comment->id) }}" method="POST">
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
                                src="{{ asset('images/profile-pictures/' . ($reply->user->profilepicture ?? App\Models\User::DEFAULT_PIC)) }}" 
                                alt="{{ $reply->user->username }}" 
                                class="object-cover w-12 h-12 rounded-full"
                            >
                            <a href="{{ route('user.profile', ['username' => $reply->user->username]) }}" 
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
