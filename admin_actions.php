<?php
// admin_actions.php - shows product management and handles uploads via POST to same file
require 'db.php';
session_start();
if(!isset($_SESSION['admin_id'])) { echo '<div class="muted">Please login via <a href="admin.php">admin page</a>.</div>'; exit; }
$msg = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $action = $_POST['a'] ?? '';
  if($action === 'create' || $action === 'update'){
    $title = $_POST['title'] ?? ''; $short = $_POST['short'] ?? ''; $desc = $_POST['description'] ?? ''; $price = (float)$_POST['price']; $cat = $_POST['category'] ?? '';
    $imagePath = '';
    if(!empty($_FILES['image']['tmp_name'])){
      $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
      $fname = 'uploads/' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
      if(move_uploaded_file($_FILES['image']['tmp_name'], $fname)) $imagePath = $fname;
    }
    if($action === 'create'){
      $stmt = $mysqli->prepare('INSERT INTO products (title,short_desc,description,price,category,image) VALUES (?,?,?,?,?,?)');
      $stmt->bind_param('sssdds',$title,$short,$desc,$price,$cat,$imagePath); $stmt->execute(); $msg='Created';
    } else {
      $id = (int)$_POST['id'];
      if($imagePath){
        $stmt = $mysqli->prepare('UPDATE products SET title=?,short_desc=?,description=?,price=?,category=?,image=? WHERE id=?');
        $stmt->bind_param('sssds si',$title,$short,$desc,$price,$cat,$imagePath,$id);
      } else {
        $stmt = $mysqli->prepare('UPDATE products SET title=?,short_desc=?,description=?,price=?,category=? WHERE id=?');
        $stmt->bind_param('sss dsi',$title,$short,$desc,$price,$cat,$id);
      }
      // simplified; ignore bind errors in demo
      $stmt->execute(); $msg='Updated';
    }
  } elseif($action === 'delete'){
    $id = (int)$_POST['id']; $stmt = $mysqli->prepare('DELETE FROM products WHERE id=?'); $stmt->bind_param('i',$id); $stmt->execute(); $msg='Deleted';
  }
}
$res = $mysqli->query('SELECT * FROM products ORDER BY created_at DESC');
?>
<!doctype html><html><head><meta charset="utf-8"><link rel="stylesheet" href="styles.css"></head><body>
<div class="muted"><?php echo htmlspecialchars($msg); ?></div>
<div style="display:flex;gap:12px;flex-wrap:wrap">
  <div style="flex:1;min-width:280px">
    <h4>Create product</h4>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="a" value="create">
      <label>Title</label><input name="title" required>
      <label>Short desc</label><input name="short">
      <label>Price</label><input name="price" type="number" step="0.01" value="0">
      <label>Category</label><input name="category">
      <label>Image</label><input name="image" type="file" accept="image/*">
      <div style="height:8px"></div>
      <button class="btn">Create</button>
    </form>
  </div>
  <div style="flex:1;min-width:320px">
    <h4>Existing</h4>
    <?php while($p = $res->fetch_assoc()): ?>
      <div class="card" style="display:flex;justify-content:space-between;align-items:center">
        <div style="display:flex;gap:8px;align-items:center">
          <img src="<?php echo htmlspecialchars($p['image']?:'uploads/placeholder.png'); ?>" style="width:80px;height:56px;object-fit:cover">
          <div><strong><?php echo htmlspecialchars($p['title']); ?></strong><div class="muted">₹<?php echo number_format($p['price'],2); ?></div></div>
        </div>
        <div style="display:flex;gap:6px">
          <form method="post" style="display:inline-block"><input type="hidden" name="a" value="delete"><input type="hidden" name="id" value="<?php echo $p['id']; ?>"><button class="btn ghost">Delete</button></form>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>
</body></html>
