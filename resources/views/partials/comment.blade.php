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
                <span class="font-semibold text-gray-900 text-lg">{{ $comment->user->name }}</span>
            </div>

            <div class="col-span-3 text-right">
                <span class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($comment->date)->format('d/m/Y') }}</span>
            </div>
        </div>

        <!-- Content and Actions -->
        <div class="grid grid-cols-3 items-center gap-4">

            <div class="col-span-2">
                <p class="text-gray-800 text-left">{{ $comment->text }}</p>
            </div>

            <div class="text-right">
                @if ($comment->comment_by_id === Auth::id())
                    <form action="{{ route('comments.delete', $comment->id) }}" method="POST" class="inline">
                        @csrf
                        <a type="submit" class="text-red-200 hover:text-red-600 transition-colors duration-200">
                            X
                        </a>
                    </form>
                @else
                    <div class="flex justify-end items-center space-x-2">
                        <a 
                            class="like-btn text-gray-500 hover:text-green-500 transition-colors duration-200"
                            onclick="toggleLike(this)">
                            <i class="fas fa-thumbs-up"></i>
                        </a>
                        <a 
                            class="dislike-btn text-gray-500 hover:text-red-500 transition-colors duration-200"
                            onclick="toggleDislike(this)">
                            <i class="fas fa-thumbs-down"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </li>
</ul>

<script>
    function toggleLike(button) {
        button.classList.toggle('text-green-500');
        button.classList.toggle('text-gray-500');

        // Remove dislike if active
        const siblingDislike = button.nextElementSibling;
        if (siblingDislike.classList.contains('text-red-500')) {
            siblingDislike.classList.remove('text-red-500');
            siblingDislike.classList.add('text-gray-500');
        }
    }

    function toggleDislike(button) {
        button.classList.toggle('text-red-500');
        button.classList.toggle('text-gray-500');

        // Remove like if active
        const siblingLike = button.previousElementSibling;
        if (siblingLike.classList.contains('text-green-500')) {
            siblingLike.classList.remove('text-green-500');
            siblingLike.classList.add('text-gray-500');
        }
    }
</script>
