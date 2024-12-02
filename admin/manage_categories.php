<!DOCTYPE html>
<html lang="en">
<s>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Categories</title>
  <link href="../css/admin.css" rel="stylesheet">
</s>
<body>
    <h1>Manage Categories</h1>
    <form id="add-category-form">
      <div class="input-group mb-3">
        <input type="text" id="category_name" name="category_name" class="form-control" placeholder="New Category" required>
        <button type="submit" class="btn btn-danger">Add</button>
      </div>
    </form>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Category Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        </tbody>
    </table>
  <script src="../js/jquery.min.js"></script>
  <script>
    $(document).ready(function () {
      loadCategories();

      $('#add-category-form').submit(function (event) {
        event.preventDefault();

        const categoryName = $('#category_name').val();

        $.ajax({
          url: '../processes/add_category.php',
          type: 'POST',
          data: { category_name: categoryName },
          success: function (response) {
            if (response === 'success') {
              loadCategories(); // Reload the categories list
              $('#category_name').val(''); // Clear the input field
            } else {
              alert('Failed to add category.');
            }
          },
          error: function () {
            alert('Error adding category.');
          }
        });
      });
    });
    
    $(document).on('click', '.delete-category-btn', function() {
        const categoryId = $(this).data('id');
            if (confirm('Are you sure you want to delete this category?')) {
                $.ajax({
                    url: '../processes/delete_category.php',
                    type: 'POST',
                    data: { category_id: categoryId },
                    success: function(response) {
                        if (response === 'success') {
                            loadCategories();
                        } else {
                            alert('Failed to delete category. Please try again.');
                        }
                    },
                    error: function() {
                    alert('Error deleting category. Please check the server logs.');
                }
            });
        }
    });

    function loadCategories() {
      $.ajax({
        url: '../processes/fetch_category.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
          let rows = '';
          data.forEach(category => {
            rows += `
              <tr>
                <td>${category.category_id}</td>
                <td>${category.category_name}</td>
                <td>
                  <button class="btn btn-danger btn-sm delete-category-btn" data-id="${category.category_id}">Delete</button>
                </td>
              </tr>`;
          });
          $('tbody').html(rows);
        },
        error: function () {
          console.error("Error loading categories.");
        }
      });
    }
  </script>
</body>
</html>