document.addEventListener('DOMContentLoaded', function () {
    // Initial state: collapsed by default
    const collapsedSections = JSON.parse(localStorage.getItem('collapsedSections')) || {};

    const sections = ['visibility', 'category', 'district', 'status'];
    sections.forEach((sectionId) => {
        const section = document.getElementById(sectionId);
        const icon = document.getElementById(sectionId + '-arrow');
        
        if (!collapsedSections[sectionId] || collapsedSections[sectionId] === 'collapsed') {
            section.classList.add('hidden');
            icon.classList.add('transform', 'rotate-90');
            collapsedSections[sectionId] = 'collapsed'; // Ensure default is saved
        }
    });

    localStorage.setItem('collapsedSections', JSON.stringify(collapsedSections));

    // Toggle sections visibility and save state to localStorage
    window.toggleSectionVisibility = function (id) {
        const section = document.getElementById(id);
        const icon = document.getElementById(id + '-arrow');
        if (section.classList.contains('hidden')) {
            section.classList.remove('hidden');
            icon.classList.remove('rotate-90');
            collapsedSections[id] = 'expanded';
        } else {
            section.classList.add('hidden');
            icon.classList.add('rotate-90');
            collapsedSections[id] = 'collapsed';
        }
        localStorage.setItem('collapsedSections', JSON.stringify(collapsedSections));
    };

    // Set min and max for date inputs
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');

    startDate.addEventListener('change', function () {
        endDate.setAttribute('min', startDate.value);
    });

    endDate.addEventListener('change', function () {
        startDate.setAttribute('max', endDate.value);
    });

    // Set initial min and max dates if values are already filled
    if (startDate.value) {
        endDate.setAttribute('min', startDate.value);
    }
    if (endDate.value) {
        startDate.setAttribute('max', endDate.value);
    }
    });


    document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filtersForm');
    const eventsGrid = document.querySelector('[aria-label="Events grid"]');
    const eventsCount = document.querySelector('p span.text-ultraViolet');

    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        const formData = new FormData(form);
        const queryString = new URLSearchParams(formData).toString();

        // Update the browser URL without reloading the page
        const newUrl = `/events/search?${queryString}`;
        history.pushState(null, '', newUrl); // Update the URL in the browser

        fetch(`/events/search?${queryString}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Signal it's an AJAX request
            },
        })
        .then(response => response.json())
        .then(data => {
            // Update events count
            if (eventsCount) {
                eventsCount.textContent = data.eventsCount;
            }

            // Update the events grid
            if (eventsGrid) {
                eventsGrid.innerHTML = data.html;
            }
        })
        .catch(error => {
            console.error('Error fetching filtered results:', error);
        });
    });

    // Reset button logic
    const resetButton = form.querySelector('button[aria-label="Reset filters"]');
    resetButton.addEventListener('click', function () {
        form.reset(); // Reset all filters
        form.dispatchEvent(new Event('submit')); // Trigger AJAX reload
    });
});