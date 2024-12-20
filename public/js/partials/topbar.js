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
                        <div class="grid grid-cols-8 items-center  gap-4">   
                            <p class="col-span-7 text-sm items-center font-semibold text-gray-600">${formattedDate}</p>
                            <button 
                                class="col-span-1 flex justify-end items-center h-6 hover:bg-transparent focus:bg-transparent border-transparent focus:border-transparent hover:border-transparent text-gray-500 focus:text-red-500 hover:text-red-500" 
                                onclick="dismissNotification(${notification.id}, event)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
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

    function dismissAllNotifications(event) {
        fetch('/notifications/dismiss-all', {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPopup(data.success, data.message);

                fetchNotifications();
            }
        })
        .catch(error => {
            showPopup(false, 'Something went wrong. Please try again later.');
            console.error(error);
        });
    }
    
    function dismissNotification(notificationId, event) {
        fetch(`/notifications/${notificationId}/dismiss`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPopup(data.success, data.message);

                fetchNotifications();
            }
        })
        .catch(error => {
            showPopup(false, 'Something went wrong. Please try again later.');
            console.error(error);
        });
    }
    

    // Reload notifications every 5 seconds
    //setInterval(fetchNotifications, 5000);
