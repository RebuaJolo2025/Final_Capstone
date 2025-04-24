<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <header id="product-header">
        <h1>Product Details</h1>
        <a href="index.php" class="back-link">Back to Homepage</a>
    </header>

    <div id="product-container">
        <?php
        // Include database connection
        include 'conn.php';
        
        // Get product ID from URL
        $product_id = $_GET['product_id'];
        
        // Simple query (without prepared statement for simplicity)
        $query = "SELECT * FROM shop WHERE product_id = $product_id";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
            ?>
            <div class="product-detail">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                <div class="product-info">
                    <h2><?php echo $product['name']; ?></h2>
                    <p><strong>Price:</strong> <?php echo $product['price']; ?></p>
                    <p><strong>Description:</strong> <?php echo $product['description']; ?></p>

                    <!-- Button container to align both buttons -->
                    <div class="product-buttons">
                        <a href="purchase.php?product_id=<?php echo $product['product_id'] ?>" class="buy-now">Buy Now</a>

                        <!-- Add to Cart Form -->
                        <form action="process.php" method="post" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                            <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                            <input type="hidden" name = "image" value = "<?php echo $product['image'] ?>">
                            <button type="submit" name="addcart" class="add-to-cart">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        } else {
            echo "<p>Product not found.</p>";
        }
        
        // Close connection
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>
