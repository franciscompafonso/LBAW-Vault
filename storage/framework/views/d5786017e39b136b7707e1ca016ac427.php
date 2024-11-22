<li data-id="<?php echo e($participator->id); ?>">
        <img 
                src="<?php echo e(asset('storage/' . $participator->profilepicture)); ?>" 
                alt="<?php echo e($participator->username); ?>" 
                class="w-10 h-10 rounded-full object-cover"
        >
        <span class="text-gray-800 font-medium"><?php echo e($participator->name); ?></span>
        <?php if($creatorId === Auth::id()): ?>
                <form action="<?php echo e(route('events.participators.kick', ['event' => $event->id, 'user' => $participator->id])); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                                X
                        </button>
                </form>
        <?php endif; ?>
</li><?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/partials/participator.blade.php ENDPATH**/ ?>