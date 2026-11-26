<?php
include "koneksi.php";

if(isset($_POST['register'])){
    mysqli_query($conn,"
        INSERT INTO tb_member(nama, alamat, jenis_kelamin, tlp, username, password)
        VALUES(
        '$_POST[nama]',
        '$_POST[alamat]',
        '$_POST[jenis_kelamin]',
        '$_POST[tlp]',
        '$_POST[username]',
        '$_POST[password]')
    ");

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register Member</title>
<style>
*{box-sizing:border-box}
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}
.box{
    width:420px;
    background:white;
    padding:35px;
    border-radius:22px;
    box-shadow:0 25px 50px rgba(0,0,0,.35);
}
h2{
    text-align:center;
    color:#203a43;
    margin-top:0;
}
input,textarea,select{
    width:100%;
    padding:13px;
    margin:8px 0;
    border:1px solid #ddd;
    border-radius:12px;
    outline:none;
}
button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#2ecc71,#27ae60);
    color:white;
    font-weight:bold;
    cursor:pointer;
    margin-top:10px;
}
a{
    color:#3498db;
    text-decoration:none;
}
</style>
</head>
<body>

<div class="box">
    <h2>Register Member</h2>

    <form method="POST">
        <input type="text" name="nama" placeholder="Nama Lengkap" required>
        <textarea name="alamat" placeholder="Alamat" required></textarea>

        <select name="jenis_kelamin" required>
            <option value="">Pilih Jenis Kelamin</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>

        <input type="text" name="tlp" placeholder="No Telepon" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="register">Daftar</button>
    </form>

    <br>
    <center>
        <a href="login.php">Sudah punya akun? Login</a>
    </center>
</div>

</body>
</html>