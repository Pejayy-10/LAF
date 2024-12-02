<div>
    <h2 class="text-danger">Manage Found Items</h2>
    <table class="table table-hover mt-3" id="found-items-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Description</th>
                <th>Date Found</th>
                <th>Location</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded via AJAX -->
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        loadFoundItems();
    });

    function loadFoundItems() {
        $.ajax({
            url: '../processes/fetch_found_items.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                let rows = '';
                data.forEach(item => {
                    rows += `
                        <tr>
                            <td>${item.item_id}</td>
                            <td>${item.category}</td>
                            <td>${item.description}</td>
                            <td>${item.date_found}</td>
                            <td>${item.location}</td>
                            <td>${item.status}</td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-item" data-id="${item.item_id}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-item" data-id="${item.item_id}">Delete</button>
                            </td>
                        </tr>`;
                });
                $('#found-items-table tbody').html(rows);

                // Add click handlers for edit and delete
                $('.edit-item').click(function () {
                    let itemId = $(this).data('id');
                    editFoundItem(itemId);
                });

                $('.delete-item').click(function () {
                    let itemId = $(this).data('id');
                    deleteFoundItem(itemId);
                });
            },
            error: function () {
                console.error("Error loading found items.");
            },
        });
    }

    function editFoundItem(itemId) {
        console.log(`Edit found item with ID: ${itemId}`);
        // Add functionality to edit found items
    }

    function deleteFoundItem(itemId) {
        if (confirm('Are you sure you want to delete this found item?')) {
            $.ajax({
                url: `../processes/delete_found_item.php?id=${itemId}`,
                type: 'GET',
                success: function (response) {
                    if (response === 'success') {
                        loadFoundItems(); // Reload the found items list
                    } else {
                        alert('Failed to delete found item.');
                    }
                },
                error: function () {
                    alert('Error deleting found item.');
                },
            });
        }
    }
</script>
