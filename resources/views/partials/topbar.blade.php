<!-- Header Section -->
<header class="relative flex flex-wrap items-center justify-between p-6">
    <!-- Logo -->
    <h1 class="font-bold">
        <a href="{{ url(path: '/home') }}" 
           class="text-5xl font-montserrat hover:text-darkSlateGrey 
                  {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}">
            Lend-A-Hand
        </a>
    </h1>

    @include('partials.search_bar')
    
    <!-- Navigation Icons -->
    <div class="flex items-center space-x-6">
        @if (Auth::guard('web')->check() )
            @if (Auth::user()->isblocked)
                <a href="{{ url('/unblock-request') }}" 
                title="Unblock Request" 
                class="{{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }} hover:text-darkSlateGrey">
                    <i class="text-4xl fa-solid fa-lock-open"></i>
                </a>
                @else
                <a href="{{ url('/event/create') }}" 
                title="Create Event" 
                class="{{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }} hover:text-darkSlateGrey">
                    <i class="text-4xl fa-solid fa-plus"></i>
                </a>
            @endif


            <div class="relative">
                <!-- Bell Icon with Notification Count -->
                <a id="notifications-button" onclick="toggleNotificationsDropdown()" class="relative cursor-pointer">
                    <i class="text-4xl hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }} fa-solid fa-bell"></i>
                    <div id="noti-number" class="absolute top-0 right-0 flex items-center justify-center hidden w-5 h-5 text-xs font-bold text-white translate-x-2 translate-y-1 bg-red-500 rounded-full">
                        0
                    </div>
                </a>

                <!-- Notifications Dropdown -->
                <div id="notifications-dropdown" class="absolute right-0 z-10 hidden p-6 mt-2 bg-white border border-gray-300 rounded-lg shadow-lg w-80">
                    <p id="no-notifications" class="text-xl text-center text-gray-500">There are no notifications.</p>
                    <div id="notifications-list"></div>
                </div>
            </div>
            
            <!-- Profile Icon -->
            <div class="relative">
                <a id="profile-button" onclick="toggleProfileDropdown()" class="cursor-pointer">
                    <img src="{{ asset('images/profile-pictures/' . (Auth::user()->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" 
                        alt="User Profile Picture" 
                        class="object-cover w-16 h-16 border-2 rounded-full hover:border-darkSlateGrey 
                        {{ Auth::guard('admin')->check() ? 'border-ultraViolet' : 'border-wisteria' }}">
                </a>

                <!-- Profile Dropdown -->
                <div id="profile-dropdown" class="absolute right-0 z-10 hidden mt-2 bg-white border border-gray-300 rounded-lg shadow-lg w-auto min-w-[200px]">
                    <!-- Profile Info Card -->
                    <a href="{{ route('user.profile', ['id' => Auth::user()->id]) }}" 
                    class="block p-6 hover:bg-gray-100">
                        <div class="flex items-center gap-6">
                            <!-- Profile Image -->
                            <img src="{{ asset('images/profile-pictures/' . (Auth::user()->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" 
                                alt="User Profile Picture" 
                                class="object-cover w-16 h-16 border-2 rounded-full 
                                {{ Auth::guard('admin')->check() ? 'border-ultraViolet' : 'border-wisteria' }}">
                            
                            <!-- User Info -->
                            <div>
                                <p class="text-3xl font-semibold text-gray-800 whitespace-nowrap">{{ Auth::user()->name }}</p>
                                <p class="text-2xl text-gray-500 whitespace-nowrap">{{ Auth::user()->username }}</p>
                            </div>
                        </div>
                    </a>

                    <!-- Logout Option -->
                    <a href="{{ url('/logout') }}" 
                    class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
                        <i class="text-xl fa-solid fa-right-from-bracket"></i>
                        <span class="font-medium">Sign out</span>
                    </a>
                </div>

            </div>
        @endif

        @if (Auth::guard('admin')->check())
            <a href="{{ url('/admin/user-management') }}" 
               class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}" 
               title="Users">
                <i class="text-4xl fa-solid fa-users-gear"></i>
            </a>
            <a href="{{ url('/admin/create') }}" 
               class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}" 
               title="Admins">
                <i class="text-4xl fa-solid fa-user-tie"></i>
            </a>
            <a href="{{ url('/logout') }}" 
               title="Logout" 
               class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}">
                <i class="text-4xl fa-solid fa-right-from-bracket"></i>
            </a>
        @endif

        @if (Auth::guest() && !Request::is('login') && (!Auth::guard('admin')->check()))
            <!-- Login Icon -->
            <a href="{{ url('/login') }}" 
               title="Login" 
               class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}">
                <i class="text-4xl fa-solid fa-right-to-bracket"></i>
            </a>
        @endif
    </div>
