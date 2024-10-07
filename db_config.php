<?php
$host = "localhost";    
$dbname = "lsp"; 
$username = "root";     
$password = "";        

// Membuat koneksi ke MySQL
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
