// JavaScript to Toggle Reply Form
function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    form.classList.toggle('hidden'); // Toggles visibility of the form
}
