const form = document.querySelector('form');
form.addEventListener('submit', function () {
    const loadingIcon = document.getElementById('loading-reset');
    if (loadingIcon) {
        loadingIcon.classList.remove('hidden');
    }
});