<?php
include "../koneksi.php";

if(isset($_POST['simpan'])){
    mysqli_query($conn,"
        INSERT INTO tb_outlet(nama, alamat, tlp)
        VALUES('$_POST[nama]', '$_POST[alamat]', '$_POST[tlp]')
    ");

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Outlet</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:'Segoe UI';background:#eef3f8;padding:30px}
.card{max-width:500px;margin:auto;background:white;padding:25px;border-radius:20px;box-shadow:0 10px 25px rgba(0,0,0,.08)}
h2{color:#203a43}
input,textarea{width:100%;padding:12px;margin:8px 0;border:1px solid #ddd;border-radius:10px}
button,.back{display:block;width:100%;padding:12px;border:none;border-radius:10px;text-align:center;text-decoration:none;color:white;margin-top:10px}
button{background:linear-gradient(135deg,#3498db,#2c3e50)}
.back{background:#e74c3c}
</style>
</head>
<body>

<div class="card">
<h2>Tambah Outlet</h2>

<form method="POST">
    <input name="nama" placeholder="Nama Outlet" required>
    <textarea name="alamat" placeholder="Alamat" required></textarea>
    <input name="tlp" placeholder="Telepon" required>

    <button name="simpan">Simpan</button>
</form>

<a href="index.php" class="back">Kembali</a>
</div>

</body>
</html>