const eventId = window.eventId;
const startDate = window.startEvent;
const endDate = window.endEvent;

function validatePhotos() {
    const photosInput = document.getElementById('photos');
    const fileCount = photosInput.files.length;

    if (fileCount > 15) {
        alert('You can only upload up to 15 photos.');
        photosInput.value = ''; // Clear the file input
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const commentForm = document.getElementById('commentForm');
    if(commentForm) {
        commentForm.reset();
    }
});

document.addEventListener('click', (event) => {
    const menu = document.getElementById('menu');
    const button = document.getElementById('toggleMenu');
    const sharePopup = document.getElementById('share-popup');
    const shareButton = document.getElementById('share-button');

    // Check if the click is outside the popup and the button
    if (!event.target.closest('#share-button') && !event.target.closest('#share-popup')) {
        sharePopup.classList.add('hidden'); // Hide the popup
    }
    // Check if click happened outside of dropdown and button
    if (!menu.contains(event.target) && !button.contains(event.target)) {
        menu.classList.add('hidden');
    }
});

function toggleMenu(event) {
    event.stopPropagation(); // Prevent click from closing the menu
    const menu = document.getElementById('menu');
    menu.classList.toggle('hidden');
}

// Set the input value to the current page URL dynamically
const shareInput = document.getElementById('share-link');
shareInput.value = `Hello! Look at this event I just found! Link: ${window.location.href}`;

// Toggles the popup visibility when the share button is clicked
function togglePopup() {
    const popup = document.getElementById('share-popup');
    popup.classList.toggle('hidden');
}

// Copies the text inside the input field to the clipboard
function copyToClipboard() {
    const link = document.getElementById('share-link');
    link.select();
    link.setSelectionRange(0, 99999);
    document.execCommand('copy');
    alert('Link copied to clipboard!');
}

// -------------------- AJAX RELOAD COMMENTS --------------------
// Handle Comment button click
document.addEventListener('submit', function (event) {
    if (event.target.id === "deleteCommentForm" || event.target.id === "replyCommentForm" || event.target.id === "commentForm" || event.target.id === "dislikeForm" || event.target.id === "likeForm") {
        event.preventDefault(); // Prevent form submission (page reload)

        const form = event.target;
        const formData = new FormData(form);

        // Make AJAX request to submit the comment
        fetch(form.action, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            showPopup(data.success, data.message);

            if (data.success) {
                updateCommentInfo();
            }
        })
        .catch(error => {
            showPopup(false, 'Something went wrong. Please try again later.');
            console.error(error);
        });
    }
});

// This function is called to update the comment list in the page after submitting
function updateCommentInfo() {
    fetch(`/events/${eventId}/comments`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('comment-list').innerHTML = data.commentsHtml;
            
            if (userId) updateCommentForm();
            console.log(data.message);
        })
        .catch(error => console.error('Error updating comments:', error));
}

// -------------------- AJAX RELOAD EVENT PAGE --------------------

// Handle Invite button click
document.addEventListener('submit', function (event) {
    if (event.target.id === "inviteForm") {
        event.preventDefault(); // Prevent form submission (page reload)

        const form = event.target;
        const formData = new FormData(form);

        // Make AJAX request to submit the comment
        fetch(form.action, {
            method: 'POST',
            body: formData,
        })
        .then(async (response) => {
            // Ensure the response is JSON, otherwise log its raw content for debugging
            if (!response.ok) {
                const text = await response.text();
                console.error('Raw response:', text); // Log the raw content
                throw new Error('Server returned a non-JSON response or error page.');
            }
        
            return response.json();
        })
        .then(data => {
            showPopup(data.success, data.message);
            document.getElementById('inviteForm').reset();
        })
        .catch(error => {
            showPopup(false, 'Something went wrong. Please try again later.');
            console.error('Error in invite submission:', error);
        });
        
    }
});

// Handle Join Event button click
document.addEventListener('submit', function (event) {
    if (event.target.id === "joinEventForm") {
        event.preventDefault(); // Prevent form submission (page reload)

        const form = event.target;
        const formData = new FormData(form);

        // Make AJAX request to join the event
        fetch(form.action, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            showPopup(data.success, data.message);

            if (data.success) {
                const button = form.querySelector('button');
                button.textContent = "Joined!";
                button.disabled = true;
            }

            updateParticipantInfo();
            updateCommentForm();
            updateEventActions();
        })
        .catch(error => {
            showPopup(false, 'Something went wrong. Please try again later.');
            console.error(error);
        });
    }
});

