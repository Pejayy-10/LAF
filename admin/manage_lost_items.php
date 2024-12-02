<div>
    <h2 class="text-danger">Manage Lost Items</h2>
    <table class="table table-hover mt-3" id="lost-items-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Description</th>
                <th>Date Lost</th>
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
        loadLostItems();
    });

    function loadLostItems() {
        $.ajax({
            url: '../processes/fetch_lost_items.php',
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
                            <td>${item.date_lost}</td>
                            <td>${item.location}</td>
                            <td>${item.status}</td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-item" data-id="${item.item_id}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-item" data-id="${item.item_id}">Delete</button>
                            </td>
                        </tr>`;
                });
                $('#lost-items-table tbody').html(rows);

                $('.edit-item').click(function () {
                    let itemId = $(this).data('id');
                    editLostItem(itemId);
                });

                $('.delete-item').click(function () {
                    let itemId = $(this).data('id');
                    deleteLostItem(itemId);
                });
            },
            error: function () {
                console.error("Error loading lost items.");
            },
        });
    }

    function deleteLostItem(itemId) {
        $.get("../modals/delete_lost_item.html", function (modalContent) {
            $("body").append(modalContent);
            $("#deleteLostItemModal").modal("show");

            $("#confirmDeleteLostItem").off("click").on("click", function () {
                $.ajax({
                    url: `../processes/delete_lost_item.php?id=${itemId}`,
                    type: "GET",
                    success: function (response) {
                        if (response === "success") {
                            $("#deleteLostItemModal").modal("hide").on("hidden.bs.modal", function () {
                                $(this).remove();
                            });
                            loadLostItems();
                            alert("Lost item deleted successfully!");
                        } else {
                            alert("Failed to delete the lost item.");
                        }
                    },
                    error: function () {
                        alert("Error deleting the lost item.");
                    },
                });
            });

            $("#deleteLostItemModal").on("hidden.bs.modal", function () {
                $(this).remove();
            });
        });
    }

    function editLostItem(itemId) {
        $.ajax({
            type: "GET",
            url: `../processes/fetch_lost_items.php?id=${itemId}`,
            dataType: "json",
            success: function (itemData) {
                $.get("../modals/edit_lost_item.html", function (modalContent) {
                    $("body").append(modalContent);
                    $("#editLostItemId").val(itemData.item_id);
                    $("#editCategory").val(itemData.category);
                    $("#editDescription").val(itemData.description);
                    $("#editDateLost").val(itemData.date_lost);
                    $("#editLocation").val(itemData.location);
                    $("#editStatus").val(itemData.status);
                    $("#editLostItemModal").modal("show");
                });

                $(document)
                    .off("submit", "#editLostItemForm")
                    .on("submit", "#editLostItemForm", function (e) {
                        e.preventDefault();
                        const updatedData = {
                            item_id: $("#editLostItemId").val(),
                            category: $("#editCategory").val(),
                            description: $("#editDescription").val(),
                            date_lost: $("#editDateLost").val(),
                            location: $("#editLocation").val(),
                            status: $("#editStatus").val(),
                        };

                        $.ajax({
                            type: "POST",
                            url: "../processes/update_lost_item.php",
                            data: updatedData,
                            success: function (response) {
                                if (response === "success") {
                                    $("#editLostItemModal").modal("hide").on("hidden.bs.modal", function () {
                                        $(this).remove();
                                    });
                                    loadLostItems();
                                    alert("Lost item updated successfully!");
                                } else {
                                    alert("Failed to update the lost item.");
                                }
                            },
                            error: function () {
                                alert("Error updating the lost item.");
                            },
                        });
                    });

                $("#editLostItemModal").on("hidden.bs.modal", function () {
                    $(this).remove();
                });
            },
            error: function () {
                alert("Error fetching lost item details.");
            },
        });
    }
</script>
