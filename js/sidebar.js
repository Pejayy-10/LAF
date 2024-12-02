
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