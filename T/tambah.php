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

$member = mysqli_query($conn,"SELECT * FROM tb_member");

$paket = mysqli_query($conn,"
SELECT tb_paket.*, tb_outlet.nama AS nama_outlet
FROM tb_paket
JOIN tb_outlet ON tb_paket.id_outlet = tb_outlet.id
");

if(isset($_POST['simpan'])){
    $id_member = $_POST['id_member'];
    $id_paket = $_POST['id_paket'];
    $qty = $_POST['qty'];
    $id_user = $_SESSION['id_user'];

    $data_paket = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM tb_paket WHERE id='$id_paket'"));
    $id_outlet = $data_paket['id_outlet'];
    $invoice = "INV".time();

    mysqli_query($conn,"
    INSERT INTO tb_transaksi
    (id_outlet,kode_invoice,id_member,tgl,batas_waktu,tgl_bayar,biaya_tambahan,diskon,pajak,status,dibayar,id_user)
    VALUES
    ('$id_outlet','$invoice','$id_member',NOW(),DATE_ADD(NOW(), INTERVAL 3 DAY),NULL,0,0,0,'baru','belum_dibayar','$id_user')
    ");

    $id_transaksi = mysqli_insert_id($conn);

    mysqli_query($conn,"
    INSERT INTO tb_detail_transaksi
    (id_transaksi,id_paket,qty,keterangan)
    VALUES
    ('$id_transaksi','$id_paket','$qty','Transaksi Laundry')
    ");

    header("Location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Transaksi</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:'Segoe UI';background:#eef3f8;padding:30px}
.card{max-width:650px;margin:auto;background:white;padding:25px;border-radius:20px;box-shadow:0 10px 25px rgba(0,0,0,.08)}
select,input{width:100%;padding:13px;border:1px solid #ddd;border-radius:12px;margin-bottom:12px}
button,.back{width:100%;padding:13px;border:none;border-radius:12px;color:white;text-decoration:none;display:block;text-align:center;margin-top:10px}
button{background:linear-gradient(135deg,#3498db,#2c3e50)}
.back{background:#e74c3c}
</style>
</head>
<body>

<div class="card">
<h2>🧺 Tambah Transaksi</h2>

<form method="POST">
<select name="id_member" required>
<option value="">-- Pilih Member --</option>
<?php while($m=mysqli_fetch_assoc($member)){ ?>
<option value="<?= $m['id']; ?>"><?= $m['nama']; ?></option>
<?php } ?>
</select>

<select name="id_paket" required>
<option value="">-- Pilih Paket --</option>
<?php while($p=mysqli_fetch_assoc($paket)){ ?>
<option value="<?= $p['id']; ?>">
<?= $p['nama_paket']; ?> - <?= $p['nama_outlet']; ?> - Rp <?= number_format($p['harga']); ?>
</option>
<?php } ?>
</select>

<input type="number" name="qty" min="1" placeholder="Jumlah / Kg" required>

<button type="submit" name="simpan">Simpan Transaksi</button>
</form>

<a href="<?= kembali(); ?>" class="back">Kembali</a>

</div>
</body>
</html>