<?php
require_once 'classes/Item.class.php';
$itemInstance = new Item();

// Get current filter values
$currentFilter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$selectedCategories = isset($_GET['categories']) ? $_GET['categories'] : [];

// Fetch categories directly
$categories = $itemInstance->fetchCategories();
?>
<link rel="stylesheet" href="../css/user_sidebar.css">
<div class="sidebar">
    <h3>Filter</h3>
    <div>
        <label>
            <input type="radio" name="filter" value="all" <?= $currentFilter === 'all' ? 'checked' : '' ?>> All
        </label>
        <label>
            <input type="radio" name="filter" value="lost" <?= $currentFilter === 'lost' ? 'checked' : '' ?>> Lost
        </label>
        <label>
            <input type="radio" name="filter" value="found" <?= $currentFilter === 'found' ? 'checked' : '' ?>> Found
        </label>
    </div>
    <h4>Categories</h4>
    <div id="category-checkboxes">
        <?php foreach ($categories as $category): ?>
            <div>
                <label>
                    <input type="checkbox" 
                           name="categories[]" 
                           value="<?= htmlspecialchars($category['category_id']) ?>"
                           <?= in_array($category['category_id'], $selectedCategories) ? 'checked' : '' ?>> 
                    <?= htmlspecialchars($category['category_name']) ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add change event listeners to all filter inputs
        const filterInputs = document.querySelectorAll('input[name="filter"], input[name="categories[]"]');
        filterInputs.forEach(input => {
            input.addEventListener('change', applyFilters);
        });

        function applyFilters() {
            const filterValue = document.querySelector('input[name="filter"]:checked').value;
            const selectedCategories = Array.from(document.querySelectorAll('input[name="categories[]"]:checked'))
                .map(checkbox => checkbox.value);
            
            let url = window.location.pathname + '?filter=' + filterValue;
            if (selectedCategories.length > 0) {
                url += '&' + selectedCategories.map(cat => 'categories[]=' + cat).join('&');
            }
            window.location.href = url;
        }
    });
</script>
