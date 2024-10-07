<?php
session_start();
//echo '<pre>';
//print_r($_SESSION);
//echo '</pre>';
include 'db_config.php';

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fungsi untuk menambahkan item ke keranjang
function addToCart($id, $name, $price, $quantity) {
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += $quantity; // Jika sudah ada, tambahkan quantity
    } else {
        $_SESSION['cart'][$id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        ]; // Tambah item baru ke keranjang
    }
}

// Fungsi untuk mengurangi quantity
function decreaseQuantity($id) {
    if (isset($_SESSION['cart'][$id])) {
        if ($_SESSION['cart'][$id]['quantity'] > 1) {
            $_SESSION['cart'][$id]['quantity']--; // Kurangi quantity
        } else {
            unset($_SESSION['cart'][$id]); // Jika quantity = 1, hapus item
        }
    }
}

// Fungsi untuk menghitung total
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity']; // Hitung total harga
    }
    return $total;
}

// Proses aksi keranjang
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    // Mengambil data produk dari database
    $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($action === 'add' && $result->num_rows > 0) {
        $product = $result->fetch_assoc(); // Ambil data produk
        addToCart($id, $product['name'], $product['price'], 1); // Tambah item ke keranjang
    } elseif ($action === 'remove') {
        unset($_SESSION['cart'][$id]); // Hapus item dari keranjang
    } elseif ($action === 'decrease') {
        decreaseQuantity($id); // Kurangi quantity
    }
}

// Tambahkan proses untuk menghapus semua item di keranjang
if (isset($_GET['action']) && $_GET['action'] === 'remove_all') {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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
        .header button {
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #007bff; 
            color: #fff;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px; 
        }
        .header button:hover {
            background-color: #0056b3; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
        }
        .action-button {
            padding: 5px 10px;
            border-radius: 3px;
            background-color: #007bff; 
            color: #fff;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px; 
            margin: 0 5px; 
        }
        .action-button:hover {
            background-color: #0056b3; 
        }
        .remove-button {
            padding: 5px 10px;
            border-radius: 3px;
            background-color: #dc3545; 
            color: #fff;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px; 
        }
        .remove-button:hover {
            background-color: #c82333; 
        }
        .checkout-button {
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #28a745; 
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px; 
        }
        .checkout-button:hover {
            background-color: #218838; 
        }
        .clear-cart-button {
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }
        .clear-cart-button:hover {
            background-color: #c82333; 
        }
        .continue-button {
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #007bff; 
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }
        .continue-button:hover {
            background-color: #0056b3; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Shopping Cart</h2>
            <a href="index.php"><button>Back to Products</button></a>
        </div>

        <table>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
            <?php if (!empty($_SESSION['cart'])): ?>
                <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>Rp. <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                    <td>
                        <form action="" method="GET" style="display:inline;">
                            <input type="hidden" name="action" value="decrease">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <button type="submit" class="action-button">-</button>
                        </form>
                        <?php echo $item['quantity']; ?>
                        <form action="" method="GET" style="display:inline;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <button type="submit" class="action-button">+</button>
                        </form>
                    </td>
                    <td>Rp. <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></td>
                    <td><a href="?action=remove&id=<?php echo $id; ?>" class="remove-button">Remove</a></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No items in the cart.</td>
                </tr>
            <?php endif; ?>
        </table>

        <div class="total">
            Total: Rp. <?php echo number_format(calculateTotal(), 0, ',', '.'); ?>
        </div>

        <!-- Tambahkan tombol baru di bawah total -->
        <div>
            <form action="cart.php?action=remove_all" method="POST" style="display:inline;">
                <button type="submit" class="clear-cart-button">Hapus Keranjang</button>
            </form>
            <button class="checkout-button" onclick="showReceipt()">Pembayaran</button>
            <a href="index.php"><button class="continue-button">Lanjut Belanja</button></a>
        </div>
    </div>

    <script>
        function showReceipt() {
            alert("Terima kasih telah berbelanja, jangan lupa cetak struknya!");
            window.location.href = "pdf.php"; // Redirect ke pdf.php
        }
    </script>
</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
