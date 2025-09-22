<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['email'])) {
    echo "<script>alert('Login first'); window.location.href='login.php';</script>";
    exit;
}

$email = $_SESSION['email'];

/* ✅ Handle Add to Cart */
if (isset($_GET['add']) && is_numeric($_GET['add'])) {
    $product_id = intval($_GET['add']);
    $qty = isset($_GET['qty']) && is_numeric($_GET['qty']) ? intval($_GET['qty']) : 1;

    $sql = "SELECT * FROM products WHERE id='$product_id' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $pname = $row['name'];
        $pprice = $row['price'];
        $pimage = "Admin/Product/" . json_decode($row['images'], true)[0]['url'];

        $check = mysqli_query($conn, "SELECT * FROM cart WHERE email='$email' AND product_id='$product_id'");
        if (mysqli_num_rows($check) > 0) {
            mysqli_query($conn, "UPDATE cart SET quantity = quantity + $qty 
                                 WHERE email='$email' AND product_id='$product_id'");
        } else {
            mysqli_query($conn, "INSERT INTO cart (email, product_id, product_name, product_price, image, quantity) 
                                 VALUES ('$email','$product_id','$pname','$pprice','$pimage','$qty')");
        }
    }
    header("Location: cart.php");
    exit;
}

/* ✅ Handle AJAX Quantity Update */
if (isset($_POST['update_qty'])) {
    $cart_id = intval($_POST['cart_id']);
    $new_qty = max(1, intval($_POST['new_qty']));
    mysqli_query($conn, "UPDATE cart SET quantity=$new_qty WHERE id=$cart_id AND email='$email'");
    echo "success";
    exit;
}

/* ✅ Handle Delete Item */
if (isset($_POST['delete_item'])) {
    $cart_id = intval($_POST['delete_item']);
    mysqli_query($conn, "DELETE FROM cart WHERE id=$cart_id AND email='$email'");
    echo "deleted";
    exit;
}

$query = "SELECT * FROM cart WHERE email='$email'";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        header { background: #2e7d32; padding: 15px 30px; color: #fff; display: flex; justify-content: space-between; align-items: center; }
        header h1 { margin: 0; font-size: 22px; }
        header a { color: #fff; text-decoration: none; font-weight: bold; }
        .cart-container { max-width: 950px; margin: 30px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0px 5px 15px rgba(0,0,0,0.08); }
        h2 { text-align: center; color: #2e7d32; margin-bottom: 20px; }
        form { margin: 0; }
        .cart-item { display: flex; align-items: flex-start; gap: 15px; padding: 15px; border-bottom: 1px solid #eee; }
        .cart-item img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; }
        .cart-details { flex: 1; }
        .cart-details h3 { margin: 0 0 5px; font-size: 18px; }
        .price { color: #2e7d32; font-size: 1.1em; font-weight: bold; }
        .quantity-control { margin-top: 10px; display: flex; align-items: center; gap: 5px; }
        .quantity-control button { width: 28px; height: 28px; font-size: 18px; font-weight: bold; background: #2e7d32; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        .quantity-control input { width: 45px; text-align: center; font-size: 1em; border: 1px solid #ccc; border-radius: 5px; }
        .select-box { margin-right: 10px; }
        .remove-btn { background: #e53935; color: #fff; border: none; border-radius: 5px; padding: 6px 12px; cursor: pointer; margin-top: 10px; }
        .remove-btn:hover { background: #c62828; }
        .total { text-align: right; font-size: 1.3em; margin-top: 20px; font-weight: bold; color: #2e7d32; }
        .checkout-btn { display: block; width: 220px; margin: 20px auto; background: #2e7d32; color: #fff; padding: 12px; text-align: center; border-radius: 6px; font-size: 1.1em; text-decoration: none; border: none; cursor: pointer; }
        .checkout-btn:hover { background: #256628; }
    </style>
</head>
<body>

<header>
    <h1>🛒 Your Cart</h1>
    <a href="index.php">⬅ Back to Shop</a>
</header>

<div class="cart-container">
    <h2>Select Items to Checkout</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <form method="POST" action="checkout.php" onsubmit="return validateSelection();">
            <?php while ($row = mysqli_fetch_assoc($result)): 
                $id = $row['id'];
                $name = $row['product_name'];
                $price = $row['product_price'];
                $image = $row['image'];
                $qty = $row['quantity'];
            ?>
                <div class="cart-item" data-id="<?= $id ?>">
                    <input type="checkbox" class="select-box" name="selected_items[]" value="<?= $id ?>">
                    <img src="<?= $image ?>" alt="<?= $name ?>">
                    <div class="cart-details">
                        <h3><?= $name ?></h3>
                        <p class="price">₱<?= number_format($price, 2) ?></p>
                        <div class="quantity-control">
                            <button type="button" class="decrease">−</button>
                            <input type="number" value="<?= $qty ?>" min="1" class="quantity" name="quantities[<?= $id ?>]" data-price="<?= $price ?>">
                            <button type="button" class="increase">+</button>
                        </div>
                        <button type="button" class="remove-btn" onclick="removeItem(<?= $id ?>)">🗑 Remove</button>
                    </div>
                </div>
            <?php endwhile; ?>

            <div class="total">Total: ₱<span id="total-price">0.00</span></div>
            <button type="submit" class="checkout-btn">Proceed to Checkout</button>
        </form>
    <?php else: ?>
        <p>No items in your cart.</p>
    <?php endif; ?>
</div>

<script>
function updateTotal() {
    let total = 0;
    document.querySelectorAll('.cart-item').forEach(item => {
        let checkbox = item.querySelector('.select-box');
        if (checkbox && checkbox.checked) {
            let qty = parseInt(item.querySelector('.quantity').value);
            let price = parseFloat(item.querySelector('.quantity').dataset.price);
            total += qty * price;
        }
    });
    document.getElementById('total-price').textContent = total.toFixed(2);
}

function updateQty(cartId, newQty) {
    fetch("cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "update_qty=1&cart_id=" + cartId + "&new_qty=" + newQty
    });
}

function removeItem(cartId) {
    if (!confirm("Remove this item from cart?")) return;
    fetch("cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "delete_item=" + cartId
    }).then(res => res.text()).then(data => {
        if (data.trim() === "deleted") {
            document.querySelector('.cart-item[data-id="'+cartId+'"]').remove();
            updateTotal();
        }
    });
}

function validateSelection() {
    let checked = document.querySelectorAll('.select-box:checked');
    if (checked.length === 0) {
        alert("⚠ Please select at least one item before proceeding to checkout.");
        return false;
    }
    return true;
}

document.querySelectorAll('.increase').forEach(btn => {
    btn.addEventListener('click', function () {
        let item = this.closest('.cart-item');
        let input = item.querySelector('.quantity');
        let newVal = parseInt(input.value) + 1;
        input.value = newVal;
        updateQty(item.dataset.id, newVal);
        updateTotal();
    });
});

document.querySelectorAll('.decrease').forEach(btn => {
    btn.addEventListener('click', function () {
        let item = this.closest('.cart-item');
        let input = item.querySelector('.quantity');
        let newVal = parseInt(input.value) - 1;
        if (newVal < 1) newVal = 1;
        input.value = newVal;
        updateQty(item.dataset.id, newVal);
        updateTotal();
    });
});

document.querySelectorAll('.quantity').forEach(input => {
    input.addEventListener('change', function () {
        let item = this.closest('.cart-item');
        let newVal = parseInt(this.value);
        if (newVal < 1) newVal = 1;
        this.value = newVal;
        updateQty(item.dataset.id, newVal);
        updateTotal();
    });
});

document.querySelectorAll('.select-box').forEach(chk => {
    chk.addEventListener('change', updateTotal);
});

updateTotal();
</script>
</body>
</html>
