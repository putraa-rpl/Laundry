<?php
session_start();
include "../koneksi.php";

/* =========================
   CEK LOGIN
========================= */

if(!isset($_SESSION['role'])){
    header("Location: ../login.php");
    exit();
}

/* =========================
   YANG BOLEH AKSES
========================= */

if(
    $_SESSION['role'] != "admin"
    &&
    $_SESSION['role'] != "kasir"
){
    die("Akses ditolak");
}

/* =========================
   FUNCTION KEMBALI
========================= */

function kembali(){

    if($_SESSION['role']=="admin"){
        return "../admin.php";
    }

    elseif($_SESSION['role']=="kasir"){
        return "../kasir.php";
    }

    return "../login.php";
}

/* =========================
   SIMPAN MEMBER
========================= */

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
        INSERT INTO tb_member
        (
            nama,
            alamat,
            jenis_kelamin,
            tlp,
            username,
            password
        )

        VALUES

        (
            '$_POST[nama]',
            '$_POST[alamat]',
            '$_POST[jenis_kelamin]',
            '$_POST[tlp]',
            '$_POST[username]',
            '$_POST[password]'
        )
    ");

    /* =========================
       KEMBALI SESUAI ROLE
    ========================= */

    header("Location: ".kembali());
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Registrasi Pelanggan</title>

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
    max-width:650px;
    margin:auto;
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

h2{
    margin-top:0;
    color:#203a43;
}

.info{
    background:#eef6ff;
    padding:15px;
    border-radius:12px;
    margin-bottom:20px;
    color:#203a43;
}

label{
    display:block;
    margin-top:10px;
    margin-bottom:6px;
    font-weight:600;
    color:#203a43;
}

input,
textarea,
select{
    width:100%;
    padding:13px;
    border:1px solid #ddd;
    border-radius:12px;
    margin-bottom:10px;
    font-size:15px;
}

textarea{
    resize:none;
    height:100px;
}

button,
.back{
    width:100%;
    padding:13px;
    border:none;
    border-radius:12px;
    color:white;
    text-decoration:none;
    display:block;
    text-align:center;
    margin-top:10px;
    font-size:15px;
}

button{
    background:linear-gradient(135deg,#3498db,#2c3e50);
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    opacity:.9;
}

.back{
    background:#e74c3c;
}

.back:hover{
    opacity:.9;
}

</style>
</head>

<body>

<div class="card">

<h2>👤 Registrasi Pelanggan</h2>

<div class="info">

Kasir/Admin dapat menambahkan member laundry baru ke sistem.

</div>

<form method="POST">

<label>Nama Member</label>

<input
type="text"
name="nama"
placeholder="Masukkan nama member"
required
>

<label>Alamat</label>

<textarea
name="alamat"
placeholder="Masukkan alamat member"
required
></textarea>

<label>Jenis Kelamin</label>

<select name="jenis_kelamin" required>

<option value="">
-- Pilih Jenis Kelamin --
</option>

<option value="L">
Laki-Laki
</option>

<option value="P">
Perempuan
</option>

</select>

<label>No Telepon</label>

<input
type="text"
name="tlp"
placeholder="Masukkan nomor telepon"
required
>

<label>Username</label>

<input
type="text"
name="username"
placeholder="Masukkan username"
required
>

<label>Password</label>

<input
type="password"
name="password"
placeholder="Masukkan password"
required
>

<button type="submit" name="simpan">

💾 Simpan Member

</button>

</form>

<!-- =========================
     TOMBOL KEMBALI
========================= -->

<a href="<?= kembali(); ?>" class="back">

⬅ Kembali

</a>

</div>

</body>
</html>