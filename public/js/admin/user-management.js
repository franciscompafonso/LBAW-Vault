document.querySelectorAll('.delete-btn, .unblock-btn, .block-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        let loadingIcon = button.querySelector('.loading-icon');
        if (loadingIcon) {
            loadingIcon.classList.remove('hidden');
        }
    });
});