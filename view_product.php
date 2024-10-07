<?php
session_start(); // Memulai session 
include 'db_config.php';

// Cek apakah ID produk ada di URL
if (isset($_GET['id'])) {
    $productId = intval($_GET['id']); // Mengambil ID produk dan memastikan itu adalah integer

    // Mengambil detail produk dari database
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek produk
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Produk tidak ditemukan.";
        exit();
    }
} else {
    echo "ID produk tidak ditentukan.";
    exit();
}

// Hitung jumlah item di cart
$cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; // Jumlah total item di cart
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Detail Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .product-img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
        .product-title {
            font-size: 24px;
            margin: 10px 0;
        }
        .product-price {
            color: green;
            font-size: 20px;
            margin: 10px 0;
        }
        .product-description {
            margin: 20px 0;
        }
        .back-button, .add-to-cart, .cart-button, .home-button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px; 
        }
        .back-button:hover, .add-to-cart:hover, .cart-button:hover, .home-button:hover {
            background-color: #0056b3;
        }
        .cart-button, .home-button {
            position: absolute; 
            top: 10px;
        }
        .cart-button {
            right: 10px; 
        }
        .home-button {
            right: 120px; 
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h2>
    <img class="product-img" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
    <p class="product-price">Harga: Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p> <!-- Jika ada kolom deskripsi -->
    
    <button class="add-to-cart" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>)">Add to Cart</button>
    <a href="index.php"><button class="back-button">Kembali ke Daftar Produk</button></a>
    
    <a href="cart.php"><button class="cart-button">Cart (<?php echo $cartCount; ?>)</button></a>
    <a href="index.php"><button class="home-button">Home</button></a> <!-- Tombol Home -->
</div>

<script>
    function addToCart(productId) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'cart.php?action=add&id=' + productId, true);
        xhr.onload = function() {
            if (this.status === 200) {
                alert('Produk berhasil ditambahkan ke keranjang!');
                updateCartCount(); // Memperbarui jumlah cart setelah berhasil menambahkan produk
            } else {
                alert('Terjadi kesalahan saat menambahkan produk ke keranjang.');
            }
        };
        xhr.send();
    }

    function updateCartCount() {
        // Hitung jumlah total item dalam keranjang
        const cartCount = <?php echo json_encode($cartCount); ?>;
        const newCount = cartCount + 1;
        document.querySelector('.cart-button').textContent = 'Cart (' + newCount + ')'; // Update tampilan tombol Cart
    }
</script>

</body>
</html>

<?php
// Menutup koneksi
$stmt->close();
$conn->close();
?>
