document.addEventListener('DOMContentLoaded', function () {
    const pollEndTime = new Date(pollData.endTime).getTime();
    const pollStartTime = new Date(pollData.startTime).getTime();
    const now = new Date().getTime();
    let countdownTime = pollEndTime;

    if (now < pollStartTime) {
        countdownTime = pollStartTime;
    }

    const countdownElement = document.getElementById('countdown-' + pollData.id);

    const updateCountdown = () => {
        const now = new Date().getTime();
        const distance = countdownTime - now;

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;

        if (distance < 0) {
            clearInterval(interval);
            countdownElement.innerHTML = "Poll has ended.";
        }
    };

    const interval = setInterval(updateCountdown, 1000);
    updateCountdown();
});