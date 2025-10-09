<?php
// create_admin.php - run once to create an admin account
require 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = 'Admin';

    if (!$username || !$password) {
        $msg = "Enter username and password.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username,password,role) VALUES (?,?,?)");
        try {
            $stmt->execute([$username, $hash, $role]);
            $msg = "Admin created. Delete this file after use.";
        } catch (Exception $e) {
            $msg = "Error: " . $e->getMessage();
        }
    }
}
?>
<!-- Simple HTML form -->
<!doctype html><html><head><meta charset="utf-8"><title>Create Admin</title></head><body>
<h2>Create Admin</h2>
<?php if (!empty($msg)) echo "<p>$msg</p>"; ?>
<form method="post">
  Username: <input name="username"><br>
  Password: <input name="password" type="password"><br>
  <button type="submit">Create Admin</button>
</form>
</body></html>
