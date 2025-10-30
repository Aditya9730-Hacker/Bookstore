<?php
require 'db.php';
session_start();
// Demo checkout: capture minimal details then clear cart
$message = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? '');
  if(!$name || !$email) $message = 'Name and email required.';
  else {
    // In real app: save order to DB, process payment gateway, send confirmation email
    $message = 'Order received. (Demo) Thank you, ' . htmlspecialchars($name) . '.';
    unset($_SESSION['cart']);
  }
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="styles.css"><title>Checkout</title></head><body>
<div class="container">
  <a href="cart.php">← Back to cart</a>
  <h1>Checkout</h1>
  <?php if($message): ?><div class="card muted"><?php echo $message; ?></div><?php else: ?>
  <form method="post">
    <label>Name</label><input name="name" required>
    <label>Email</label><input name="email" type="email" required>
    <label>Notes (optional)</label><textarea name="notes"></textarea>
    <div style="height:8px"></div>
    <button class="btn">Place order (demo)</button>
  </form>
  <?php endif; ?>
</div>
</body></html>
