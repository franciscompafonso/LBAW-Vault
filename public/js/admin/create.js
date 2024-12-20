document.querySelectorAll('.action-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        let loadingIcon = button.querySelector('.loading-icon');
        if (loadingIcon) {
            loadingIcon.classList.remove('hidden');
        }
    });
});