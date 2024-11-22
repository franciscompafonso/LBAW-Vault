<?php $__env->startSection('content'); ?>
<!-- Wrapper to ensure sections stack vertically -->
<div class="w-full">
    <!-- Banner Section -->
    <section class="bg-whiteSmoke py-16 shadow-md w-full">
        <div class="max-w-6xl mx-auto flex items-center flex-col lg:flex-row px-6">
            <!-- Text Section -->
            <div class="w-full lg:w-1/2 text-left mb-8 lg:mb-0">
                <h2 class="text-4xl font-montserrat font-semibold mb-4 text-eerieBlack">Empowering Communities, One Event at a Time.</h2>
                <?php if(!Auth::check()): ?>
                    <p class="text-2xl font-montserrat text-deepNavy mb-6">Join us now!</p>
                <?php else: ?>
                    <p class="text-2xl font-montserrat text-deepNavy mb-6">Welcome back, <?php echo e(Auth::user()->name); ?>!</p>
                <?php endif; ?>
            </div>

            <!-- Image Section -->
            <div class="w-full lg:w-1/2 flex justify-center lg:justify-end">
                <img src="<?php echo e(asset('images/others/main-pic.png')); ?>" alt="Main Picture" class="bg-white w-64 h-64 rounded-full object-cover shadow-lg transition-transform transform hover:scale-105 duration-300">
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-8 px-4">
        <div class="flex flex-col space-y-6">
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group flex items-center space-x-4">
                <!-- Category Name with Link -->
                <h2 class="text-4xl font-montserrat font-semibold text-eerieBlack group-hover:font-bold transition-all inline-block">
                    <a href="<?php echo e(url('events/category=' . $category->id)); ?>" 
                    class="group-hover:text-eerieBlack group-hover:font-bold text-eerieBlack">
                        <?php echo e($category->name); ?>

                    </a>
                </h2>

                <!-- "Explore all >" Link -->
                <a href="<?php echo e(url('events/category=' . $category->id)); ?>" 
                class="opacity-0 group-hover:opacity-100 translate-x-[-20px] group-hover:translate-x-0 my-auto text-cambridgeBlue text-xl font-bold hover:text-darkSlateGrey transition-all duration-300 ease-in-out font-montserrat transform">
                    Explore all >
                </a>
            </div>

            <!-- Events Grid -->
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                <?php $__currentLoopData = $category->events->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <!-- Limite o número de eventos -->
                    <?php echo $__env->make('partials.home_event', ['event' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($category->events->isEmpty()): ?>
                    <p class="text-eerieBlack col-span-full font-montserrat">No events available in this category.</p>
                <?php endif; ?>
            </div>

            <!-- Separator between categories -->
            <hr class="my-6 border-t-2 border-lightGrey">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/pages/home.blade.php ENDPATH**/ ?>