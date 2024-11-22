<article class="card bg-white shadow-md rounded-lg overflow-hidden hover:scale-105 transition-transform duration-300">
    <img src="<?php echo e($card->image_url ?? 'default-image.jpg'); ?>" alt="<?php echo e($card->name); ?>" class="w-full h-40 object-cover">

    <div class="p-4">
        <h3 class="text-lg font-bold text-gray-800"><?php echo e($card->name); ?></h3>

        <p class="text-sm text-gray-600 mt-2 line-clamp-2"><?php echo e($card->description); ?></p>

        <a href="<?php echo e(route('cards.show', $card->id)); ?>" class="mt-4 inline-block text-blue-400 hover:underline">
            View Details
        </a>
    </div>
</article><?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/partials/card.blade.php ENDPATH**/ ?>