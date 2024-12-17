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
  notification.innerHTML = `
      <div class="px-4 py-3 bg-green-500 text-white font-montserrat rounded-lg shadow-lg mb-4">
        <h2>${data.title}</h2>    
        <p>${data.message}</p>
      </div>
  `;

  // Append to container
  const container = document.getElementById('notification-container');
  container.appendChild(notification);

  // Remove notification after 5 seconds
  setTimeout(() => {
      notification.remove();
  }, 5000);
});
  