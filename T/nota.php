<?php
session_start();
include "../koneksi.php";

if(!isset($_SESSION['role'])){
    header("Location: ../login.php");
    exit();
}

function kembali(){
    if($_SESSION['role']=="admin") return "../admin.php";
    if($_SESSION['role']=="kasir") return "../kasir.php";
    if($_SESSION['role']=="owner") return "../owner.php";
    return "../login.php";
}

$id = $_GET['id'];

$data = mysqli_query($conn,"
SELECT tb_transaksi.*, tb_member.nama AS nama_member, tb_member.alamat, tb_member.tlp,
tb_paket.nama_paket, tb_paket.harga, tb_detail_transaksi.qty
FROM tb_transaksi
JOIN tb_member ON tb_transaksi.id_member = tb_member.id
JOIN tb_detail_transaksi ON tb_transaksi.id = tb_detail_transaksi.id_transaksi
JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id
WHERE tb_transaksi.id='$id'
");

$d = mysqli_fetch_assoc($data);

$subtotal = $d['harga'] * $d['qty'];
$diskon_rupiah = ($subtotal * $d['diskon']) / 100;
$total = $subtotal + $d['biaya_tambahan'] + $d['pajak'] - $diskon_rupiah;
?>

<!DOCTYPE html>
<html>
<head>
<title>Nota Laundry</title>
<style>
body{font-family:'Segoe UI';background:#eef3f8;padding:30px}
.nota{width:420px;margin:auto;background:white;padding:25px;border-radius:20px;box-shadow:0 10px 25px rgba(0,0,0,.08)}
h2{text-align:center}
table{width:100%}
td{padding:7px}
.total{font-size:22px;font-weight:bold;color:#203a43}
.btn{display:block;text-align:center;padding:12px;margin-top:10px;text-decoration:none;color:white;border-radius:10px}
.print{background:#2ecc71}.back{background:#e74c3c}
@media print{.btn{display:none}body{background:white}.nota{box-shadow:none}}
</style>
</head>
<body>

<div class="nota">
<h2>🧺 GoLaundry</h2>
<hr>

<table>
<tr><td>Invoice</td><td>: <?= $d['kode_invoice']; ?></td></tr>
<tr><td>Nama</td><td>: <?= $d['nama_member']; ?></td></tr>
<tr><td>Paket</td><td>: <?= $d['nama_paket']; ?></td></tr>
<tr><td>Harga</td><td>: Rp <?= number_format($d['harga']); ?></td></tr>
<tr><td>Qty</td><td>: <?= $d['qty']; ?></td></tr>
<tr><td>Subtotal</td><td>: Rp <?= number_format($subtotal); ?></td></tr>
<tr><td>Pajak</td><td>: Rp <?= number_format($d['pajak']); ?></td></tr>
<tr><td>Tambahan</td><td>: Rp <?= number_format($d['biaya_tambahan']); ?></td></tr>
<tr><td>Diskon</td><td>: <?= $d['diskon']; ?>%</td></tr>
<tr><td>Status</td><td>: <?= $d['status']; ?></td></tr>
<tr><td>Pembayaran</td><td>: <?= $d['dibayar']; ?></td></tr>
</table>

<hr>

<p class="total">Total : Rp <?= number_format($total); ?></p>

<a href="#" onclick="window.print()" class="btn print">Cetak Nota</a>
<a href="<?= kembali(); ?>" class="btn back">Kembali</a>

</div>
</body>
</html>