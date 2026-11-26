<?php
include "../koneksi.php";

if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query($conn,"
        DELETE FROM tb_outlet
        WHERE id='$id'
    ");

    header("Location:index.php");
    exit();
}

$data = mysqli_query($conn,"
    SELECT * FROM tb_outlet
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Data Outlet</title>

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Segoe UI';
    background:#eef3f8;
    padding:30px;
}

.card{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

h2{
    margin-top:0;
    color:#203a43;
}

.btn{
    padding:10px 14px;
    border-radius:10px;
    color:white;
    text-decoration:none;
    font-size:13px;
}

.tambah{
    background:#2ecc71;
}

.edit{
    background:#f39c12;
}

.hapus{
    background:#e74c3c;
}

.back{
    background:#3498db;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:#3498db;
    color:white;
    padding:14px;
}

td{
    padding:14px;
    border-bottom:1px solid #eee;
    text-align:center;
}

</style>

</head>

<body>

<div class="card">

<h2>Data Outlet</h2>

<a href="../admin.php" class="btn back">
Kembali
</a>

<a href="tambah.php" class="btn tambah">
+ Tambah Outlet
</a>

<table>

<tr>

<th>No</th>
<th>Nama Outlet</th>
<th>Alamat</th>
<th>Telepon</th>
<th>Aksi</th>

</tr>

<?php

$no = 1;

while($d=mysqli_fetch_assoc($data)){

?>

<tr>

<td><?= $no++; ?></td>

<td><?= $d['nama']; ?></td>

<td><?= $d['alamat']; ?></td>

<td><?= $d['tlp']; ?></td>

<td>

<a href="edit.php?id=<?= $d['id']; ?>"
class="btn edit">
Edit
</a>

<a href="?hapus=<?= $d['id']; ?>"
class="btn hapus"
onclick="return confirm('Hapus data?')">
Hapus
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>