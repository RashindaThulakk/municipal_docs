<?php
require 'includes/db_connect.php';
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login | Municipal Document System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body, html {
  height: 100%;
  margin: 0;
  font-family: 'Poppins', sans-serif;
}

.bg-image {
  background: url('assets/images/RM_Banner_Large.jpg') no-repeat center center;
  background-size: cover;
  height: 100vh;
  width: 100%;
  position: absolute;
  top: 0;
  left: 0;
}

.login-section {
  position: absolute;
  top: 0;
  right: 0;
  width: 33.33%;
  height: 100vh;
  background-color: #ffffff;
  display: flex;
  flex-direction: column;
  justify-content: center;
  box-shadow: -5px 0 25px rgba(0, 0, 0, 0.2);
  padding: 40px;
}

.login-section h2 {
  text-align: center;
  margin-bottom: 1.5rem;
  color: #007bff;
  font-weight: 600;
}

.btn-primary {
  width: 100%;
  border-radius: 50px;
  padding: 10px;
}

.form-control {
  border-radius: 10px;
}

.show-password {
  margin-top: 10px;
}
@media (max-width: 992px) {
  .login-section {
    width: 100%;
    box-shadow: none;
  }
}
</style>
</head>

<body>
<div class="bg-image"></div>

<div class="login-section">
  <h2>Municipal Document System</h2>
  <?php if ($error): ?>
    <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <form method="post" autocomplete="off">
    <div class="mb-3">
      <input type="text" name="username" class="form-control" placeholder="Username" required>
    </div>
    <div class="mb-3">
      <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
      <div class="form-check show-password">
        <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePassword()">
        <label class="form-check-label" for="showPassword">Show Password</label>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
  </form>
</div>

<script>
function togglePassword() {
  const passwordField = document.getElementById('password');
  passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
