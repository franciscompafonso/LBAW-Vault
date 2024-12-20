document.addEventListener('DOMContentLoaded', function () {
    const handlePagination = (event) => {
        event.preventDefault();

        const url = event.target.closest('a').href; // Get the pagination URL
        const section = event.target.closest('div[id$="-container"]').id.replace('-container', ''); // Extract section ID

        console.log(`Fetching URL: ${url} for section: ${section}`); // Debugging

        fetch(`${url}&section=${section}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then((html) => {
                const container = document.getElementById(`${section}-container`);
                if (container) {
                    console.log(`Updating content for section: ${section}`);
                    container.innerHTML = html; // Replace HTML content
                    attachPaginationHandlers(); // Reattach pagination handlers
                } else {
                    console.error(`Container not found for section: ${section}`);
                }
            })
            .catch((error) => {
                console.error('Error fetching pagination:', error);
            });
    };

    const attachPaginationHandlers = () => {
        document.querySelectorAll('.pagination a').forEach((link) => {
            link.addEventListener('click', handlePagination);
        });
    };

    attachPaginationHandlers(); // Initial setup
});