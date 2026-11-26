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

$transaksi = mysqli_query($conn,"
    SELECT 
    tb_transaksi.id,
    tb_transaksi.kode_invoice,
    tb_member.nama AS nama_member

    FROM tb_transaksi

    LEFT JOIN tb_member 
    ON tb_transaksi.id_member = tb_member.id
");

$paket = mysqli_query($conn,"
    SELECT 
    tb_paket.*,
    tb_outlet.nama AS nama_outlet

    FROM tb_paket

    LEFT JOIN tb_outlet 
    ON tb_paket.id_outlet = tb_outlet.id
");

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
        INSERT INTO tb_detail_transaksi
        (
            id_transaksi,
            id_paket,
            qty,
            keterangan
        )
        VALUES
        (
            '$_POST[id_transaksi]',
            '$_POST[id_paket]',
            '$_POST[qty]',
            '$_POST[keterangan]'
        )
    ");

    header("Location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Detail Transaksi</title>

<style>
*{box-sizing:border-box}

body{
    margin:0;
    font-family:'Segoe UI';
    background:#eef3f8;
    padding:30px;
}

.card{
    max-width:600px;
    margin:auto;
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

h2{
    color:#203a43;
    margin-top:0;
}

input,select,textarea{
    width:100%;
    padding:12px;
    margin:8px 0;
    border:1px solid #ddd;
    border-radius:10px;
}

button,.back{
    display:block;
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    text-align:center;
    text-decoration:none;
    color:white;
    margin-top:10px;
}

button{
    background:linear-gradient(135deg,#3498db,#2c3e50);
}

.back{
    background:#e74c3c;
}
</style>
</head>

<body>

<div class="card">

<h2>Tambah Detail Transaksi</h2>

<form method="POST">

<select name="id_transaksi" required>
    <option value="">Pilih Invoice</option>

    <?php while($t=mysqli_fetch_assoc($transaksi)){ ?>
        <option value="<?= $t['id']; ?>">
            <?= $t['kode_invoice']; ?> - <?= $t['nama_member']; ?>
        </option>
    <?php } ?>
</select>

<select name="id_paket" required>
    <option value="">Pilih Paket</option>

    <?php while($p=mysqli_fetch_assoc($paket)){ ?>
        <option value="<?= $p['id']; ?>">
            <?= $p['nama_paket']; ?> - <?= $p['nama_outlet']; ?> - Rp <?= number_format($p['harga']); ?>
        </option>
    <?php } ?>
</select>

<input type="number" name="qty" min="1" placeholder="Qty / Jumlah" required>

<textarea name="keterangan" placeholder="Keterangan"></textarea>

<button name="simpan">Simpan Detail</button>

</form>

<a href="<?= kembali(); ?>" class="back">Kembali</a>

</div>

</body>
</html>