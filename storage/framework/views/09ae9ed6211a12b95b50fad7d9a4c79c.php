<?php $__env->startSection('content'); ?>
    <div class="max-w-7xl mx-auto mt-6 p-6 bg-white rounded-lg shadow-md">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-ultraViolet font-montserrat">User Information</h2>
        </div>
    
        <!-- Profile Picture Section -->
        <div class="flex justify-center mb-6">
        <?php if($user->profilepicture): ?>
            <img src="<?php echo e(asset('images/profile-pictures/' . $user->profilepicture)); ?>" alt="Profile Picture" class="w-32 h-32 rounded-full border-2 border-darkSlateGrey">
        <?php else: ?>
            <div class="w-32 h-32 bg-gray-300 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-user text-white text-3xl"></i>
            </div>
        <?php endif; ?>
        </div>

        <!-- User Info Section -->
        <div class="text-center">
            <div class="flex justify-center items-center space-x-2">
                <p class="text-2xl font-montserrat font-semibold">Name:</p>
                <p class="text-2xl font-montserrat"><?php echo e($user->name); ?></p>
            </div>
            <div class="flex justify-center items-center space-x-2">
                <p class="text-2xl font-montserrat font-semibold">Username:</p>
                <p class="text-2xl font-montserrat"><?php echo e($user->username); ?></p>
            </div>
            <div class="flex justify-center items-center space-x-2">
                <p class="text-2xl font-montserrat font-semibold">Email:</p>
                <p class="text-2xl font-montserrat"><?php echo e($user->email); ?></p>
            </div>
        </div>

        <!-- Edit Profile Link -->
        <div class="flex flex-col items-center space-y-6 py-8 px-4">
            <a href="<?php echo e(route('user.edit.form', ['id' => $user->id])); ?>" 
                class="w-full max-w-md bg-ultraViolet hover:bg-cambridgeBlue text-whiteSmoke text-2xl font-bold py-3 rounded-lg shadow-md hover:text-darkSlateGrey text-center transition-all duration-200 font-montserrat">
                Edit Profile
            </a>
        </div>

        <!-- My Events -->
        <div class="mt-8">
            <h3 class="text-2xl font-bold text-ultraViolet mb-4">My Events</h3>
            <?php if($myEvents->isEmpty()): ?>
                <p class="text-gray-500">You have not created any events yet.</p>
            <?php else: ?>
                <section id="events" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
                    <?php $__currentLoopData = $myEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('partials.event', ['event' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </section>
            <?php endif; ?>
        </div>

        <!-- Participated Events Section -->
        <div class="mt-8">
            <h3 class="text-2xl font-bold text-ultraViolet mb-4">Events I've Participated In</h3>
            <?php if($participatedEvents->isEmpty()): ?>
                <p class="text-gray-500">You have not participated in any events yet.</p>
            <?php else: ?>
                <section id="participated-events" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
                    <?php $__currentLoopData = $participatedEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('partials.event', ['event' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </section>
            <?php endif; ?>
        </div>

        <!-- Not Yet Participated Events Section -->
        <div class="mt-8">
            <h3 class="text-2xl font-bold text-ultraViolet mb-4">Events I'm Interested In</h3>
            <?php if($notYetParticipatedEvents->isEmpty()): ?>
                <p class="text-gray-500">You have not shown interest in any events yet.</p>
            <?php else: ?>
                <section id="not-yet-participated-events" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
                    <?php $__currentLoopData = $notYetParticipatedEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('partials.event', ['event' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </section>
            <?php endif; ?>
        </div>


    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/user/profile.blade.php ENDPATH**/ ?>