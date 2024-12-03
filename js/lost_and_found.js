function loadFilteredContent() {
    const filterValue = $('input[name="filter"]:checked').val();
    const selectedCategories = $('input[name="categories[]"]:checked').map(function() {
        return this.value;
    }).get();
    const searchValue = $('input[name="search"]').val();
    
    $.ajax({
        url: 'user/lost_and_found.php',
        type: 'GET',
        data: {
            filter: filterValue,
            categories: selectedCategories,
            search: searchValue,
            ajax: true
        },
        success: function(response) {
            $('#content').html(response);
            initializeLostAndFound();
        },
        error: function(xhr, status, error) {
            console.error('Error loading filtered content:', error);
        }
    });
}

function updateURL() {
    const filterValue = $('input[name="filter"]:checked').val();
    const selectedCategories = $('input[name="categories[]"]:checked').map(function() {
        return this.value;
    }).get();
    const searchValue = $('input[name="search"]').val();
    
    let url = 'main.php?page=user/lost_and_found.php';
    
    if (filterValue) {
        url += '&filter=' + filterValue;
    }
    
    if (selectedCategories.length > 0) {
        url += '&' + selectedCategories.map(cat => 'categories[]=' + cat).join('&');
    }
    
    if (searchValue) {
        url += '&search=' + encodeURIComponent(searchValue);
    }
    
    window.history.pushState({}, '', url);
} 