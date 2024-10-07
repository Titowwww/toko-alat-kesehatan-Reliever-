<?php
session_start();

require('C:\xampp\htdocs\ujikom\tcpdf\tcpdf.php');
include 'db_config.php';

// Ambil userID dari session
$userId = $_SESSION['userID'] ?? null; // Ambil dari session
$username = $_SESSION['username'] ?? 'Unknown'; // Ambil username untuk menampilkan

if (!$userId) {
    echo "User ID tidak ditemukan!";
    exit;
}

// Query untuk mengambil data pengguna berdasarkan userID
$stmt = $conn->prepare("SELECT paypal_id, address, contact FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah data pengguna ditemukan
if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    $paypalId = $userData['paypal_id'];   
    $alamat = $userData['address'];      
    $noHp = $userData['contact'];         
} else {
    echo "Data pengguna tidak ditemukan!";
    exit;
}

// Variabel lainnya
$namaBank = 'Bank ABC';                    
$caraBayar = 'Transfer Bank';               
$tanggal = date('d-m-Y');                  

// Cek apakah keranjang ada
if (empty($_SESSION['cart'])) {
    echo "Keranjang kosong! Tidak ada barang untuk di-generate invoice.";
    exit;
}

// Buat instance TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Reliever');
$pdf->SetTitle('Invoice Pembelian');
$pdf->SetSubject('Invoice Pembelian Barang');

// Set header dan footer
$pdf->SetHeaderData('', 0, 'Invoice Pembelian', 'Reliever', [0, 64, 255], [0, 64, 128]);
$pdf->setFooterData([0, 64, 0], [0, 64, 128]);

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(15, 27, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 25);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('dejavusans', '', 12);

// Tulis informasi pengguna di atas invoice
$html = <<<EOD
<h1>Invoice Pembelian</h1>
<table cellpadding="4" cellspacing="0" border="0">
    <tr>
        <td><strong>User ID:</strong> $userId</td>
        <td><strong>Tanggal:</strong> $tanggal</td>
    </tr>
    <tr>
        <td><strong>Nama:</strong> $username</td>
        <td><strong>ID Paypal:</strong> $paypalId</td>
    </tr>
    <tr>
        <td><strong>Alamat:</strong> $alamat</td>
        <td><strong>Nama Bank:</strong> $namaBank</td>
    </tr>
    <tr>
        <td><strong>No. HP:</strong> $noHp</td>
        <td><strong>Cara Bayar:</strong> $caraBayar</td>
    </tr>
</table>
<br><br>
EOD;

$pdf->writeHTML($html, true, false, true, false, '');

// Buat tabel untuk barang di keranjang
$html = <<<EOD
<h3>Daftar Barang</h3>
<table cellpadding="5" cellspacing="0" border="1">
    <tr>
        <th width="45%"><strong>Nama Barang</strong></th>
        <th width="20%"><strong>Harga</strong></th>
        <th width="10%"><strong>Qty</strong></th>
        <th width="25%"><strong>Subtotal</strong></th>
    </tr>
EOD;

$totalBelanja = 0;

foreach ($_SESSION['cart'] as $id => $item) {
    $namaBarang = htmlspecialchars($item['name']);
    $harga = number_format($item['price'], 0, ',', '.');
    $qty = $item['quantity'];
    $subtotal = number_format($item['price'] * $qty, 0, ',', '.');

    $totalBelanja += $item['price'] * $qty;

    $html .= <<<EOD
    <tr>
        <td width="45%">$namaBarang</td>
        <td width="20%">Rp. $harga</td>
        <td width="10%">$qty</td>
        <td width="25%">Rp. $subtotal</td>
    </tr>
EOD;
}

// Total belanja
$totalBelanjaFormatted = number_format($totalBelanja, 0, ',', '.');
$html .= <<<EOD
<tr>
    <td colspan="3" align="right"><strong>Total Belanja:</strong></td>
    <td><strong>Rp. $totalBelanjaFormatted</strong></td>
</tr>
</table>
EOD;

// Output tabel ke dalam PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output file PDF
$pdf->Output('invoice.pdf', 'I');

?>
