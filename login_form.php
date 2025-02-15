<?php
session_start();
require_once __DIR__ . '/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $password  = $_POST['password'] ?? '';
    $email     = trim($_POST['email'] ?? '');
    
    if (!empty($firstName) && !empty($lastName) && !empty($username) && !empty($password) && !empty($email)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Username already exists!";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, email, password, role) VALUES (?, ?, ?, ?, ?, 'user')");
            
            if ($stmt->execute([$firstName, $lastName, $username, $email, $hashedPassword])) {
                $success = "Registration successful! Please login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    } else {
        $error = "All fields are required!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    
    if (!empty($username) && !empty($password) && !empty($role)) {
        if ($role === 'admin') {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = 'user'");
        }
        
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $role;
      
            if ($role === 'admin') {
                $_SESSION['admin_logged_in'] = true;
                setcookie('loggedInUser', $user['username'], time() + 3600, '/'); 
                header("Location: admin.php");
                exit;
            } else {
                $_SESSION['user_logged_in'] = true;
                setcookie('loggedInUser', $user['username'], time() + 3600, '/'); 
                header("Location: index.php");
                exit;
            }
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Please fill in all fields!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login/Register - Pet Store</title>
  <link rel="stylesheet" href="css/auth.css">
</head>
<body>
  <div class="auth-container">
    <div class="welcome-side">
      <h2>Welcome Back</h2>
      <p>Nice to see you again</p>
      <p class="extra-note">
        Ready to adopt your new best friend? Find the perfect pet and give them a loving home today.
      </p>
    </div>

    <div class="form-side">
      <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        <div class="success"><?php echo $success; ?></div>
      <?php endif; ?>

      <form method="post" action="" id="loginForm">
        <h3>Login Account</h3>
        <div class="form-group">
          <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="radio-group">
          <label>
            <input type="radio" name="role" value="user" checked> User
          </label>
          <label>
            <input type="radio" name="role" value="admin"> Admin
          </label>
        </div>
        <input type="hidden" name="action" value="login">
        <button type="submit">Login</button>
        <div class="toggle-form">
          <a href="#" onclick="toggleForms(); return false;">Need an account? Sign up</a>
        </div>
      </form>

      <form method="post" action="" id="registerForm" style="display: none;">
        <h3>Create Account</h3>
        <div class="form-group">
          <input type="text" name="first_name" placeholder="First Name" required>
        </div>
        <div class="form-group">
          <input type="text" name="last_name" placeholder="Last Name" required>
        </div>
        <div class="form-group">
          <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
          <input type="email" name="email" placeholder="Email ID" required>
        </div>
        <div class="form-group">
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <input type="hidden" name="action" value="register">
        <button type="submit">Sign Up</button>
        <div class="toggle-form">
          <a href="#" onclick="toggleForms(); return false;">Already have an account? Login</a>
        </div>
      </form>
    </div>
  </div>

  <script src="js/auth.js"></script>
</body>
</html>
