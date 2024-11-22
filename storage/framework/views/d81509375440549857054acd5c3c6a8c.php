<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto mt-6 p-6 bg-white rounded-lg shadow-md">
  <div class="text-center mb-6">
    <!-- Page Title -->
    <h2 class="text-3xl font-bold text-ultraViolet font-montserrat">Create Event</h2>
  </div>

  <!-- Form Container -->
  <form method="POST" action="<?php echo e(route('events.store')); ?>" class="space-y-8">
    <?php echo csrf_field(); ?>

    <!-- Event Name -->
    <div class="mb-4">
      <label for="name" class="block text-lg font-semibold font-montserrat">Event Name</label>
      <input id="name" type="text" name="name" value="<?php echo e(old('name')); ?>" required autofocus autocomplete="name" class="w-full p-2 border border-gray-300 rounded-md font-montserrat">
      <?php if($errors->has('name')): ?>
        <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('name')); ?></span>
      <?php endif; ?>
    </div>

    <!-- Description -->
    <div class="mb-4">
      <label for="description" class="block text-lg font-semibold font-montserrat">Description</label>
      <textarea id="description" name="description" required class="w-full p-2 border border-gray-300 rounded-md font-montserrat"><?php echo e(old('description')); ?></textarea>
      <?php if($errors->has('description')): ?>
        <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('description')); ?></span>
      <?php endif; ?>
    </div>

    <!-- Start Date and Time -->
    <div class="mb-4">
      <label for="start_date" class="block text-lg font-semibold font-montserrat">Start Date & Time</label>
      <input id="start_date" type="datetime-local" name="start_date" value="<?php echo e(old('start_date')); ?>" required class="w-full p-2 border border-gray-300 rounded-md font-montserrat">
      <style>
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
          filter: invert(1) grayscale(1) contrast(2);
        }
      </style>
      <?php if($errors->has('start_date')): ?>
        <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('start_date')); ?></span>
      <?php endif; ?>
    </div>

    <!-- End Date and Time -->
    <div class="mb-4">
      <label for="end_date" class="block text-lg font-semibold font-montserrat">End Date & Time</label>
      <input id="end_date" type="datetime-local" name="end_date" value="<?php echo e(old('end_date')); ?>" required class="w-full p-2 border border-gray-300 rounded-md font-montserrat">
      <style>
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
          filter: invert(1) grayscale(1) contrast(2);
        }
      </style>
      <?php if($errors->has('end_date')): ?>
        <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('end_date')); ?></span>
      <?php endif; ?>
    </div>

    <!-- Location Selector -->
    <div class="mb-4">
      <label for="location_id" class="block text-lg font-semibold font-montserrat">Location</label>
      <select id="location_id" name="location_id" required class="px-2 block text-lg font-semibold font-montserrat">
        <option value="">Select a Location</option>
        <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($location->id); ?>" <?php echo e(old('location_id') == $location->id ? 'selected' : ''); ?>><?php echo e($location->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <?php if($errors->has('location_id')): ?>
        <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('location_id')); ?></span>
      <?php endif; ?>
    </div>

    <!-- Category Selector -->
    <div class="mb-4">
      <label for="category_id" class="block text-lg font-semibold font-montserrat">Category</label>
      <select id="category_id" name="category_id" required class="px-2 block text-lg font-semibold font-montserrat">
        <option value="">Select a Category</option>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <?php if($errors->has('category_id')): ?>
        <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('category_id')); ?></span>
      <?php endif; ?>
    </div>

    <!-- Visibility -->
    <div class="mb-4">
      <label for="visibility" class="block text-lg font-semibold font-montserrat">Visibility</label>
      <select id="visibility" name="visibility" required class="px-2 block text-lg font-semibold font-montserrat">
        <option value="public" <?php echo e(old('visibility') == 'public' ? 'selected' : ''); ?>>Public</option>
        <option value="private" <?php echo e(old('visibility') == 'private' ? 'selected' : ''); ?>>Private</option>
      </select>
      <?php if($errors->has('visibility')): ?>
        <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('visibility')); ?></span>
      <?php endif; ?>
    </div>

    <!-- Capacity -->
    <div class="mb-4">
      <label for="capacity" class="block text-lg font-semibold font-montserrat">Capacity</label>
      <input id="capacity" type="number" name="capacity" value="<?php echo e(old('capacity')); ?>" required class="block w-full rounded-md bg-eerieBlack text-white border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel py-3 px-4 placeholder-charcoal sm:text-base">
      <?php if($errors->has('capacity')): ?>
        <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('capacity')); ?></span>
      <?php endif; ?>
    </div>

    <!-- Submit Button -->
    <div class="mt-6 text-center">
      <button type="submit" class="bg-ultraViolet hover:bg-cambridgeBlue text-white px-6 py-2 rounded-md hover:text-darkSlateGrey border-ultraViolet hover:border-cambridgeBlue font-montserrat font-bold transition-all duration-200">
        Create Event
      </button>
    </div>
  </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/event/create.blade.php ENDPATH**/ ?>