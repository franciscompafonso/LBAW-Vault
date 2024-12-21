document.addEventListener('DOMContentLoaded', function () {
    // Collect all polls on the page
    const polls = document.querySelectorAll('.poll');

    if (polls.length === 0) {
        console.error("No polls found on the page.");
        return;
    }

    polls.forEach((pollElement) => {
        const pollId = pollElement.dataset.pollId;
        const startTime = new Date(pollElement.dataset.pollStart).getTime();
        const endTime = new Date(pollElement.dataset.pollEnd).getTime();

        const countdownElement = pollElement.querySelector(`#countdown-${pollId}`);
        const parentElement = countdownElement.parentElement;

        if (!countdownElement) {
            console.error(`Countdown element not found for poll ID: ${pollId}`);
            return;
        }

        const updateCountdown = (distance) => {
            if (distance < 0) return "";

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (days > 0) {
                return `${days}d ${hours}h`;
            } else if (hours > 0) {
                return `${hours}h ${minutes}m`;
            } else {
                return `${minutes}m ${seconds}s`;
            }
        };

        const startCountdown = (endTime, updateMessage, prefixMessage) => {
            const countdownInterval = setInterval(function () {
                const now = new Date().getTime();
                const distance = endTime - now;

                if (distance < 0) {
                    clearInterval(countdownInterval);
                    parentElement.textContent = updateMessage;
                    return;
                }

                parentElement.textContent = `${prefixMessage}: ${updateCountdown(distance)}`;
            }, 1000);
        };

        const now = new Date().getTime();

        if (now < startTime) {
            startCountdown(startTime, "Poll has started", "Poll starts in");
        } else if (now < endTime) {
            startCountdown(endTime, "Poll has ended", "Poll ends in");
        } else {
            parentElement.textContent = "Poll has ended";
        }
    });
});
