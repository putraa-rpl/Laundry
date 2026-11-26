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

$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM tb_user
    WHERE id='$id'
"));

if(isset($_POST['update'])){

    mysqli_query($conn,"
        UPDATE tb_user SET

        nama='$_POST[nama]',
        username='$_POST[username]',
        password='$_POST[password]',
        id_outlet=1,
        role='$_POST[role]'

        WHERE id='$id'
    ");

    header("Location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit User</title>

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
    background:#f39c12;
}

.back{
    background:#e74c3c;
}

</style>
</head>

<body>

<div class="card">

<h2>Edit User</h2>

<form method="POST">

<input
type="text"
name="nama"
value="<?= $data['nama']; ?>"
required
>

<input
type="text"
name="username"
value="<?= $data['username']; ?>"
required
>

<input
type="text"
name="password"
value="<?= $data['password']; ?>"
required
>

<select name="role" required>

<option value="admin"
<?= $data['role']=="admin" ? "selected" : ""; ?>>
Admin
</option>

<option value="kasir"
<?= $data['role']=="kasir" ? "selected" : ""; ?>>
Kasir
</option>

<option value="owner"
<?= $data['role']=="owner" ? "selected" : ""; ?>>
Owner
</option>

</select>

<button name="update">

Update User

</button>

</form>

<a href="index.php" class="back">

Kembali

</a>

</div>

</body>
</html>