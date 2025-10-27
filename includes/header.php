<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Municipal Document System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
  <header class="app-header">
    <div class="container">
     <h1><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Municipal Document System'; ?></h1>
    </div>
  </header>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <div><strong>Welcome</strong> <?php echo isset($_SESSION['username'])? htmlspecialchars($_SESSION['username']): 'Guest'; ?></div>
      <div class="ms-auto">
        <?php if (!empty($_SESSION['user_id'])): ?>
          <a href="logout.php" class="btn btn-outline-secondary btn-sm">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

<div class="container-fluid mt-3">
       