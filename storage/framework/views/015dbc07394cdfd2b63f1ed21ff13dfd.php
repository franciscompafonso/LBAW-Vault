<?php $__env->startSection('content'); ?>
    <div class="max-w-2xl mx-auto mt-6 p-6 bg-white rounded-lg shadow-md">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-ultraViolet font-montserrat">Edit Profile</h2>
        </div>

        <!-- Edit Profile Form -->
        <form action="<?php echo e(route('user.edit.action', ['id' => $user->id])); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('POST'); ?>

            <!-- Profile Picture Input -->
            <div class="mb-4 flex items-center space-x-4">
                <div class="flex-shrink-0">
                <?php if($user->profilepicture): ?>
                    <img id="profile-picture-preview" src="<?php echo e(asset('storage/' . $user->profilepicture)); ?>" alt="Profile Picture" class="w-32 h-32 rounded-full border-2 border-ultraViolet">
                <?php else: ?>
                    <div id="profile-picture-preview" class="w-32 h-32 bg-gray-300 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-user text-white text-3xl"></i>
                    </div>
                <?php endif; ?>
                </div>
                <div>
                    <label for="profilepicture" class="block text-lg font-semibold font-montserrat">Upload Image</label>
                    <input type="file" id="profilepicture" name="profilepicture" class="w-full p-2 border border-gray-300 rounded-md mt-2" onchange="previewImage(event)">
                    <?php $__errorArgs = ['profilepicture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-600 text-sm mt-2"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-lg font-semibold font-montserrat">Name</label>
                <input type="text" id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" class="w-full p-2 border border-gray-300 rounded-md font-montserrat" required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-600 text-sm mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <!-- Username -->
            <div class="mb-4">
                <label for="username" class="block text-lg font-semibold font-montserrat">Username</label>
                <input type="text" id="username" name="username" value="<?php echo e(old('username', $user->username)); ?>" class="w-full p-2 border border-gray-300 rounded-md font-montserrat" required>
                <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-600 text-sm mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-lg font-semibold font-montserrat">Email</label>
                <input type="email" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="w-full p-2 border border-gray-300 rounded-md font-montserrat" required>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-600 text-sm mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-lg font-semibold font-montserrat">New Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded-md font-montserrat">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-600 text-sm mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-lg font-semibold font-montserrat">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-2 border border-gray-300 rounded-md font-montserrat">
                <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-600 text-sm mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 text-center">
                <button type="submit" class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Image Preview -->
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('profile-picture-preview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file); 
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/user/edit.blade.php ENDPATH**/ ?>