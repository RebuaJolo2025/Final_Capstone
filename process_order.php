<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $quantity = (int) $_POST['quantity'];

    // Optional: Validate product again
    $result = mysqli_query($conn, "SELECT * FROM shop WHERE product_id = $product_id");
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        $total = $product['price'] * $quantity;

        // Store order (assumes you have `orders` table)
        // You can insert into an orders table here...

        echo "<h1>Thank you for your order!</h1>";
        echo "<p>You ordered <strong>{$quantity}</strong> of <strong>{$product['name']}</strong>.</p>";
        echo "<p>Total Amount: <strong>Php {$total}</strong></p>";
    } else {
        echo "Invalid product.";
    }
} else {
    echo "Invalid request.";
}


?>
