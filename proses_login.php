<?php
include 'db_config.php';

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $password = $_POST['password'];

    // Cek apakah username ada di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Password benar, login berhasil
            session_start(); // Memulai session untuk menyimpan informasi pengguna
            $_SESSION['username'] = $row['username']; // Simpan username ke session
            $_SESSION['userID'] = $row['id'];
            header("Location: index.php"); // Arahkan user ke halaman index.php setelah login
            exit();
        } else {
            // Password salah
            echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
        }
    } else {
        // Username tidak ditemukan
        echo "<script>alert('Username tidak ditemukan!'); window.location.href='login.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
