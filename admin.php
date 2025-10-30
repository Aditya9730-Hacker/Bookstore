<?php
require 'db.php';
session_start();
// Simple admin login/register (demo). Passwords hashed with password_hash.
$action = $_POST['action'] ?? null;
if($action === 'login'){
  $u = trim($_POST['username']); $p = $_POST['password'];
  $stmt = $mysqli->prepare('SELECT id,password_hash FROM admins WHERE username=? LIMIT 1'); $stmt->bind_param('s',$u); $stmt->execute(); $r=$stmt->get_result(); $row=$r->fetch_assoc();
  if($row && password_verify($p, $row['password_hash'])){ $_SESSION['admin_id']=$row['id']; header('Location: admin.php'); exit; } else $err='Invalid credentials';
}
if($action === 'register'){
  // registration allowed only if there are no admins (simple demo)
  $count = $mysqli->query('SELECT COUNT(*) as c FROM admins')->fetch_assoc()['c'];
  if($count>0) $err='Admin already exists. Please login.'; else {
    $u = trim($_POST['username']); $p = $_POST['password']; $hash = password_hash($p, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare('INSERT INTO admins (username,password_hash) VALUES (?,?)'); $stmt->bind_param('ss',$u,$hash); $stmt->execute();
    $id = $mysqli->insert_id; $_SESSION['admin_id']=$id; header('Location: admin.php'); exit;
  }
}
if(isset($_GET['logout'])){ unset($_SESSION['admin_id']); header('Location: admin.php'); exit; }
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="styles.css"><title>Admin</title></head><body>
<div class="container">
  <a href="index.php">← Shop</a>
  <h1>Admin</h1>
  <?php if(!isset($_SESSION['admin_id'])): ?>
    <div class="card">
      <h3>Login</h3>
      <?php if(isset($err)) echo '<div class="muted">'.htmlspecialchars($err).'</div>'; ?>
      <form method="post">
        <input type="hidden" name="action" value="login">
        <label>Username</label><input name="username" required>
        <label>Password</label><input type="password" name="password" required>
        <div style="height:8px"></div>
        <button class="btn">Login</button>
      </form>
      <hr>
      <h4>Or register (one-time)</h4>
      <form method="post">
        <input type="hidden" name="action" value="register">
        <label>Username</label><input name="username" required>
        <label>Password</label><input type="password" name="password" required>
        <div style="height:8px"></div>
        <button class="btn ghost">Register</button>
      </form>
    </div>
  <?php else: ?>
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <h3>Products</h3>
        <div><a class="btn" href="admin.php?logout=1">Logout</a></div>
      </div>
      <iframe src="admin_actions.php" style="width:100%;height:420px;border:0;margin-top:12px"></iframe>
    </div>
  <?php endif; ?>
</div>
</body></html>
