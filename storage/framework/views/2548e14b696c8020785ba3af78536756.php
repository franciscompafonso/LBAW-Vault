<?php $__env->startSection('content'); ?>
<div class="sm:my-auto w-full">
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="mt-6 text-center text-3xl font-montserrat font-extrabold text-ultraViolet">Sign in to your admin account</h2>
  </div>

  <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md bg-babyPowder py-8 px-6 shadow-md rounded-lg sm:px-10">
      <form method="POST" action="<?php echo e(route('admin.login')); ?>" class="space-y-6">
        <?php echo e(csrf_field()); ?>


        <!-- Email Field -->
        <div>
          <label for="email" class="block text-lg font-montserrat font-medium text-darkSlateGrey">Email address</label>
          <div class="mt-1">
            <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="email" 
              class="block w-full rounded-md font-montserrat border-darkSlateGrey shadow-sm focus:ring-ultraViolet focus:border-ultraViolet">
          </div>
          <?php if($errors->has('email')): ?>
            <p class="mt-1 text-lg text-red-500"><?php echo e($errors->first('email')); ?></p>
          <?php endif; ?>
        </div>

        <!-- Password Field -->
        <div>
          <div class="flex justify-between">
            <label for="password" class="block text-lg font-montserrat font-medium text-darkSlateGrey">Password</label>
            <a href="#" class="text-lg font-medium font-montserrat text-darkSlateGrey hover:text-cambrigdeBlue">Forgot password?</a>
          </div>
          <div class="mt-1">
            <input id="password" type="password" name="password" required autocomplete="current-password" 
              class="block w-full rounded-md font-montserrat border-darkSlateGrey shadow-sm focus:ring-ultraViolet focus:border-ultraViolet">
          </div>
          <?php if($errors->has('password')): ?>
            <p class="mt-1 font-montserrat text-lg text-red-500"><?php echo e($errors->first('password')); ?></p>
          <?php endif; ?>
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center">
          <input id="remember" name="remember" type="checkbox" 
            class="h-4 w-4 text-ultraViolet font-montserrat border-darkSlateGrey rounded focus:ring-ultraViolet">
          <label for="remember" class="my-auto font-montserrat ml-2 block text-lg text-ultraViolet">Remember Me</label>
        </div>

        <!-- Sign In Button -->
        <div class="mt-6 text-center">
            <button type="submit" class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">Sign in</button>
        </div>
      </form>

      <!-- Registration Link -->
      <p class="mt-6 text-center font-montserrat text-lg text-gray-600">
        Not a member? 
        <a href="<?php echo e(route('register')); ?>" class="font-medium font-montserrat text-darkSlateGrey hover:text-cambrigdeBlue">Register</a>
      </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/auth/admin-login.blade.php ENDPATH**/ ?>