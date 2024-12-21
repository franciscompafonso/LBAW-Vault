function showTab(tab) {
    // Hide both contents
    document.getElementById('content-invite-sent').classList.add('hidden');
    document.getElementById('content-invite-received').classList.add('hidden');
    document.getElementById('content-request-sent').classList.add('hidden');
    document.getElementById('content-request-received').classList.add('hidden');

    // Show the selected content
    document.getElementById(`content-${tab}`).classList.remove('hidden');

    // Update tab indicator position and width
    const activeTab = document.getElementById(`tab-${tab}`);
    const tabIndicator = document.getElementById('tab-indicator');

    const tabWidth = activeTab.offsetWidth;
    const tabOffset = activeTab.offsetLeft;

    tabIndicator.style.width = `${tabWidth}px`;
    tabIndicator.style.transform = `translateX(${tabOffset}px)`;
}

// Initialize to show "Sent Invites" tab
showTab('invite-sent');
