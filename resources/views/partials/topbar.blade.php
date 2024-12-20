<!-- Skip to Content Link -->
<a href="#main" 
   class="absolute left-[-999px] top-[-999px] focus:left-0 focus:top-0 focus:z-50 focus:bg-ultraViolet focus:text-white focus:px-4 focus:py-2 focus:rounded-md">
   Skip to Content
</a>

<!-- Header Section -->
<header class="relative flex flex-wrap items-center justify-between p-6">
    <!-- Logo -->
    <h1 class="font-bold">
        <a href="{{ url(path: '/home') }}" 
           class="text-5xl font-montserrat hover:text-darkSlateGrey 
                  {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}" aria-label="Lend-A-Hand Home Page">
            Lend-A-Hand
        </a>
    </h1>

    @include('partials.search_bar')
    
    <!-- Navigation Icons -->
    <div class="flex items-center space-x-6">
        @if (Auth::guard('web')->check() )
            @can('sendUnblockRequest', auth()->user())
                <a href="{{ url('/unblock-request') }}" 
                title="Unblock Request" 
                aria-label="Request Unblock"
                class="{{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }} hover:text-darkSlateGrey">
                    <i class="text-4xl fa-solid fa-lock-open"></i>
                </a>
            @endcan
            @can('create', auth()->user())
                <a href="{{ url('/event/create') }}" 
                title="Create Event" 
                aria-label="Create Event"
                class="{{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }} hover:text-darkSlateGrey">
                    <i class="text-4xl fa-solid fa-plus"></i>
                </a>
            @endcan
            


            <div class="relative">
                <!-- Bell Icon with Notification Count -->
                <a id="notifications-button" onclick="toggleNotificationsDropdown()" class="relative cursor-pointer">
                    <i class="text-4xl hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }} fa-solid fa-bell"></i>
                    
                    <!-- Notification Count Circle -->
                    <div id="noti-indicator" class="absolute top-0 right-0 flex items-center justify-center hidden w-5 h-5 font-bold text-white translate-x-2 translate-y-1 bg-red-500 rounded-full text-[10px]">
                    </div>
                </a>

                <!-- Notifications Dropdown -->
                <div id="notifications-dropdown" class="absolute right-0 z-10 font-montserrat hidden p-6 mt-2 bg-white border border-gray-300 rounded-lg shadow-lg w-[32rem] max-h-[750px] overflow-y-auto">
                    <div class="flex items-center justify-between mb-4">
                        <p id="no-notifications" class="text-xl text-center text-gray-500">There are no notifications.</p>
                        <!-- Dismiss All Button -->
                        <button id="dismiss-all-btn" onclick="dismissAllNotifications()" class="hidden font-bold border-transparent font-montserrat focus:text-ultraViolet focus:border-transparent focus:bg-white hover:border-transparent text-ultraViolet hover:bg-white hover:text-wisteria" aria-label="Dismiss all notifications">
                            Dismiss all
                        </button>
                    </div>
                    <div id="notifications-list"></div>
                </div>
                
            </div>
            
            <!-- Profile Icon -->
            <div class="relative">
                <a id="profile-button" onclick="toggleProfileDropdown()" class="cursor-pointer">
                    <img src="{{ asset('images/profile-pictures/' . (Auth::user()->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" 
                        alt="{{ Auth::user()->name ? Auth::user()->name . ' profile picture' : 'Default profile picture' }}" 
                        class="object-cover w-16 h-16 border-2 rounded-full hover:border-darkSlateGrey 
                        {{ Auth::guard('admin')->check() ? 'border-ultraViolet' : 'border-wisteria' }}">
                </a>

                <!-- Profile Dropdown -->
                <div id="profile-dropdown" 
                    class="absolute right-0 z-10 hidden mt-2 bg-white border border-gray-300 rounded-lg shadow-lg w-auto max-w-[90vw]" aria-label="User Profile">
                    <!-- Profile Info Card -->
                    <a href="{{ route('user.profile', ['username' => Auth::user()->username]) }}" 
                    class="block p-6 hover:bg-gray-100" aria-label="View Profile">
                        <div class="flex items-center gap-6">
                            <!-- Profile Image -->
                            <img src="{{ asset('images/profile-pictures/' . (Auth::user()->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" 
                                alt="{{ Auth::user()->name ? Auth::user()->name . ' profile picture' : 'Default profile picture' }}" 
                                class="object-cover w-16 h-16 border-2 rounded-full 
                                        {{ Auth::guard('admin')->check() ? 'border-ultraViolet' : 'border-wisteria' }}">
                            
                            <!-- User Info -->
                            <div class="min-w-0">
                                <p class="mr-16 font-semibold text-gray-800 font-montserrat whitespace-nowrap text-dynamic-xl">
                                    {{ Auth::user()->name }}
                                </p>
                                <p class="mr-16 text-gray-500 font-montserrat whitespace-nowrap text-dynamic-lg">
                                    {{ Auth::user()->username }}
                                </p>
                            </div>
                        </div>
                    </a>

                    <!-- Logout Option -->
                    <a href="{{ url('/logout') }}" 
                    class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100" aria-label="Sign out">
                        <i class="text-xl fa-solid fa-right-from-bracket"></i>
                        <span class="font-medium font-montserrat">Sign out</span>
                    </a>
                </div>
            </div>
        @endif

        @if (Auth::guard('admin')->check())
        
            <a href="{{ route('admin.users.create') }}" 
               class="hover:text-darkSlateGrey text-ultraViolet" 
               title="Create User"
               aria-label="Create User">
                <i class="text-4xl fa-solid fa-user-plus"></i>
            </a>
            <a href="{{ url('/admin/user-management') }}" 
               class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}" 
               title="Users"
               aria-label="User Management">
                <i class="text-4xl fa-solid fa-users-gear"></i>
            </a>
            <a href="{{ url('/admin/create') }}" 
               class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}" 
               title="Admins"
               aria-label="Create Admin">
                <i class="text-4xl fa-solid fa-user-tie"></i>
            </a>
            <a href="{{ url('/admin/unblock-requests') }}" 
                class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}" 
                title="Unblock Requests"
                aria-label="Unblock Requests">
                    <i class="text-4xl fa-solid fa-lock"></i>
            </a>
            <a href="{{ url('/logout') }}" 
               title="Logout" aria-label="Logout"
               class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}">
                <i class="text-4xl fa-solid fa-right-from-bracket"></i>
            </a>
        @endif

        @if (Auth::guest() && !Request::is('login') && (!Auth::guard('admin')->check()))
            <!-- Login Icon -->
            <a href="{{ url('/login') }}" 
               title="Login" aria-label="Login"
               class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}">
                <i class="text-4xl fa-solid fa-right-to-bracket"></i>
            </a>
        @endif
    </div>
