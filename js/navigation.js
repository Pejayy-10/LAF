$(document).ready(function() {
    // Load hero page by default
    loadContent('user/hero.html');
    
    // Set Home link as active by default
    $('a.nav-link:contains("Home")').addClass('active');

    // Handle navigation clicks
    $('.nav-link:not(#report-btn):not(.dropdown-toggle)').on('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all nav links
        $('.nav-link').removeClass('active');
        // Add active class to clicked link
        $(this).addClass('active');

        const page = $(this).data('page');
        if (page) {
            loadContent(page);
            // Update URL without reloading
            window.history.pushState({}, '', 'main.php?page=' + page);
        }
    });

    // Handle dropdown menu items
    $('.dropdown-item:not([href="account/logout.php"])').on('click', function(e) {
        e.preventDefault();
        
        const page = $(this).data('page');
        if (page) {
            loadContent(page);
            // Update URL without reloading
            window.history.pushState({}, '', 'main.php?page=' + page);
        }
    });
});

function loadContent(page) {
    $.ajax({
        url: page,
        type: 'GET',
        success: function(response) {
            $('#content').html(response);
            
            // Initialize typing animation if it's the hero page
            if (page === 'user/hero.html') {
                initializeTyping();
            }
            
            // Reinitialize any necessary plugins or event handlers
            if (page === 'user/lost_and_found.php') {
                initializeLostAndFound();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading content:', error);
            $('#content').html('<div class="alert alert-danger">Error loading page: ' + error + '</div>');
        }
    });
}

function initializeLostAndFound() {
    // Initialize filters and search functionality
    $('input[name="filter"], input[name="categories[]"]').on('change', function() {
        updateURL();
        loadFilteredContent();
    });

    $('input[name="search"]').on('keyup', function() {
        updateURL();
        loadFilteredContent();
    });
}

function initializeTyping() {
    if ($('.typing-left').length > 0) {
        new Typed('.typing-left', {
            strings: ["Lost Something?", "Found Something?"],
            typeSpeed: 100,
            backSpeed: 60,
            loop: true,
            showCursor: true,
            cursorChar: '|',
            autoInsertCss: true
        });
    }
} 