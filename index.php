<?php
require 'db.php';
// Simple product listing with add-to-cart (session)
session_start();
$res = $mysqli->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>StatModeller Shop</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header class="site-header">
    <div class="container">
      <h1>StatModeller Shop</h1>
      <nav>
        <a href="index.php">Shop</a>
        <a href="cart.php">Cart (<?php echo isset($_SESSION['cart'])?array_sum(array_column($_SESSION['cart'],'qty')):0; ?>)</a>
        <a href="admin.php">Admin</a>
      </nav>
    </div>
  </header>
  <main class="container">
    <section class="grid">
      <?php foreach($products as $p): ?>
        <article class="card">
          <img src="<?php echo htmlspecialchars($p['image'] ?: 'uploads/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($p['title']); ?>">
          <h2><?php echo htmlspecialchars($p['title']); ?></h2>
          <p class="muted"><?php echo htmlspecialchars($p['short_desc']); ?></p>
          <div class="price">₹<?php echo number_format($p['price'],2); ?></div>
          <div class="actions">
            <a class="btn ghost" href="product.php?id=<?php echo $p['id']; ?>">View</a>
            <form method="post" action="cart.php" style="display:inline-block">
              <input type="hidden" name="action" value="add">
              <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
              <input type="number" name="qty" value="1" min="1" style="width:72px">
              <button class="btn">Add to cart</button>
            </form>
          </div>
        </article>
      <?php endforeach; ?>
    </section>
  </main>
  <script src="app.js"></script>
</body>
</html>