</header>


<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    function toggleNotificationsDropdown() {
        const notificationsDropdown = document.getElementById('notifications-dropdown');
        notificationsDropdown.classList.toggle('hidden');

        if (!notificationsDropdown.classList.contains('hidden')) {
            fetchNotifications();
        }
    }

    function fetchNotifications(display = true) {
        fetch('/api/notifications', {
            headers: { 'Accept': 'application/json' },
        })
        .then(response => response.json())
        .then(data => {
            if (display) displayNotificationResults(data);
            updateNotificationIndicator(data);
        })
        .catch(error => console.error('Error fetching notifications:', error));
    }

    function updateNotificationIndicator(notifications) {
        const notiIndicator = document.getElementById('noti-indicator');

        if (notifications && notifications.length > 0) {
            notiIndicator.textContent = notifications.length;
            notiIndicator.classList.remove('hidden');
        } else {
            notiIndicator.classList.add('hidden');
        }
    }

    function displayNotificationResults(notifications) {
        const notificationsList = document.getElementById('notifications-list');
        const noNotificationsText = document.getElementById('no-notifications');
        const dismissAllBtn = document.getElementById('dismiss-all-btn');

        notificationsList.innerHTML = '';

        if (!notifications || notifications.length === 0) {
            noNotificationsText.classList.remove('hidden');
            dismissAllBtn.classList.add('hidden');
        } else {
            noNotificationsText.classList.add('hidden');
            dismissAllBtn.classList.remove('hidden');

            notifications.forEach(notification => {
                const notificationItem = document.createElement('div');
                notificationItem.classList.add('bg-white', 'p-4', 'rounded-lg', 'shadow-md', 'mb-4', 'border', 'border-gray-200');

                notificationItem.setAttribute('data-notification-id', notification.id);

                const formattedDate = new Date(notification.date).toLocaleString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                }).replace(',', '') || 'Not available';

                let notificationContent = `
                    <div class="notification-card">
                        <p class="text-sm font-semibold text-gray-600">${formattedDate}</p>
                `;
                if (notification.type === 'invite_notification') {
                    notificationContent += `
                        <a class="font-semibold text-ultraViolet hover:text-wisteria text-md" href="/events/${notification.data.event_id}">You have received an invite!</a>
                        <p class="text-sm text-gray-600">
                            <a class="text-blue-600 hover:underline" href="/user/${notification.data.sender_username}">
                                ${notification.data.sender_name ?? 'Someone'}
                            </a> 
                            has invited you to 
                            <a class="text-blue-600 hover:underline" href="/events/${notification.data.event_id}">
                                ${notification.data.event_name ?? 'an '}
                            </a>
                            event.
                        </p>
                        <!-- Container for the buttons, using flex to position them side by side -->
                        <div class="flex space-x-4">
                            <!-- Accept Button -->
                            <form action="/invites/${notification.data.id}/accept" method="POST">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="notification_id" value="${notification.id}">
                                <button 
                                    type="submit" 
                                    class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-[#8D9D8D] hover:text-gray-100 border-[#A3C1AD] hover:border-[#8D9D8D]"
                                >
                                    Accept
                                </button>
                            </form>

                            <!-- Decline Button -->
                            <form action="/invites/${notification.data.id}/decline" method="POST">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="notification_id" value="${notification.id}">
                                <button 
                                    type="submit" 
                                    class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-burgundy hover:bg-[#6B0010] hover:text-gray-100 border-[#800020] hover:border-[#6B0010]"
                                >
                                    Decline
                                </button>
                            </form>
                        </div>
                    `;
                } else if (notification.type === 'accepted_invite_notification') {
                    notificationContent += `
                        <a class="font-semibold text-ultraViolet hover:text-wisteria text-md" href="/events/${notification.data.event_id}">Your invite was accepted!</a>
                        <p class="text-sm text-gray-600">
                            <a class="text-blue-600 hover:underline" href="/user/${notification.data.receiver_username}">
                                ${notification.data.receiver_name ?? 'Someone'}
                            </a>
                            accepted your invite for
                            <a class="text-blue-600 hover:underline" href="/events/${notification.data.event_id}">
                                ${notification.data.event_name ?? 'an event'}
                            </a>
                        </p>
                    `;
                } else if (notification.type === 'join_request_notification') {
                    notificationContent += `
                        <a class="font-semibold text-ultraViolet hover:text-wisteria text-md" href="/events/${notification.data.event_id}">Join Request</a>
                        <p class="text-sm text-gray-600">
                            <a class="text-blue-600 hover:underline" href="/user/${notification.data.join_request_by_username}">
                                ${notification.data.join_request_by_name ?? ''}
                            </a> 
                            has requested to join the
                            <a class="text-blue-600 hover:underline" href="/events/${notification.data.event_id}">
                                ${notification.data.event_name ?? 'an '}
                            </a> 
                            event.
                        </p>
                        <!-- Container for the buttons, using flex to position them side by side -->
                        <div class="flex space-x-4">
                            <!-- Accept Button -->
                            <form action="/requests/${notification.data.id}/accept" method="POST">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="notification_id" value="${notification.id}">
                                <button 
                                    type="submit" 
                                    class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-ultraViolet hover:bg-[#8D9D8D] hover:text-gray-100 border-[#A3C1AD] hover:border-[#8D9D8D]"
                                >
                                    Accept
                                </button>
                            </form>

                            <!-- Decline Button -->
                            <form action="/requests/${notification.data.id}/decline" method="POST">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="notification_id" value="${notification.id}">
                                <button type="submit" class="px-6 py-2 font-bold text-white transition-all duration-200 rounded-md font-montserrat bg-burgundy hover:bg-[#6B0010] hover:text-gray-100 border-[#800020] hover:border-[#6B0010]">
                                    Decline
                                </button>
                            </form>
                        </div>
                    `;
                } else if (notification.type === 'accepted_join_request_notification') {
                    notificationContent += `
                        <p class="font-semibold text-ultraViolet hover:text-wisteria text-md">Join request was accepted!</p>
                        <p class="text-sm text-gray-600">
                            <a class="text-blue-600 hover:underline" href="/user/${notification.data.event_creator_username}">
                                ${notification.data.event_creator_name ?? 'The event creator'}
                            </a>
                            accepted your request to join
                            <a class="text-blue-600 hover:underline" href="/events/${notification.data.event_id}">
                                ${notification.data.event_name ?? 'an event'}
                            </a>.
                        </p>
                    `;
                } else if (notification.type === 'event_update_notification') {
                    notificationContent += `
                        <a class="font-semibold text-ultraViolet hover:text-wisteria text-md" href="/events/${notification.data.id}"><p class="font-semibold text-ultraViolet hover:text-wisteria text-md">Event was updated</p></a>
                        <p class="text-sm text-gray-600">
                            <a class="text-blue-600 hover:underline" href="/events/${notification.data.id}">
                                ${notification.data.name ?? 'an event youre a part of'}
                            </a>
                             has been updated.
                        </p>
                    `;
                } else if (notification.type === 'comment_notification') {
                    notificationContent += `
                        <a class="font-semibold text-ultraViolet hover:text-wisteria text-md" href="/events/${notification.data.event_id}">New Comment!</a><br>
                        <p class="text-sm text-gray-600">
                            <a class="text-blue-600 hover:underline" href="/user/${notification.data.commenter_username}">
                                ${notification.data.commenter_name ?? 'Someone'}
                            </a>
                            commented in
                            <a class="text-blue-600 hover:underline" href="/events/${notification.data.event_id}">
                                ${notification.data.event_name ?? "an event you're a part of"}
                            </a>
                        </p>
                    `;
                } else if (notification.type === 'poll_reminder') {
                    const eventEndTime = new Date(notification.data.end_time);

                    const formattedDate = eventEndTime.toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                    });
                    const formattedTime = eventEndTime.toLocaleTimeString('en-GB', {
                        hour: '2-digit',
                        minute: '2-digit',
                    });

                    notificationContent += `
                        <p class="font-semibold text-ultraViolet hover:text-wisteria text-md">${notification.type ? notification.type : 'No type available'}</p>
                        <a class="text-sm text-gray-600" href="/events/${notification.data.event_id}">
                            You have up until ${formattedDate} ${formattedTime} to answer to the ${notification.data.name} poll.
                        </a>
                    `;
                } else if (notification.type === 'event_cancelation') {
                    notificationContent += `
                        <p class="font-semibold text-ultraViolet hover:text-wisteria text-md">Event was canceled</p>
                        <a class="text-blue-600 hover:underline" href="/events/${notification.data.id}">
                            ${notification.data.name ?? 'An event youre a part of'}
                        </a>
                        <p class="text-sm text-gray-600"> has been canceled.</p>
                    `;
                } else if (notification.type === 'event_reminder') {
                    notificationContent += `
                        <p class="font-semibold text-ultraViolet hover:text-wisteria text-md">${notification.type ? notification.type : 'No type available'}</p>
                        <p class="text-sm text-gray-600">Reminder: Event with ID: ${notification.event_id} is coming soon!</p>
                    `;
                } else if (notification.type === 'admin_action') {
                    notificationContent += `
                        <p class="font-semibold text-ultraViolet hover:text-wisteria text-md">${notification.type ? notification.type : 'No type available'}</p>
                        <p class="text-sm text-gray-600">An administrative action has been performed.</p>
                    `;
                } else if (notification.type === 'unblock_notification') {
                    notificationContent += `
                        <p class="font-semibold text-ultraViolet hover:text-wisteria text-md">${notification.type ? notification.type : 'No type available'}</p>
                        <p class="text-sm text-gray-600">You have been unblocked.</p>
                    `;
                } else {
                    notificationContent += `
                        <p class="font-semibold text-ultraViolet hover:text-wisteria text-md">${notification.type ? notification.type : 'No type available'}</p>
                        <p class="text-sm text-gray-600">No specific details available for this notification type.</p>
                    `;
                }

                notificationContent += `</div>`;

                notificationItem.innerHTML = notificationContent;

                notificationsList.appendChild(notificationItem);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchNotifications();

        const dismissAllBtn = document.getElementById('dismiss-all-btn');
        if (dismissAllBtn) {
            dismissAllBtn.addEventListener('click', () => {
                fetch('/notifications/dismiss-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            fetchNotifications();
                            alert("All notifications were dismissed successfully!");
                        } else {
                            alert("Error dismissing notifications.");
                        }
                    })
                    .catch(error => {
                        console.error('Error dismissing notifications:', error);
                        alert("Error dismissing notifications.");
                    });
            });
        }
    });

    document.addEventListener('click', (event) => {
        const notificationsDropdown = document.getElementById('notifications-dropdown');

        if (!event.target.closest('#notifications-button') && !event.target.closest('#notifications-dropdown')) {
            notificationsDropdown.classList.add('hidden');
        }
    });


    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profile-dropdown');
        dropdown.classList.toggle('hidden');
    }

    // Reload notifications every 5 seconds
    setInterval(fetchNotifications, 5000);

</script>