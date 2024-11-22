<?php $__env->startSection('title', 'Products'); ?>

<?php $__env->startSection('content'); ?>
<section id="cards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
    <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('partials.card', ['card' => $card], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- Form for adding a new product -->
    <article class="card bg-white shadow-md rounded-lg overflow-hidden max-w-sm m-4">
        <form class="new_card p-4">
            <input type="text" name="name" placeholder="New product" class="border border-gray-300 rounded p-2 w-full">
        </form>
    </article>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/pages/cards.blade.php ENDPATH**/ ?>