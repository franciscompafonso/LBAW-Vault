<?php $__env->startSection('content'); ?>
<section id="event-detail" class="max-w-full mx-auto p-8 bg-babyPowder shadow-xl rounded-lg">   
    <div class="flex flex-col lg:flex-row gap-10">
        <!-- Image Section (Left) -->
        <div class="flex-shrink-0 w-full lg:w-2/5">
            <img src="<?php echo e($event->image_url ?? 'default-image.jpg'); ?>" alt="<?php echo e($event->name); ?>" class="w-full h-80 object-cover rounded-lg shadow-md transition-transform transform hover:scale-105 duration-300">
        </div>

        <!-- Event Details Section (Right) -->
        <div class="flex-1 space-y-6">
            <h1 class="text-4xl font-bold text-ultraViolet"><?php echo e($event->name); ?></h1>   
            <p class="text-darkSlateGrey leading-relaxed text-lg"><?php echo e($event->description); ?></p>

            <div class="mt-6 space-y-4">
                <p class="text-darkSlateGrey text-lg">
                    <strong class="font-semibold">Start Date:</strong> <?php echo e(\Carbon\Carbon::parse($event->start_date)->format('d/m/Y')); ?>

                </p>
                <p class="text-darkSlateGrey text-lg">
                    <strong class="font-semibold">Start Time:</strong> <?php echo e(\Carbon\Carbon::parse($event->start_date)->format('H:i')); ?> 
                </p>
                <p class="text-darkSlateGrey text-lg">
                    <strong class="font-semibold">Location:</strong> <?php echo e($event->location->name ?? 'No location specified'); ?>

                </p>
            </div>

            <!-- cool for debugging! -->
            <?php if(session('message')): ?>
                <div class="bg-green-500 text-white px-4 py-2 rounded-md shadow-md">
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?>
            

            <?php if($event->participators->contains('id', Auth::id())): ?>
                <form action="<?php echo e(route('events.leave', $event->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                        Leave Event
                    </button>
                </form>
            <?php else: ?>
                <form action="<?php echo e(route('events.join', $event->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                        Join Event
                    </button>
                </form>
            <?php endif; ?>

            <?php if($event->creator_id === Auth::id()): ?>
                <form action="<?php echo e(route('events.delete', $event->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
                        Cancel Event
                    </button>
                </form>
                <button class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">   
                    <a href="<?php echo e(route('events.edit', $event->id)); ?>">
                        Edit Event
                    </a>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Action & Comments Section -->
    <div class="flex flex-col lg:flex-row mt-12 gap-10">
        <!-- Left: Reviews Section (Comments) -->
        <div class="flex-1 bg-whiteSmoke p-6 rounded-lg shadow-lg">   
            <h2 class="text-3xl font-bold text-burgundy mb-6 border-b-2 border-babyPowder pb-4">Reviews</h2>
            <div class="space-y-6 text-babyPowder">
                <?php echo $__env->renderEach('partials.comment', $event->comments()->orderBy('id')->get(), 'comment'); ?>
            </div>
        </div>

        <!-- Right: Participants List -->
        <div class="w-full lg:w-1/3 bg-whiteSmoke p-6 rounded-lg shadow-lg">   
            <h2 class="text-3xl font-bold text-keppel mb-6 border-b-2 border-babyPowder pb-4">Participants</h2>
            <ul class="mt-4 text-babyPowder space-y-4">
            <!-- Invite Form -->
            <div class="mt-4">
                <form action="<?php echo e(route('events.invite', $event->id)); ?>" method="POST" class="flex items-center space-x-2">
                    <?php echo csrf_field(); ?>
                    <!-- Input Text Box -->
                    <input 
                        id="invite" 
                        type="text" 
                        name="username" 
                        placeholder="@username" 
                        class="block w-full rounded-md font-montserrat border-darkSlateGrey shadow-sm focus:ring-ultraViolet focus:border-ultraViolet" 
                        required 
                    >
                    <!-- Optional Submit Button -->
                    <button 
                        type="submit" 
                        class="bg-keppel text-white px-4 py-2 rounded-md font-semibold shadow-lg hover:bg-charcoal transition-colors duration-200"
                    >
                        ✔️
                    </button>
                </form>
            </div>
            <?php $__currentLoopData = $event->participators()->orderBy('id')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('partials.participator', [
                    'participator' => $participator,
                    'creatorId' => $event->creator_id
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/pages/event.blade.php ENDPATH**/ ?>