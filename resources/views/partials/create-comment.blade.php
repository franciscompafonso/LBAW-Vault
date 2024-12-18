@can('comment', $event)
    <form action="{{ route('comments.store', ['event' => $event->id]) }}" method="POST" class="mt-6" enctype="multipart/form-data">
        @csrf
        <textarea 
            name="content" 
            rows="4" 
            class="w-full p-4 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-burgundy"
            placeholder="Add your comment here..."
            aria-labelledby="comments-heading"
            required></textarea>

        <!-- Photo Upload for Participators -->
        @can('uploadPhotos', $event)
            <div class="mt-4">
                <input 
                    type="file" 
                    name="photos[]" 
                    id="photos" 
                    multiple 
                    accept="image/*"
                    class="w-full p-2 mt-2 border border-gray-300 rounded-lg" 
                    aria-describedby="photo-help"
                    onchange="validatePhotos()" />
                <small class="text-gray-500">You can upload up to 15 photos.</small>
            </div>
        @endcan
        <div class="flex justify-end mt-4">
            <button 
                type="submit" 
                class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-cambridgeBlue hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue"
                aria-label="Submit your comment">
                Comment
            </button>
        </div>
    </form>
@endcan