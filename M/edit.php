<?php
include "../koneksi.php";

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM tb_member WHERE id='$id'"));

if(isset($_POST['update'])){
    mysqli_query($conn,"
        UPDATE tb_member SET
        nama='$_POST[nama]',
        alamat='$_POST[alamat]',
        jenis_kelamin='$_POST[jenis_kelamin]',
        tlp='$_POST[tlp]',
        username='$_POST[username]',
        password='$_POST[password]'
        WHERE id='$id'
    ");

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Member</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:'Segoe UI';background:#eef3f8;padding:30px}
.card{max-width:500px;margin:auto;background:white;padding:25px;border-radius:20px;box-shadow:0 10px 25px rgba(0,0,0,.08)}
h2{color:#203a43}
input,select,textarea{width:100%;padding:12px;margin:8px 0;border:1px solid #ddd;border-radius:10px}
button,.back{display:block;width:100%;padding:12px;border:none;border-radius:10px;text-align:center;text-decoration:none;color:white;margin-top:10px}
button{background:#f39c12}
.back{background:#e74c3c}
</style>
</head>
<body>

<div class="card">
<h2>Edit Member</h2>

<form method="POST">
    <input name="nama" value="<?= $data['nama']; ?>" required>
    <textarea name="alamat" required><?= $data['alamat']; ?></textarea>

    <select name="jenis_kelamin" required>
        <option value="L" <?= $data['jenis_kelamin']=="L" ? "selected" : ""; ?>>Laki-laki</option>
        <option value="P" <?= $data['jenis_kelamin']=="P" ? "selected" : ""; ?>>Perempuan</option>
    </select>

    <input name="tlp" value="<?= $data['tlp']; ?>" required>
    <input name="username" value="<?= $data['username']; ?>" required>
    <input name="password" value="<?= $data['password']; ?>" required>

    <button name="update">Update</button>
</form>

<a href="index.php" class="back">Kembali</a>
</div>

</body>
</html>