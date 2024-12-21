function togglePollForm() {
    document.getElementById('poll-form').classList.toggle('hidden');
}

function addOption() {
    const container = document.getElementById('poll-options-container');
    const optionDiv = document.createElement('div');
    optionDiv.classList.add('flex', 'items-center', 'mt-2');
    optionDiv.innerHTML = `
        <input type="text" name="options[]" class="w-full p-2 border border-gray-300 rounded-lg" required maxlength="40">
        <button type="button" class="ml-2 px-2 py-1 bg-red-500 text-white rounded-lg" onclick="removeOption(this)">X</button>
    `;
    container.appendChild(optionDiv);
}

function removeOption(button) {
    const container = document.getElementById('poll-options-container');
    container.removeChild(button.parentElement);
}