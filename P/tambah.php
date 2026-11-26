<?php
session_start();
include "../koneksi.php";

if($_SESSION['role'] != "admin"){
    die("Akses ditolak");
}

$outlet = mysqli_query($conn,"
    SELECT * FROM tb_outlet
");

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
        INSERT INTO tb_paket
        (
            id_outlet,
            jenis,
            nama_paket,
            harga
        )

        VALUES

        (
            '$_POST[id_outlet]',
            '$_POST[jenis]',
            '$_POST[nama_paket]',
            '$_POST[harga]'
        )
    ");

    header("Location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Paket</title>

<style>

*{box-sizing:border-box}

body{
    margin:0;
    font-family:'Segoe UI';
    background:#eef3f8;
    padding:30px;
}

.card{
    max-width:550px;
    margin:auto;
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

h2{
    color:#203a43;
}

input,select{
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

<h2>Tambah Paket Laundry</h2>

<form method="POST">

<select name="id_outlet" required>

<option value="">
Pilih Outlet
</option>

<?php while($o=mysqli_fetch_assoc($outlet)){ ?>

<option value="<?= $o['id']; ?>">
<?= $o['nama']; ?>
</option>

<?php } ?>

</select>

<select name="jenis" required>

<option value="">
Pilih Jenis
</option>

<option value="kiloan">
Kiloan
</option>

<option value="selimut">
Selimut
</option>

<option value="bed_cover">
Bed Cover
</option>

<option value="kaos">
Kaos
</option>

<option value="lain">
Lainnya
</option>

</select>

<input
type="text"
name="nama_paket"
placeholder="Nama Paket"
required
>

<input
type="number"
name="harga"
placeholder="Harga"
required
>

<button name="simpan">
Simpan Paket
</button>

</form>

<a href="index.php" class="back">
Kembali
</a>

</div>

</body>
</html>