(function($) {
    // Function to load content dynamically
    function loadContent(url, linkId) {
        $.ajax({
            type: "GET",
            url: url,
            dataType: "html",
            success: function(response) {
                $("#content-area").html(response);

                // Highlight the active link
                $(".nav-link").removeClass("active"); 
                $("#" + linkId).addClass("active"); 
            },
            error: function(xhr, status, error) {
                console.log("Error loading page:", error);
            }
        });
    }

    $(document).ready(function() {
        // Load home content initially and highlight the Home link
        loadContent("user/hero.html", "Home"); 

        $("#Home").click(function(event) {
            event.preventDefault();
            loadContent("user/hero.html", "Home");
        });

        $("#lost-and-found-link").click(function(event) {
            event.preventDefault();
            loadContent("user/lost_and_found.php", "lost-and-found-link");
        });

        $("#about-us-link").click(function(event) {
            event.preventDefault();
            loadContent("user/about_us.php", "about-us-link");
        });

        // Prevent dropdown from refreshing the page
        $('.dropdown-toggle').click(function(e) {
            e.preventDefault(); 
            $(this).dropdown('toggle'); 
        });
    });
})(jQuery);