</header>


<script>
    //Notifications 

    function toggleNotificationsDropdown() {
        const notificationsDropdown = document.getElementById('notifications-dropdown');

        notificationsDropdown.classList.toggle('hidden'); 

        if (!notificationsDropdown.classList.contains('hidden')) {
            fetchNotifications();
        }
    }

    function fetchNotifications() {
        fetch('/api/notifications')
            .then(response => response.json())
            .then(data => {
                console.log('Notifications:', data);
                displayNotificationResults(data);
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
            });
    }

    const csrfToken = "{{ csrf_token() }}";

    function displayNotificationResults(notifications) {
        const notificationsButton = document.getElementById('notifications-button');
        const notificationsDropdown = document.getElementById('notifications-dropdown');
        const notificationsList = document.getElementById('notifications-list');
        const noNotificationsText = document.getElementById('no-notifications');
        const notiNumber = document.getElementById('noti-number');
        
        notificationsList.innerHTML = '';

        if (notifications.length === 0) {
            noNotificationsText.classList.remove('hidden');
        } else {
            noNotificationsText.classList.add('hidden');
            notifications.forEach(notification => {
                const notificationItem = document.createElement('div');
                notificationItem.classList.add('bg-white', 'p-4', 'rounded-lg', 'shadow-md', 'mb-4', 'border', 'border-gray-200');

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
                        <a class="font-semibold text-indigo-600 text-md" href="/events/${notification.data.event_id}">You have received an invite!</a>
                        <p class="text-sm text-gray-600">
                            <a class="text-blue-600 hover:underline" href="/user/${notification.data.invite_by_id}">
                                ${notification.data.invitee_name ? notification.data.invitee_name : ''}
                            </a> 
                            has invited you to 
                            <a class="text-blue-600 hover:underline" href="/events/${notification.data.event_id}">
                                ${notification.data.event_name ? notification.data.event_name + ' event.' : 'an event'}
                            </a><br>
                            ${notification.data.description ? notification.data.description : ''}
                        </p>
                        <!-- Container for the buttons, using flex to position them side by side -->
                        <div class="flex space-x-4">
                            <!-- Accept Button -->
                            <form action="/invites/${notification.data.invite_id}/accept" method="POST">
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
                            <form action="/invites/${notification.data.invite_id}/decline" method="POST">
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
                        <p class="font-semibold text-indigo-600 text-md">Your invite was accepted!</p>
                        <p class="text-sm text-gray-600">
                            <a class="text-blue-600 hover:underline" href="/user/${notification.data.invite_to_id}">
                                ${notification.data.invited_name ? notification.data.invited_name : 'Someone'}
                            </a>
                            accepted your invite for
                            <a class="text-blue-600 hover:underline" href="/events/${notification.data.event_id}">
                                ${notification.data.event_name ? notification.data.event_name : 'an event'}
                            </a>
                        </p>
                    `;
                } else if (notification.type === 'join_request_notification') {
                    notificationContent += `
                        <a class="font-semibold text-indigo-600 text-md" href="/events/${notification.data.event_id}">${notification.type ? notification.type : 'No type available'}</a>
                        <p class="text-sm text-gray-600">
                            <a class="text-blue-600 hover:underline" href="/user/${notification.data.join_request_by_id}">
                                ${notification.data.join_request_by_name ? notification.data.join_request_by_name : ''}
                            </a> 
                            has requested to join the
                            <a class="text-blue-600 hover:underline" href="/events/${notification.data.event_id}">
                                ${notification.data.event_name ? notification.data.event_name : 'an '}
                            </a> 
                            event.
                        </p>
                        <!-- Container for the buttons, using flex to position them side by side -->
                        <div class="flex space-x-4">
                            <!-- Accept Button -->
                            <form action="/requests/${notification.data.join_request_id}/accept" method="POST">
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
                            <form action="/requests/${notification.data.join_request_id}/decline" method="POST">
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
                        <p class="font-semibold text-indigo-600 text-md">Join request was accepted!</p>
                        <p class="text-sm text-gray-600">
                            <a class="text-blue-600 hover:underline" href="/user/${notification.data.event_creator_id}">
                                ${notification.data.event_creator_name ? notification.data.event_creator_name : 'The event creator'}
                            </a>
                            accepted your request to join
                            <a class="text-blue-600 hover:underline" href="/events/${notification.data.event_id}">
                                ${notification.data.event_name ? notification.data.event_name : 'an event'}
                            </a>.
                        </p>
                    `;
                } else if (notification.type === 'event_update_notification') {
                    notificationContent += `
                        <p class="font-semibold text-indigo-600 text-md">'Event was updated'}</p>
                        <p class="text-sm text-gray-600">Event ${notification.data.event_name} has been updated.</p>
                    `;
                } else if (notification.type === 'comment_notification') {
                    notificationContent += `
                        <a class="font-semibold text-indigo-600 text-md" href="/events/${notification.data.event_id}">New Comment!</a>
                        <a class="text-sm text-gray-600" href="/events/${notification.data.event_id}">${notification.data.user_name} has post a comment in ${notification.data.event_name} event.</a>
                    `;
                } else if (notification.type === 'poll_reminder') {
                    const eventEndTime = new Date(notification.data.end_time);

                    // Format the date and time
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
                        <p class="font-semibold text-indigo-600 text-md">${notification.type ? notification.type : 'No type available'}</p>
                        <a class="text-sm text-gray-600" href="/events/${notification.data.event_id}">
                            You have up until ${formattedDate} ${formattedTime} to answer to the ${notification.data.name} poll.
                        </a>
                    `;
                } else if (notification.type === 'event_cancelation') {
                    notificationContent += `
                        <p class="font-semibold text-indigo-600 text-md">${notification.type ? notification.type : 'No type available'}</p>
                        <p class="text-sm text-gray-600">The event with ID: ${notification.event_id} has been canceled.</p>
                    `;
                } else if (notification.type === 'event_reminder') {
                    notificationContent += `
                        <p class="font-semibold text-indigo-600 text-md">${notification.type ? notification.type : 'No type available'}</p>
                        <p class="text-sm text-gray-600">Reminder: Event with ID: ${notification.event_id} is coming soon!</p>
                    `;
                } else if (notification.type === 'admin_action') {
                    notificationContent += `
                        <p class="font-semibold text-indigo-600 text-md">${notification.type ? notification.type : 'No type available'}</p>
                        <p class="text-sm text-gray-600">An administrative action has been performed.</p>
                    `;
                } else if (notification.type === 'unblock_notification') {
                    notificationContent += `
                        <p class="font-semibold text-indigo-600 text-md">${notification.type ? notification.type : 'No type available'}</p>
                        <p class="text-sm text-gray-600">You have been unblocked.</p>
                    `;
                } else {
                    notificationContent += `
                        <p class="font-semibold text-indigo-600 text-md">${notification.type ? notification.type : 'No type available'}</p>
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
        window.dismissNotification = function (notificationId) {
            fetch('/notifications/dismiss', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ notification_id: notificationId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetchNotifications();
                }
            })
            .catch(error => console.error('Error dismissing notification:', error));
        };

        window.dismissAll = function () {
            fetch('/notifications/dismiss-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetchNotifications();
                }
            })
            .catch(error => console.error('Error dismissing all notifications:', error));
        };
    });

    function updateNotificationCount() {
        const notiNumber = document.getElementById('noti-number');
        
        fetch('/notifications/count')
            .then(response => response.json())
            .then(data => {
                if (data.unreadCount !== undefined) {
                    notiNumber.textContent = data.unreadCount;
                    notiNumber.classList.toggle('hidden', data.unreadCount === 0);
                }
            })
            .catch(error => console.error('Error fetching notification count:', error));
    }

    window.userIsAuthenticated = @json(Auth::check());
    // Update the count every 30 seconds, but only if the user is authenticated
    function startNotificationUpdates() {
        if (window.userIsAuthenticated) {
            // Update the notification count every 30 seconds
            setInterval(updateNotificationCount, 30000);

            // Update the notification count when the page loads
            document.addEventListener('DOMContentLoaded', () => {
                updateNotificationCount();
            });
        }
    }

    // Call this function when the page is ready or at the appropriate time
    startNotificationUpdates();

    // Profile dropdown

    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profile-dropdown');
        dropdown.classList.toggle('hidden');
    }

    function toggleNotificationsDropdown() {
        const dropdown = document.getElementById('notifications-dropdown');
        dropdown.classList.toggle('hidden');
    }

    // Optional: Close dropdowns when clicking outside
    document.addEventListener('click', (event) => {
        const profileDropdown = document.getElementById('profile-dropdown');
        const notificationsDropdown = document.getElementById('notifications-dropdown');

        if (!event.target.closest('#profile-button') && !event.target.closest('#profile-dropdown')) {
            profileDropdown.classList.add('hidden');
        }
        if (!event.target.closest('#notifications-button') && !event.target.closest('#notifications-dropdown')) {
            notificationsDropdown.classList.add('hidden');
        }
    });
</script>