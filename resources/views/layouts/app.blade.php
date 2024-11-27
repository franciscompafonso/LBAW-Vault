<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('Title')</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">

        <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
        @vite('resources/css/app.css')
    </head>
    <body class="flex flex-col min-h-screen bg-white">
        <!-- Header Section -->
        <header class="flex justify-between items-center p-4 {{ Auth::guard('admin')->check() ? 'bg-ultraViolet' : 'bg-wisteria' }} shadow-lg relative">
            <!-- Logo/Title -->
            <h1 class="text-3xl font-bold text-whiteSmoke">
                <a href="{{ url(path: '/home') }}" class="text-5xl hover:text-darkSlateGrey font-montserrat">Lend-A-Hand</a>
            </h1>

            <!-- Navigation Icons -->
            <div class="flex items-center space-x-6">
                <!-- Search Icon -->
                @if (!Request::is('login') && !Request::is('register'))
                    <a href="javascript:void(0)" onclick="toggleSearchBar()" class="text-whiteSmoke hover:text-darkSlateGrey" title="Events">
                        <i class="text-3xl fa-solid fa-magnifying-glass"></i>
                    </a>
                @endif

                @if (Auth::guard('admin')->check())
                <a href="{{ url('/admin/user-management') }}" class="text-whiteSmoke hover:text-darkSlateGrey" title="Users">
                    <i class="text-3xl fa-solid fa-users-gear"></i>
                </a>
                <a href="{{ url('/admin/create') }}" class="text-whiteSmoke hover:text-darkSlateGrey" title="Admins">
                    <i class="text-3xl fa-solid fa-user-tie"></i>
                </a>
                @endif

                <!-- Create Event -->
                @if (Auth::guard('web')->check())
                <a href="{{ url('/event/create') }}" title="Create Event" class="text-whiteSmoke hover:text-darkSlateGrey">
                    <i class="text-3xl fa-solid fa-plus"></i>
                </a>
                @endif

                <!-- Info Icon -->
                <a href="{{ url('/about') }}" class="text-whiteSmoke hover:text-darkSlateGrey" title="Info">
                    <i class="text-3xl fa-solid fa-circle-info"></i>
                </a>

                <!-- Help Icon -->
                <a href="{{ url('/faqs') }}" class="text-whiteSmoke hover:text-darkSlateGrey" title="FAQs">
                    <i class="text-3xl fa-solid fa-question"></i>
                </a>

                @if (Auth::guest() && !Request::is('login') && (!Auth::guard('admin')->check()))
                    <!-- Login Icon -->
                    <a href="{{ url('/login') }}" title="Login" class="text-whiteSmoke hover:text-darkSlateGrey">
                        <i class="text-3xl fa-solid fa-right-to-bracket"></i>
                    </a>
                @endif

                @if (Auth::guard('web')->check())
                    <div class="relative">
                        <!-- Bell Icon with Notification Count -->
                        <a id="notifications-button" onclick="toggleNotificationsDropdown()" class="relative cursor-pointer">
                            <i class="text-4xl text-whiteSmoke hover:text-darkSlateGrey fa-solid fa-bell"></i>
                            <div id="noti-number" class="absolute top-0 right-0 flex items-center justify-center hidden w-5 h-5 text-xs font-bold text-white translate-x-2 translate-y-1 bg-red-500 rounded-full">
                                0
                            </div>
                        </a>

                        <!-- Notifications Dropdown -->
                        <div id="notifications-dropdown" class="absolute right-0 z-10 hidden p-4 mt-2 bg-white border border-gray-300 rounded-lg shadow-lg w-100">
                            <p id="no-notifications" class="text-center text-gray-500">There are no notifications.</p>
                            <div id="notifications-list">
                            </div>
                        </div>
                    </div>
                    <!-- Profile Icon for regular user -->
                    <a href="{{ route('user.profile', ['id' => Auth::user()->id]) }}">
                        <img src="{{ asset('images/profile-pictures/' . Auth::user()->profilepicture) }}" 
                             alt="User Profile Picture" 
                             class="object-cover w-16 h-16 border-2 rounded-full border-whiteSmoke hover:border-darkSlateGrey">
                    </a>
                    <!-- Logout Icon for regular user -->
                @endif

                @if (Auth::guard('web')->check() || Auth::guard('admin')->check() )
                <a href="{{ url('/logout') }}" title="Logout" class="text-whiteSmoke hover:text-darkSlateGrey">
                        <i class="text-3xl fa-solid fa-right-from-bracket"></i>
                </a>
                @endif
            </div>
        </header>

        <!-- Search Bar & Results -->
        <div id="searchBar" class="absolute top-0 z-10 hidden w-full max-w-4xl p-4 mt-16 transform -translate-x-1/2 bg-white rounded-lg shadow-lg left-1/2">
            <div class="relative w-full">
                <input type="text" id="searchInput" 
                    class="w-full p-4 pl-12 border border-gray-300 rounded-full search-input focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                    placeholder="Search events..." onkeyup="searchEvents()">
            </div>
            
            <div id="searchResults" class="mt-4 overflow-y-auto max-h-80"></div>
        </div>

        <main id="main" 
            class="flex items-center justify-center flex-1 p-8 overflow-auto" 
            style="
                {{ Request::is('faqs') ? 'padding-top: 250px;' : '' }}
                {{ Request::is('events/*/survey/stats') && isset($hasResponses) && $hasResponses ? 'padding-top: 1000px;' : '' }}
            ">
            @yield('content')
        </main>

        <!-- Footer Section -->
        <footer class="{{ Auth::guard('admin')->check() ? 'bg-ultraViolet' : 'bg-wisteria' }} flex items-center justify-center text-center font-bold font-montserrat text-surface/75 h-16">
            <p class="text-white">&copy; Lend-A-Hand 2024</p>
        </footer>

        <script>
            const isAdmin = @json(Auth::guard('admin')->check());
            // Search
            function toggleSearchBar() {
                const searchBar = document.getElementById('searchBar');
                const searchInput = document.getElementById('searchInput');
                const searchResults = document.getElementById('searchResults');

                searchBar.classList.toggle('hidden');

                if (searchBar.classList.contains('hidden')) {
                    searchInput.value = '';
                    searchResults.innerHTML = '';
                }
            }

            function searchEvents() {
                const query = document.getElementById('searchInput').value;
                console.log("Searching for: " + query); // Add this line to debug the input

                if (query.length >= 3) {
                    fetch(`/search?q=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Search results:', data); // Debug the response
                            displaySearchResults(data);
                        })
                        .catch(error => {
                            console.error('Error fetching search results:', error);
                        });
                } else {
                    document.getElementById('searchResults').innerHTML = ''; // Clear results if less than 3 characters
                }
            }


            function displaySearchResults(events) {
                const resultsContainer = document.getElementById('searchResults');
                resultsContainer.innerHTML = '';

                if (events.length === 0) {
                    resultsContainer.innerHTML = '<p class="text-center text-gray-500">No events found.</p>';
                } else {
                    events.forEach(event => {
                        const eventItem = document.createElement('div');
                        eventItem.classList.add('bg-white', 'p-4', 'rounded-lg', 'shadow-md', 'mb-4', 'border', 'border-gray-200');
                        
                        
                        const formattedDate = event.start_date 
                            ? new Date(event.start_date).toLocaleString() 
                            : 'Not available';

                        const eventUrl = isAdmin ? `/admin/events/${event.id}` : `/events/${event.id}`;

                        eventItem.innerHTML = `
                            <div class="event-card">
                                <h3 class="text-xl font-semibold text-gray-800">
                                    <a href="${eventUrl}" class="font-semibold text-md font-montserrat text-ultraViolet hover:text-cambridgeBlue">${event.name}</a>
                                </h3>
                                <p class="text-sm font-semibold font-montserrat text-darkSlateGrey">${event.description || 'No description available'}</p>
                                <p class="text-sm font-semibold font-montserrat text-darkSlateGrey">${formattedDate}</p>
                            </div>
                        `;

                        resultsContainer.appendChild(eventItem);
                    });
                }
            }

            // Notifications

            function toggleNotificationsDropdown() {
                const notificationsDropdown = document.getElementById('notifications-dropdown');

                notificationsDropdown.classList.toggle('hidden'); 

                if (!notificationsDropdown.classList.contains('hidden')) {
                    fetchNotifications();
                }
            }

            function fetchNotifications() {
                fetch('/notifications')
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
                                <a class="font-semibold text-indigo-600 text-md" href="/events/${notification.data.event_id}">${notification.type ? notification.type : 'No type available'}</a>
                                <p class="text-sm text-gray-600">
                                    You have been invited by 
                                    <a class="text-blue-600 hover:underline" href="/user/${notification.data.invite_by_id}">
                                        ${notification.data.invite_by_name ? notification.data.invite_by_name : ''}
                                    </a> 
                                    to 
                                    <a class="text-blue-600 hover:underline" href="/events/${notification.data.event_id}">
                                        ${notification.data.title ? notification.data.title : 'an event'}
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
                                    <form action="/notifications/dismiss" method="POST">
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
                                <p class="font-semibold text-indigo-600 text-md">${notification.type ? notification.type : 'No type available'}</p>
                                <p class="text-sm text-gray-600">
                                    <a class="text-blue-600 hover:underline" href="/user/${notification.data.invite_to_id}">
                                        ${notification.data.invite_to_name ? notification.data.invite_to_name : 'Someone'}
                                    </a>
                                    accepted your invite for
                                    <a class="text-blue-600 hover:underline" href="/events/${notification.data.event_id}">
                                        ${notification.data.title ? notification.data.title : 'an event'}
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
                                        ${notification.data.event_title ? notification.data.event_title : 'an '}
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
                                    <form action="/notifications/dismiss" method="POST">
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
                        } else if (notification.type === 'accepted_join_request_notification') {
                            notificationContent += `
                                <p class="font-semibold text-indigo-600 text-md">${notification.type ? notification.type : 'No type available'}</p>
                                <p class="text-sm text-gray-600">
                                    <a class="text-blue-600 hover:underline" href="/user/${notification.data.event_creator_id}">
                                        ${notification.data.event_creator_name ? notification.data.event_creator_name : 'The event creator'}
                                    </a>
                                    accepted your request to join
                                    <a class="text-blue-600 hover:underline" href="/events/${notification.data.event_id}">
                                        ${notification.data.event_title ? notification.data.event_title : 'an event'}
                                    </a>.
                                </p>
                            `;
                        } else if (notification.type === 'event_update_notification') {
                            notificationContent += `
                                <p class="font-semibold text-indigo-600 text-md">${notification.type ? notification.type : 'No type available'}</p>
                                <p class="text-sm text-gray-600">Event ${notification.event_id} has been updated.</p>
                            `;
                        } else if (notification.type === 'comment_notification') {
                            notificationContent += `
                                <a class="font-semibold text-indigo-600 text-md" href="/events/${notification.data.event_id}">${notification.type ? notification.type : 'No type available'}</a>
                                <a class="text-sm text-gray-600" href="/events/${notification.data.event_id}">You have a new comment on your post.</a>
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

        </script>
    </body>
</html>
