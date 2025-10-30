<?php
require 'db.php';
session_start();
$id = isset($_GET['id'])? (int)$_GET['id'] : 0;
$stmt = $mysqli->prepare('SELECT * FROM products WHERE id = ? LIMIT 1');
$stmt->bind_param('i',$id);
$stmt->execute();
$res = $stmt->get_result();
$p = $res->fetch_assoc();
if(!$p) { header('Location: index.php'); exit; }
?>
<!doctype html>
<html><head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars($p['title']); ?></title>
  <link rel="stylesheet" href="styles.css">
</head><body>
  <div class="container">
    <a href="index.php">← Back to shop</a>
    <div class="product-detail">
      <div class="left">
        <img src="<?php echo htmlspecialchars($p['image'] ?: 'uploads/placeholder.png'); ?>" alt="">
      </div>
      <div class="right">
        <h1><?php echo htmlspecialchars($p['title']); ?></h1>
        <p class="muted"><?php echo htmlspecialchars($p['short_desc']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($p['description'])); ?></p>
        <div class="price">₹<?php echo number_format($p['price'],2); ?></div>
        <form method="post" action="cart.php">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
          <input type="number" name="qty" value="1" min="1">
          <button class="btn">Add to cart</button>
        </form>
      </div>
    </div>
  </div>
</body></html>
