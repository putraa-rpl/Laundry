<?php
session_start();
include "../koneksi.php";

if(!isset($_SESSION['role'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "admin" && $_SESSION['role'] != "kasir"){
    die("Akses ditolak");
}

function kembali(){
    if($_SESSION['role']=="admin") return "../admin.php";
    if($_SESSION['role']=="kasir") return "../kasir.php";
    if($_SESSION['role']=="owner") return "../owner.php";
    return "../login.php";
}

$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM tb_transaksi WHERE id='$id'
"));

if(isset($_POST['update'])){
    $status = $_POST['status'];
    $dibayar = $_POST['dibayar'];
    $tgl_bayar = ($dibayar == "dibayar") ? "NOW()" : "NULL";

    mysqli_query($conn,"
    UPDATE tb_transaksi SET
    biaya_tambahan='$_POST[biaya_tambahan]',
    pajak='$_POST[pajak]',
    diskon='$_POST[diskon]',
    status='$status',
    dibayar='$dibayar',
    tgl_bayar=$tgl_bayar
    WHERE id='$id'
    ");

    header("Location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Proses Transaksi</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:'Segoe UI';background:#eef3f8;padding:30px}
.card{max-width:600px;margin:auto;background:white;padding:25px;border-radius:20px;box-shadow:0 10px 25px rgba(0,0,0,.08)}
input,select{width:100%;padding:13px;border:1px solid #ddd;border-radius:12px;margin-bottom:12px}
button,.back{width:100%;padding:13px;border:none;border-radius:12px;color:white;text-decoration:none;display:block;text-align:center;margin-top:10px}
button{background:#f39c12}
.back{background:#e74c3c}
</style>
</head>
<body>

<div class="card">
<h2>🧺 Proses Transaksi</h2>

<form method="POST">
<label>Biaya Tambahan</label>
<input type="number" name="biaya_tambahan" value="<?= $data['biaya_tambahan']; ?>" required>

<label>Pajak</label>
<input type="number" name="pajak" value="<?= $data['pajak']; ?>" required>

<label>Diskon (%)</label>
<input type="number" name="diskon" value="<?= $data['diskon']; ?>" required>

<label>Status Laundry</label>
<select name="status">
<option value="baru" <?= $data['status']=="baru" ? "selected" : ""; ?>>Baru</option>
<option value="proses" <?= $data['status']=="proses" ? "selected" : ""; ?>>Proses</option>
<option value="selesai" <?= $data['status']=="selesai" ? "selected" : ""; ?>>Selesai</option>
<option value="diambil" <?= $data['status']=="diambil" ? "selected" : ""; ?>>Diambil</option>
</select>

<label>Status Pembayaran</label>
<select name="dibayar">
<option value="belum_dibayar" <?= $data['dibayar']=="belum_dibayar" ? "selected" : ""; ?>>Belum Dibayar</option>
<option value="dibayar" <?= $data['dibayar']=="dibayar" ? "selected" : ""; ?>>Dibayar</option>
</select>

<button type="submit" name="update">Update Transaksi</button>
</form>

<a href="<?= kembali(); ?>" class="back">Kembali</a>

</div>
</body>
</html>