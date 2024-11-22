<?php $__env->startSection('content'); ?>
<!-- Events by Category Section -->
<section class="py-16 px-6">
    <h1 class="text-4xl font-bold text-ultraViolet font-montserrat mb-6"><?php echo e($category->name); ?></h1> 

    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php echo $__env->make('partials.event', ['event' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-eerieBlack font-montserrat col-span-full">No events available in this category.</p> 
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/pages/events_by_category.blade.php ENDPATH**/ ?>