// Handle Request to join Event button click
document.addEventListener('submit', function (event) {
    if (event.target.id === "requestEventForm") {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            showPopup(data.success, data.message);

            if (data.success) {
                const button = form.querySelector('button');
                button.textContent = "Request Sent!";
                button.disabled = false;
            }

            updateParticipantInfo();
            updateCommentForm();
            updateEventActions();
        })
        .catch(error => {
            showPopup(false, 'Something went wrong. Please try again later.');
            console.error(error);
        });
    }
});

// Handle Leave Event button click
document.addEventListener('submit', function (event) {
    if (event.target.id === "leaveEventForm") {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            showPopup(data.success, data.message);

            if (data.success) {
                const button = form.querySelector('button');
                button.textContent = "Join Event";
                button.disabled = false;
            }

            updateParticipantInfo();
            updateCommentForm();
            updateEventActions();
        })
        .catch(error => {
            showPopup(false, 'Something went wrong. Please try again later.');
            console.error(error);
        });
    }
});

// Reload Participant Info (count or list)
function updateParticipantInfo() {
    fetch(`/events/${eventId}/participants`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('participant-count').innerText = `${data.count} / ${data.capacity}`;
            
            const participantList = document.getElementById('participant-list');
            participantList.innerHTML = data.participantHtml;
        })
        .catch(error => console.error('Error updating participants:', error));
}

// Reload Comment Form
function updateCommentForm() {
    fetch(`/events/${eventId}/update-comment-form`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error updating comment form.');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('comment-form').innerHTML = data.commentFormHtml;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Reload Event Options
function updateEventActions() {
    fetch(`/events/${eventId}/event-actions`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error updating event actions.');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('actions').innerHTML = data.html;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function updateCountdown() {
    try {
        // Validate the countdown target element
        const countdownElement = document.getElementById('countdown');
        if (!countdownElement) {
            console.error("Element with ID 'countdown' not found.");
            return;
        }

        // Parse the dates
        const eventStartDate = new Date(startDate).getTime();
        const eventEndDate = new Date(endDate).getTime();
        const now = new Date().getTime();

        const distanceStart = eventStartDate - now;
        const distanceEnd = eventEndDate - now;

        // Handle event start and end scenarios
        if (distanceStart < 0 && distanceEnd > 0) {
            countdownElement.innerHTML = "Event has started!";
            return;
        }
        if (distanceEnd < 0) {
            countdownElement.innerHTML = "Event has ended!";
            return;
        }

        // Calculate remaining time
        const days = Math.floor(distanceStart / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distanceStart % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distanceStart % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distanceStart % (1000 * 60)) / 1000);

        // Build the countdown HTML
        let countdownHtml = '';
        if (days > 2) {
            countdownHtml = `
                <div class="flex flex-col items-center">
                    <span class="text-2xl">${days}</span>
                    <span class="text-xs">Days</span>
                </div>
            `;
        } else if (days >= 1) {
            countdownHtml = `
                <div class="flex flex-col items-center">
                    <span class="text-2xl">${days}</span>
                    <span class="text-xs">Days</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-2xl">${hours}</span>
                    <span class="text-xs">Hours</span>
                </div>
            `;
        } else if (hours >= 1) {
            countdownHtml = `
                <div class="flex flex-col items-center">
                    <span class="text-2xl">${hours}</span>
                    <span class="text-xs">Hours</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-2xl">${minutes}</span>
                    <span class="text-xs">Minutes</span>
                </div>
            `;
        } else {
            countdownHtml = `
                <div class="flex flex-col items-center">
                    <span class="text-2xl">${minutes}</span>
                    <span class="text-xs">Minutes</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-2xl">${seconds}</span>
                    <span class="text-xs">Seconds</span>
                </div>
            `;
        }

        // Update countdown element
        countdownElement.innerHTML = `<div class="flex space-x-1">${countdownHtml}</div>`;
    } catch (error) {
        console.error("Error in updateCountdown:", error);
    }
}

setInterval(updateCountdown, 1000);
updateCountdown();