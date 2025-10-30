<?php
require 'db.php';
session_start();
// cart stored in session: $_SESSION['cart'] = [id => ['qty'=>n, 'title'=>..., 'price'=>...]]
$action = $_POST['action'] ?? $_GET['action'] ?? null;
if($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST'){
  $id = (int)$_POST['id']; $qty = max(1,(int)$_POST['qty']);
  $stmt = $mysqli->prepare('SELECT id,title,price,image FROM products WHERE id=? LIMIT 1'); $stmt->bind_param('i',$id); $stmt->execute(); $r=$stmt->get_result(); $p=$r->fetch_assoc();
  if($p){
    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if(isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id]['qty'] += $qty;
    else $_SESSION['cart'][$id] = ['qty'=>$qty,'title'=>$p['title'],'price'=>$p['price'],'image'=>$p['image']];
  }
  header('Location: cart.php'); exit;
}
if($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST'){
  foreach($_POST['qty'] as $id => $q){ $id=(int)$id; $q=(int)$q; if($q<=0) unset($_SESSION['cart'][$id]); else $_SESSION['cart'][$id]['qty']=$q; }
  header('Location: cart.php'); exit;
}
if($action === 'clear'){ unset($_SESSION['cart']); header('Location: cart.php'); exit; }
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="styles.css"><title>Cart</title></head><body>
<div class="container">
  <a href="index.php">← Continue shopping</a>
  <h1>Your cart</h1>
  <?php if(empty($_SESSION['cart'])): ?>
    <p class="muted">Your cart is empty.</p>
  <?php else: ?>
    <form method="post" action="cart.php">
      <input type="hidden" name="action" value="update">
      <table class="cart-table">
        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
          <?php $total=0; foreach($_SESSION['cart'] as $id=>$item): $subtotal = $item['price']*$item['qty']; $total += $subtotal; ?>
          <tr>
            <td><img src="<?php echo htmlspecialchars($item['image']?:'uploads/placeholder.png'); ?>" style="width:64px;height:48px;object-fit:cover"> <?php echo htmlspecialchars($item['title']); ?></td>
            <td>₹<?php echo number_format($item['price'],2); ?></td>
            <td><input type="number" name="qty[<?php echo $id; ?>]" value="<?php echo $item['qty']; ?>" min="0" style="width:72px"></td>
            <td>₹<?php echo number_format($subtotal,2); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="cart-actions">
        <button class="btn" type="submit">Update cart</button>
        <a class="btn ghost" href="cart.php?action=clear">Clear cart</a>
        <a class="btn" href="checkout.php">Proceed to checkout (₹<?php echo number_format($total,2); ?>)</a>
      </div>
    </form>
  <?php endif; ?>
</div>
</body></html>
