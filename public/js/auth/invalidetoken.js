const requestButton = document.getElementById('request-reset-btn');
requestButton.addEventListener('click', function(event) {
    const loadingIcon = document.getElementById('loading-reset');
    if (loadingIcon) {
        loadingIcon.classList.remove('hidden');
    }
});