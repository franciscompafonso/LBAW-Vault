<ul class="space-y-4">
    <li class="bg-white shadow-md p-4 rounded-lg border border-gray-200 space-y-4">
        <!-- Profile Image, Username, and Date -->
        <div class="grid grid-cols-4 items-center gap-4">
            <div class="flex items-center space-x-2">
                <img 
                    src="{{ asset('images/profile-pictures/' . $comment->user->profilepicture) }}" 
                    alt="{{ $comment->user->username }}" 
                    class="w-12 h-12 rounded-full object-cover"
                >
                <span class="font-semibold font-montserrat text-gray-900 text-lg">{{ $comment->user->name }}</span>
            </div>

            <div class="col-span-3 text-right">
                <span class="text-gray-500 text-sm font-montserrat">{{ \Carbon\Carbon::parse($comment->date)->format('d/m/Y') }}</span>
            </div>
        </div>

        <!-- Content and Actions -->
        <div class="grid grid-cols-3 items-center gap-4">

            <div class="col-span-2">
                <p class="text-gray-800 text-left font-montserrat">{{ $comment->text }}</p>
            </div>

            <div class="text-right">
                @if (auth()->guard('admin')->check())
                    <form action="{{ route('admin.comment.delete', $comment->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-200 font-montserrat hover:text-red-600 transition-colors duration-200 border-none hover:bg-transparent bg-transparent focus:outline-none">
                            X
                        </button>
                    </form>
                @elseif ($comment->comment_by_id === Auth::id())
                    <form action="{{ route('comments.delete', $comment->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-200 font-montserrat hover:text-red-600 transition-colors duration-200 border-none hover:bg-transparent bg-transparent focus:outline-none">
                            X
                        </button>
                    </form>
                @else
                    <div class="flex justify-end items-center space-x-2">
                        <!-- Like button form -->
                        <form action="{{ route('comment.like') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                            @if ($comment->feedback(Auth::id()) == 1)
                                <button 
                                    type="submit"
                                    class="like-btn hover:text-gray-500 text-green-500 focus:text-green-500 transition-colors duration-200 border-none hover:bg-transparent focus:bg-transparent focus:outline-none" aria-label="Like this comment">
                                    <i class="fas fa-thumbs-up"></i>
                                </button>
                            @else
                                <button 
                                    type="submit"
                                    class="like-btn text-gray-500 hover:text-green-500 focus:text-green-500 transition-colors duration-200 border-none hover:bg-transparent focus:bg-transparent focus:outline-none" aria-label="Like this comment">
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
                                    class="dislike-btn hover:text-gray-500 text-red-500 focus:text-red-500 transition-colors duration-200 border-none hover:bg-transparent focus:bg-transparent focus:outline-none" 
                                    aria-label="Dislike this comment">
                                    <i class="fas fa-thumbs-down"></i>
                                </button>
                            @else
                                <button 
                                    type="submit"
                                    class="dislike-btn text-gray-500 hover:text-red-500 focus:text-red-500 transition-colors duration-200 border-none hover:bg-transparent focus:bg-transparent focus:outline-none" 
                                    aria-label="Dislike this comment">
                                    <i class="fas fa-thumbs-down"></i>
                                </button>
                            @endif
                        </form>

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
                <div class="w-full h-32 bg-gray-100 rounded-lg overflow-hidden">
                    <a href="{{ route('photos.show', $photo->id) }}">
                        <img 
                                src="{{ asset('images/others/' . $photo->path) }}" 
                                alt="Comment Photo"
                                class="w-full h-full object-cover cursor-pointer"
                        >
                   </a>
                </div>
            @endforeach
        </div>
        @endif
    </li>
</ul>
