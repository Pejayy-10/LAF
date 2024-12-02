<div>
    <h2 class="text-danger">Manage Users</h2>
    <table class="table table-hover mt-3" id="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
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
        loadUsers();
        $.get("../modals/delete_user.html", function (modalContent) {
                $("body").append(modalContent);
            });
    });

    function loadUsers() {
        $.ajax({
            url: '../processes/fetch_users.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                let rows = '';
                data.forEach(user => {
                    rows += `
                        <tr>
                            <td>${user.user_id}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td>${user.role}</td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-user" data-id="${user.user_id}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-user" data-id="${user.user_id}">Delete</button>
                            </td>
                        </tr>`;
                });
                $('#users-table tbody').html(rows);

                $('.edit-user').click(function () {
                    let userId = $(this).data('id');
                    editUser(userId);
                });

                $('.delete-user').click(function () {
                    let userId = $(this).data('id');
                    openDeleteModal(userId);
                });
            },
            error: function () {
                console.error("Error loading users.");
            },
        });
    }
    function openDeleteModal(userId) {
            $('#confirmDelete').data('id', userId);
            $('#deleteUserModal').modal('show');
        }

        $(document).on('click', '#confirmDelete', function () {
            const userId = $(this).data('id');
            deleteUser(userId);
        });

        function deleteUser(userId) {
            $.ajax({
                url: `../processes/delete_user.php?id=${userId}`,
                type: 'GET',
                success: function (response) {
                    if (response === 'success') {
                        $('#deleteUserModal').modal('hide');
                        loadUsers();
                    } else {
                        alert('Failed to delete user.');
                    }
                },
                error: function () {
                    alert('Error deleting user.');
                },
            });
        }

    function editUser(userId) {
        $.ajax({
            type: "GET",
            url: `../processes/fetch_user_details.php?id=${userId}`,
            dataType: "json",
            success: function (userData) {
                // modal
                $.get("../modals/edit_user.html", function (modalContent) {
                    $("body").append(modalContent);
                    $("#editUserId").val(userData.user_id);
                    $("#editUsername").val(userData.username);
                    $("#editEmail").val(userData.email);
                    $("#editRole").val(userData.role);
                    $("#editUserModal").modal("show");
                });

                // Handle form submission
                $(document).off('submit', '#editUserForm').on('submit', '#editUserForm', function (e) {
                    e.preventDefault();
                    updateUser(userId);
                });
            },
            error: function () {
                alert("Error fetching user details.");
            }
        });
    }

    function updateUser(userId) {
        const username = $('#editUsername').val();
        const email = $('#editEmail').val();
        const role = $('#editRole').val();

        $.ajax({
            type: "POST",
            url: "../processes/update_user.php",
            data: {
                user_id: userId,
                username: username,
                email: email,
                role: role
            },
            success: function (response) {
                try {
                    const res = JSON.parse(response);
                    if (res.status === "success") {
                        $("#editUserModal").modal("hide");
                        $('#editUserModal').remove();
                        loadUsers();
                        alert('User updated successfully!');
                    } else {
                        alert('Error updating user: ' + res.message);
                    }
                } catch (e) {
                    alert('An unexpected error occurred.');
                }
            },
            error: function () {
                alert('Error updating user.');
            }
        });
    }
</script>
