<script>
    let currentPage = 1;
    let isLoading = false;

    const loadMoreReviews = () => {
        if (isLoading) return; // Prevent multiple requests if one is already in progress

        isLoading = true; // Mark as loading
        const loader = document.getElementById('loading'); // Get the loader element
        loader.classList.remove('hidden'); // Show the loader while fetching

        // Get the current filters from the URL
        const params = new URLSearchParams(window.location.search);

        // Add the page parameter to the current filters
        params.set('page', ++currentPage);

        const url = `?${params.toString()}`; // Create the URL with the updated filters
        fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Send the request as an AJAX request
                }
            })
            .then(response => response.text()) // Convert response to text
            .then(data => {
                const reviewsList = document.getElementById('review-list'); // Get the reviews list container

                // Check if the response is empty
                if (!data.trim()) {
                    window.removeEventListener('scroll', scrollHandler); // Stop listening to scroll events
                    loader.textContent = 'No more reviews to load'; // Show a message to the user
                    return;
                }

                // Add the new reviews to the list
                reviewsList.insertAdjacentHTML('beforeend', data);
                loader.classList.add('hidden'); // Hide the loader
                isLoading = false; // Allow new requests
            })
            .catch(() => {
                loader.classList.add('hidden'); // Hide the loader if there's an error
                isLoading = false; // Reset loading flag even on failure
            });
    };


    // Separate scroll handler to easily remove it later
    const scrollHandler = () => {
        const {
            scrollTop,
            scrollHeight,
            clientHeight
        } = document.documentElement;
        if (scrollTop + clientHeight >= scrollHeight - 5) {
            loadMoreReviews();
        }
    };

    // Attach the scroll handler
    window.addEventListener('scroll', scrollHandler);
</script>