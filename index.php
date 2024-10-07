<?php
session_start();

include 'db_config.php';

// Mengambil data produk dari database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Hitung jumlah item di cart
$cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }
        .header div {
            display: flex;
            align-items: center;
        }
        .header button, .header a {
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            margin-left: 10px;
        }
        .header button:hover, .header a:hover {
            background-color: #0056b3;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        .product {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .product img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        .product h3 {
            margin-bottom: 10px;
        }
        .product button {
            margin-top: 10px;
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .product button:hover {
            background-color: #0056b3;
        }
        .category {
            margin-top: 30px;
            text-align: center;
            background-color: #eee;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Product Page</h2>
        <div>
            <?php if (isset($_SESSION['username'])): ?>
                <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
                <a href="cart.php"><button>Cart (<?php echo $cartCount; ?>)</button></a>
                <a href="logout.php"><button>Log Out</button></a>
            <?php else: ?>
                <a href="login.php"><button>Login</button></a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Product Categories -->
    <div class="category">
        <h3>Alat Bantu</h3>
        <div class="product-grid">
            <?php
            if ($result && $result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    // Menyaring produk yang masuk dalam kategori "Alat Bantu"
                    if (strtolower($row['name']) === 'kursi roda'): ?>
                        <div class="product">
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p>Harga: Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                            <button onclick="viewProduct(<?php echo $row['id']; ?>)">View</button>
                            <button onclick="addToCart(<?php echo $row['id']; ?>)">Buy</button>
                        </div>
                    <?php endif;
                endwhile;
            endif;
            ?>
        </div>
    </div>

    <div class="category">
        <h3>Alat Cek Kesehatan</h3>
        <div class="product-grid">
            <?php
            if ($result && $result->num_rows > 0):
                // Reset hasil query (jika diperlukan)
                $result->data_seek(0);

                while ($row = $result->fetch_assoc()):
                    // Menyaring produk yang masuk dalam kategori "Alat Cek Kesehatan"
                    if (strtolower($row['name']) !== 'kursi roda'): ?>
                        <div class="product">
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p>Harga: Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                            <button onclick="viewProduct(<?php echo $row['id']; ?>)">View</button>
                            <button onclick="addToCart(<?php echo $row['id']; ?>)">Buy</button>
                        </div>
                    <?php endif;
                endwhile;
            endif;
            ?>
        </div>
    </div>

</div>

<script>
    function viewProduct(productId) {
        <?php if (isset($_SESSION['username'])): ?>
            window.location.href = "view_product.php?id=" + productId;
        <?php else: ?>
            window.location.href = "login.php";
        <?php endif; ?>
    }

    function addToCart(productId) {
        <?php if (isset($_SESSION['username'])): ?>
            window.location.href = "cart.php?action=add&id=" + productId;
        <?php else: ?>
            window.location.href = "login.php";
        <?php endif; ?>
    }
</script>

</body>
</html>
