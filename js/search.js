document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');

    // Search functionality
    function performSearch() {
        const searchText = searchInput.value.toLowerCase();
        document.querySelectorAll('.card').forEach(function(card) {
            const cardText = card.textContent.toLowerCase();
            const cardContainer = card.closest('.col-md-4');
            if(cardText.includes(searchText)) {
                cardContainer.style.display = '';
            } else {
                cardContainer.style.display = 'none';
            }
        });
    }

    // Add event listeners
    if(searchInput) {
        searchInput.addEventListener('keyup', performSearch);
    }
    if(searchButton) {
        searchButton.addEventListener('click', performSearch);
    }
});
