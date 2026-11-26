<?php
session_start();
include "../koneksi.php";

if(!isset($_SESSION['role'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "admin"){
    die("Akses ditolak");
}

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
        INSERT INTO tb_user
        (
            nama,
            username,
            password,
            id_outlet,
            role
        )

        VALUES

        (
            '$_POST[nama]',
            '$_POST[username]',
            '$_POST[password]',
            1,
            '$_POST[role]'
        )
    ");

    header("Location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah User</title>

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

input,
select{
    width:100%;
    padding:12px;
    margin:8px 0;
    border:1px solid #ddd;
    border-radius:10px;
}

button,
.back{
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

<h2>Tambah User</h2>

<form method="POST">

<input
type="text"
name="nama"
placeholder="Nama Lengkap"
required
>

<input
type="text"
name="username"
placeholder="Username"
required
>

<input
type="password"
name="password"
placeholder="Password"
required
>

<select name="role" required>

    <option value="">
        Pilih Role
    </option>

    <option value="admin">
        Admin
    </option>

    <option value="kasir">
        Kasir
    </option>

    <option value="owner">
        Owner
    </option>

</select>

<button name="simpan">

Simpan User

</button>

</form>

<a href="index.php" class="back">
Kembali
</a>

</div>

</body>
</html>