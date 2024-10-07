<?php
session_start();
session_destroy(); // Hapus semua sesi
header("Location: index.php"); // Arahkan ke halaman login setelah log out
exit();
?>
