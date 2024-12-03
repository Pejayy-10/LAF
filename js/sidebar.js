document.getElementById("toggleSidebar").addEventListener("click", function () {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("collapsed");

    // Adjust content margin based on sidebar state
    const content = document.querySelector(".content");
    if (sidebar.classList.contains("collapsed")) {
        content.style.marginLeft = "0";
    } else {
        content.style.marginLeft = "250px";
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Add change event listeners to all filter inputs
    const filterInputs = document.querySelectorAll('input[name="filter"], input[name="categories[]"]');
    filterInputs.forEach(input => {
        input.addEventListener('change', updateURL);
    });
});