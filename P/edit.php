<?php
session_start();
include "../koneksi.php";

if($_SESSION['role'] != "admin"){
    die("Akses ditolak");
}

$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM tb_paket
    WHERE id='$id'
"));

$outlet = mysqli_query($conn,"
    SELECT * FROM tb_outlet
");

if(isset($_POST['update'])){

    mysqli_query($conn,"
        UPDATE tb_paket SET

        id_outlet='$_POST[id_outlet]',
        jenis='$_POST[jenis]',
        nama_paket='$_POST[nama_paket]',
        harga='$_POST[harga]'

        WHERE id='$id'
    ");

    header("Location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Paket</title>

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
    background:#f39c12;
}

.back{
    background:#e74c3c;
}

</style>
</head>

<body>

<div class="card">

<h2>Edit Paket Laundry</h2>

<form method="POST">

<select name="id_outlet" required>

<?php while($o=mysqli_fetch_assoc($outlet)){ ?>

<option
value="<?= $o['id']; ?>"

<?= $data['id_outlet']==$o['id'] ? "selected" : ""; ?>
>

<?= $o['nama']; ?>

</option>

<?php } ?>

</select>

<select name="jenis" required>

<option value="kiloan"
<?= $data['jenis']=="kiloan" ? "selected" : ""; ?>>
Kiloan
</option>

<option value="selimut"
<?= $data['jenis']=="selimut" ? "selected" : ""; ?>>
Selimut
</option>

<option value="bed_cover"
<?= $data['jenis']=="bed_cover" ? "selected" : ""; ?>>
Bed Cover
</option>

<option value="kaos"
<?= $data['jenis']=="kaos" ? "selected" : ""; ?>>
Kaos
</option>

<option value="lain"
<?= $data['jenis']=="lain" ? "selected" : ""; ?>>
Lainnya
</option>

</select>

<input
type="text"
name="nama_paket"
value="<?= $data['nama_paket']; ?>"
required
>

<input
type="number"
name="harga"
value="<?= $data['harga']; ?>"
required
>

<button name="update">
Update Paket
</button>

</form>

<a href="index.php" class="back">
Kembali
</a>

</div>

</body>
</html>
