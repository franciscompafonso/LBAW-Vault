// Quotes Array
const quotes = [
    "Empowering Communities, One Event at a Time.",
    "Building Stronger Bonds, One Step at a Time.",
    "Transforming Lives,  One Kind Act at a Time.",
    "Inspiring Change, One Small Action at a Time.",
    "Spreading Warmth, One Smile Given at a Time.",
    "Uplifting Spirits, One Act of Care at a Time."

    ];


const quoteElement = document.getElementById('quote');
let currentIndex = 0;

// Function to update the quote with a transition
function updateQuote() {
    quoteElement.classList.add('fade-out');
    setTimeout(() => {
        currentIndex = (currentIndex + 1) % quotes.length;
        quoteElement.textContent = quotes[currentIndex];

        quoteElement.classList.remove('fade-out');
        quoteElement.classList.add('fade-in');
    }, 500);

    setTimeout(() => {
        quoteElement.classList.remove('fade-in');
    }, 500);
}

// Change quote every 5 seconds
setInterval(updateQuote, 5000);