<?php $__env->startSection('title', 'Products'); ?>

<?php $__env->startSection('content'); ?>
<section id="events" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
    <?php $__empty_1 = true; $__currentLoopData = $createdEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php echo $__env->make('partials.event', ['event' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-eerieBlack col-span-full">No events available in this category.</p> 
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/pages/events.blade.php ENDPATH**/ ?>