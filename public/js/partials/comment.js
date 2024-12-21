// JavaScript to Toggle Reply Form
function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    form.classList.toggle('hidden'); // Toggles visibility of the form
}

// JavaScript to show loading icon on comment button click
document.addEventListener('DOMContentLoaded', function () {
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function () {
            const loadingIcon = document.getElementById('loading-comment');
            if (loadingIcon) {
                loadingIcon.classList.remove('hidden');
            }
        });
    }
});
