const pollData = {
    id: "{{ $poll->id }}",
    startTime: new Date("{{ $poll->start_time->format('Y-m-d\TH:i:sP') }}").getTime(),
    endTime: new Date("{{ $poll->end_time->format('Y-m-d\TH:i:sP') }}").getTime()
};

console.log("Poll Data:", pollData);

document.addEventListener('DOMContentLoaded', function () {
    const countdownElement = document.getElementById('countdown-' + pollData.id);
    if (!countdownElement) {
        console.error("Countdown element not found for poll ID:", pollData.id);
        return;
    }

    const now = new Date().getTime();
    console.log("Current Time:", now);
    console.log("Poll Start Time:", pollData.startTime);
    console.log("Poll End Time:", pollData.endTime);

    const startCountdown = (endTime, message) => {
        const countdownInterval = setInterval(function () {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(countdownInterval);
                countdownElement.innerHTML = message;
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        }, 1000);
    };

    if (now < pollData.startTime) {
        console.log("Countdown to poll start");
        startCountdown(pollData.startTime, "Poll has started");
    } else if (now < pollData.endTime) {
        console.log("Countdown to poll end");
        startCountdown(pollData.endTime, "Poll has ended");
    } else {
        countdownElement.innerHTML = "Poll has ended";
    }
});
