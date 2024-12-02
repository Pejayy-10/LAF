<?php
session_start();
require_once '../classes/Database.class.php';

$error_message = '';
$success_message = '';
$form_type = 'login'; // Default form is login

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Login functionality
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            $error_message = "Please fill in all fields.";
        } else {
            try {
                $db = new Database();
                $conn = $db->connect();

                $sql = "SELECT * FROM users WHERE username = :username";
                $query = $conn->prepare($sql);
                $query->execute(['username' => $username]);
                $user = $query->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        header('Location: ../admin/dashboard.php');
                    } else {
                        header('Location: ../main.php');
                    }
                    exit;
                } else {
                    $error_message = "Invalid username or password!";
                }
            } catch (PDOException $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        }
    } elseif (isset($_POST['register'])) {
        // Registration functionality
        $form_type = 'register'; // Switch to the register form in case of an error
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm-password']);

        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $error_message = "Please fill in all fields.";
        } elseif ($password !== $confirm_password) {
            $error_message = "Passwords do not match!";
        } else {
            try {
                $db = new Database();
                $conn = $db->connect();

                // Check if username or email already exists
                $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
                $query = $conn->prepare($sql);
                $query->execute(['username' => $username, 'email' => $email]);

                if ($query->rowCount() > 0) {
                    $error_message = "Username or email already exists!";
                } else {
                    // Hash password
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                    // Insert user into database
                    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
                    $query = $conn->prepare($sql);
                    $query->execute([
                        'username' => $username,
                        'email' => $email,
                        'password' => $hashed_password
                    ]);

                    $success_message = "Registration successful! You can now log in.";
                    $form_type = 'login'; // Switch back to login form after successful registration
                }
            } catch (PDOException $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/auth.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.12/typed.min.js"></script>
</head>
<body>
    <div class="background-text text-left">
        <span class="typing-left"></span>
    </div>
    <div class="background-text text-right">
        <span class="typing-right"></span>
    </div>
    
    <div class="container">
        <div class="forms-wrapper <?php echo $form_type === 'register' ? 'slide-left' : ''; ?>" id="formsWrapper">
            <div class="form-section">
                <h2 class="form-title" id="loginTitle">Login</h2>
                <?php if ($form_type === 'login' && !empty($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <input type="text" id="username" name="username" placeholder="Enter your username">
                        <label for="username">Username</label>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Enter your password">
                        <label for="password">Password</label>
                    </div>
                    <button type="submit" name="login" class="submit-btn">Login</button>
                    <div class="form-footer">
                        <p>Don't have an account? <a href="#" onclick="toggleForms()">Sign up here</a></p>
                    </div>
                </form>
            </div>
            
            <div class="form-section">
                <h2 class="form-title">Sign Up</h2>
                <?php if ($form_type === 'register' && !empty($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <input type="text" id="newUsername" name="username" placeholder="Enter your username">
                        <label for="newUsername">Username</label>
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Enter your email">
                        <label for="email">Email</label>
                    </div>
                    <div class="form-group">
                        <input type="password" id="newPassword" name="password" placeholder="Enter your password">
                        <label for="newPassword">Password</label>
                    </div>
                    <div class="form-group">
                        <input type="password" id="confirmPassword" name="confirm-password" placeholder="Confirm your password">
                        <label for="confirmPassword">Confirm Password</label>
                    </div>
                    <button type="submit" name="register" class="submit-btn">Sign Up</button>
                    <div class="form-footer">
                        <p>Already have an account? <a href="#" onclick="toggleForms()">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
      function toggleForms() {
        const wrapper = document.getElementById('formsWrapper');
        wrapper.classList.toggle('slide-left');
      }

      // Typing animations
      var typedLeft = new Typed(".typing-left", {
          strings: ["LOST ", "FOUND"],
          typeSpeed: 100,
          backSpeed: 60,
          loop: true,
          showCursor: true,
          cursorChar: '|',
          autoInsertCss: true
      });

      var typedRight = new Typed(".typing-right", {
          strings: ["FOUND", "LOST "],
          typeSpeed: 100,
          backSpeed: 60,
          loop: true,
          showCursor: true,
          cursorChar: '|',
          autoInsertCss: true
      });
    </script>
</body>
</html>
