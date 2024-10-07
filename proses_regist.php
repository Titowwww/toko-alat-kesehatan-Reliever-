<?php
include 'db_config.php';

//Post data dari form
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password untuk keamanan
$email = $_POST['email'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$address = $_POST['address'];
$contact = $_POST['contact'];
$paypal_id = $_POST['paypal'];

// query untuk memasukkan data
$sql = "INSERT INTO users (username, password, email, dob, gender, address, contact, paypal_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $username, $password, $email, $dob, $gender, $address, $contact, $paypal_id);

// Eksekusi query
if ($stmt->execute()) {
    header("Location: index.php");  // Arahkan user ke halaman home setelah login
    exit();
} else {
    echo "Terjadi kesalahan: " . $stmt->error;
}

// Menutup koneksi
$stmt->close();
$conn->close();
?>
