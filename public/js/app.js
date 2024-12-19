const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');

Pusher.logToConsole = true;

// Initialize Pusher
const pusher = new Pusher(pusherAppKey, {
  cluster: pusherCluster,
  encrypted: true,
});

const channel = pusher.subscribe(`user.${userId}`)

// Bind to the notification event
channel.bind('notification', function (data) {
  // Create notification element
  const notification = document.createElement('div');
  notification.className = 'notification-popup'; // For animation
  // notification.innerHTML = `
  //     <div class="px-4 py-3 bg-green-500 text-white font-montserrat rounded-lg shadow-lg mb-4">
  //       <h2>${data.title}</h2>    
  //       <p>${data.message}</p>
  //     </div>
  // `;

  notification.innerHTML = `
  <div id="popup" class="fixed bottom-0 right-0 z-50 p-4 mb-4 mr-4 transition-transform duration-500 ease-in-out transform translate-x-0 bg-white border-2 border-blue-500 rounded-lg shadow-lg opacity-100 text-darkSlateGrey">
    <div class="flex items-start justify-between">
        <div class="pr-4">
            <div class="grid items-center grid-cols-8 gap-4">
                <div class="flex justify-center col-span-1">
                    <i class="text-3xl text-blue-500 fas fa-bell"></i>
                </div>
                <div class="col-span-7">
                    <h3 class="text-2xl font-semibold font-montserrat">${data.title}</h3>
                </div>
            </div>
            <p class="mt-1 text-md">${data.message}</p>
        </div>
    </div>
  </div>`;

  // Append to container
  const container = document.getElementById('notification-container');
  container.appendChild(notification);

  // Remove notification after 5 seconds
  setTimeout(() => {
      notification.remove();
  }, 5000);
});
  