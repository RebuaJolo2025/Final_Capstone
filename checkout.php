<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$total = 0;
$items = [];

/* ✅ Get only selected items */
if (isset($_POST['selected_items']) && is_array($_POST['selected_items'])) {
    $selected = $_POST['selected_items'];
    $quantities = $_POST['quantities'] ?? [];

    // Build placeholders (?, ?, ?) for SQL IN()
    $placeholders = implode(",", array_fill(0, count($selected), "?"));
    $types = str_repeat("i", count($selected)); // all integers (cart IDs)

    $sql = "SELECT id, product_name, product_price FROM cart WHERE id IN ($placeholders) AND email=?";
    $stmt = $conn->prepare($sql);

    // bind dynamically
    $params = array_merge($selected, [$email]);
    $bind_types = $types . "s";
    $stmt->bind_param($bind_types, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cid = $row['id'];
        $qty = isset($quantities[$cid]) ? intval($quantities[$cid]) : 1;
        if ($qty < 1) $qty = 1;

        $subtotal = $row['product_price'] * $qty;
        $total += $subtotal;
        $items[] = [
            'id' => $cid,
            'name' => $row['product_name'],
            'price' => $row['product_price'],
            'quantity' => $qty,
            'subtotal' => $subtotal
        ];
    }
    $stmt->close();
}

if (empty($items)) {
    echo "<p>No items selected for checkout. <a href='cart.php'>Back to cart</a></p>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout Summary</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        .container { max-width: 900px; margin: 50px auto; background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0px 5px 20px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2e7d32; margin-bottom: 25px; }
        ul { list-style: none; padding: 0; margin-bottom: 20px; }
        ul li { background: #f9f9f9; margin-bottom: 10px; padding: 12px; border-radius: 6px; display: flex; justify-content: space-between; }
        .total { font-size: 18px; font-weight: bold; text-align: right; margin-top: 10px; color: #222; }
        button { background: #2e7d32; color: white; padding: 12px 20px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; margin-top: 10px; transition: 0.3s; }
        button:hover { background: #256428; }
        .back-link { display: inline-block; margin-top: 15px; text-decoration: none; color: #2196F3; font-weight: bold; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
<h2>Checkout Summary</h2>
<ul>
<?php foreach ($items as $item): ?>
    <li>
        <span><?= htmlspecialchars($item['name']) ?> x <?= $item['quantity'] ?></span>
        <span>₱<?= number_format($item['subtotal'], 2) ?></span>
    </li>
<?php endforeach; ?>
</ul>
<p class="total">Total: ₱<?= number_format($total, 2) ?></p>

<form method="POST" action="place_order.php">
    <?php foreach ($items as $item): ?>
        <input type="hidden" name="selected_items[]" value="<?= $item['id'] ?>">
        <input type="hidden" name="quantities[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>">
    <?php endforeach; ?>
    <button type="submit">Place Order</button>
</form>

<a href="cart.php" class="back-link">← Back to Cart</a>
</div>
</body>
</html>
