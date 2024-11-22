<?php $__env->startSection('content'); ?>
<section id="edit-event" class="max-w-4xl mx-auto p-8 bg-whiteSmoke shadow-xl rounded-lg mt-10">
  <div class="flex flex-col justify-center items-center">
    <!-- Page Title -->
    <h2 class="text-4xl font-bold text-eerieBlack mb-10">Edit Event</h2>
  </div>

  <!-- Form Container -->
  <div class="bg-eerieBlack p-8 rounded-lg shadow-lg">
    <form method="POST" action="<?php echo e(route('events.update', $event->id)); ?>" class="space-y-8">
      <?php echo csrf_field(); ?>
      <?php echo method_field('PUT'); ?> <!-- Add this to indicate the update method -->

      <!-- Event Name -->
      <div>
        <label for="name" class="block text-lg font-semibold text-whiteSmoke">Event Name</label>
        <div class="mt-2">
          <input id="name" type="text" name="name" value="<?php echo e(old('name', $event->name)); ?>" required autofocus autocomplete="name" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel focus:border-keppel py-3 px-4 text-white placeholder-charcoal sm:text-base">
          <?php if($errors->has('name')): ?>
            <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('name')); ?></span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-lg font-semibold text-whiteSmoke">Description</label>
        <div class="mt-2">
          <textarea id="description" name="description" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel focus:border-keppel py-3 px-4 text-white placeholder-charcoal sm:text-base"><?php echo e(old('description', $event->description)); ?></textarea>
          <?php if($errors->has('description')): ?>
            <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('description')); ?></span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Start Date and Time -->
      <div>
        <label for="start_date" class="block text-lg font-semibold text-whiteSmoke">Start Date & Time</label>
        <div class="mt-2 relative">
          <input id="start_date" type="datetime-local" name="start_date" value="<?php echo e(old('start_date', $event->start_date)); ?>" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel focus:border-keppel py-3 px-4 text-white placeholder-charcoal sm:text-base">
          <style>
            input[type="datetime-local"]::-webkit-calendar-picker-indicator {
              filter: invert(1) grayscale(1) contrast(2);
            }
          </style>
        </div>
        <?php if($errors->has('start_date')): ?>
          <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('start_date')); ?></span>
        <?php endif; ?>
      </div>

      <!-- End Date and Time -->
      <div>
        <label for="end_date" class="block text-lg font-semibold text-whiteSmoke">End Date & Time</label>
        <div class="mt-2 relative">
          <input id="end_date" type="datetime-local" name="end_date" value="<?php echo e(old('end_date', $event->end_date)); ?>" required class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel focus:border-keppel py-3 px-4 text-white placeholder-charcoal sm:text-base">
          <style>
            input[type="datetime-local"]::-webkit-calendar-picker-indicator {
              filter: invert(1) grayscale(1) contrast(2);
            }
          </style>
        </div>
        <?php if($errors->has('end_date')): ?>
          <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('end_date')); ?></span>
        <?php endif; ?>
      </div>

      <!-- Location Selector -->
      <div>
        <label for="location_id" class="block text-lg font-semibold text-whiteSmoke">Location</label>
        <div class="mt-2">
          <select id="location_id" name="location_id" required class="block w-full rounded-md bg-eerieBlack text-white border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel py-3 px-4 sm:text-base">
            <option value="">Select a Location</option>
            <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($location->id); ?>" <?php echo e(old('location_id', $event->location_id) == $location->id ? 'selected' : ''); ?>><?php echo e($location->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <?php if($errors->has('location_id')): ?>
            <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('location_id')); ?></span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Category Selector -->
      <div>
        <label for="category_id" class="block text-lg font-semibold text-whiteSmoke">Category</label>
        <div class="mt-2">
          <select id="category_id" name="category_id" required class="block w-full rounded-md bg-eerieBlack text-white border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel py-3 px-4 sm:text-base">
            <option value="">Select a Category</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $event->category_id) == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <?php if($errors->has('category_id')): ?>
            <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('category_id')); ?></span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Visibility -->
      <div>
        <label for="visibility" class="block text-lg font-semibold text-whiteSmoke">Visibility</label>
        <div class="mt-2">
          <select id="visibility" name="visibility" required class="block w-full rounded-md bg-eerieBlack text-white border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel py-3 px-4 sm:text-base">
            <option value="public" <?php echo e(old('visibility', $event->visibility) == 'public' ? 'selected' : ''); ?>>Public</option>
            <option value="private" <?php echo e(old('visibility', $event->visibility) == 'private' ? 'selected' : ''); ?>>Private</option>
          </select>
          <?php if($errors->has('visibility')): ?>
            <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('visibility')); ?></span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Capacity -->
      <div>
        <label for="capacity" class="block text-lg font-semibold text-whiteSmoke">Capacity</label>
        <div class="mt-2">
          <input id="capacity" type="number" name="capacity" value="<?php echo e(old('capacity', $event->capacity)); ?>" required class="block w-full rounded-md bg-eerieBlack text-white border-gray-300 shadow-sm focus:ring-2 focus:ring-keppel py-3 px-4 placeholder-charcoal sm:text-base">
          <?php if($errors->has('capacity')): ?>
            <span class="text-sm text-red-600 mt-1"><?php echo e($errors->first('capacity')); ?></span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="pt-4">
        <button type="submit" class="w-full flex justify-center rounded-md bg-charcoal text-white px-8 py-3 rounded-md font-semibold shadow-lg hover:bg-keppel transition-colors duration-200">
          Update Event
        </button>
      </div>
    </form>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/franciscoafonso/Desktop/LBAW/lbaw2414/resources/views/event/edit.blade.php ENDPATH**/ ?>