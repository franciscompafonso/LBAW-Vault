<ul data-id="<?php echo e($comment->id); ?>">
        <li data-id="<?php echo e($comment->id); ?>">
                <img 
                        src="<?php echo e(asset('storage/' . $comment->user->profilepicture)); ?>" 
                        alt="<?php echo e($comment->user->username); ?>" 
                        class="w-10 h-10 rounded-full object-cover"
                >
                <span class="text-gray-800 font-medium"><?php echo e($comment->user->name); ?></span>
                <span class="text-gray-800 font-medium"><?php echo e($comment->text); ?></span>
                <span class="text-gray-800 font-medium"><?php echo e('Score: ' . $comment->score); ?></span>
                <span class="text-gray-800 font-medium"><?php echo e(\Carbon\Carbon::parse($comment->date)->format('d/m')); ?></span>
                <span class="text-gray-800 font-medium"><?php echo e($comment->is_pinned ? 'Pinned' : ''); ?></span>
                <span class="text-gray-800 font-medium"><?php echo e($comment->edited ? 'Edited' : ''); ?></span>
                
                <?php if($comment->comment_by_id === Auth::id()): ?>
                        <form action="<?php echo e(route('comments.delete', $comment->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="bg-charcoal text-white px-6 py-3 rounded-md font-semibold shadow-lg hover:bg-keppel transition-colors duration-200">
                                        X
                                </button>
                        </form>
                <?php endif; ?>
        </li>
</ul><?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/partials/comment.blade.php ENDPATH**/ ?>