<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

session_start();
include "koneksi.php";



if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];


    $user = mysqli_query($conn,"
        SELECT * FROM tb_user
        WHERE username='$username'
        AND password='$password'
    ");

    if(mysqli_num_rows($user) > 0){

        $u = mysqli_fetch_assoc($user);

        $_SESSION['id_user'] = $u['id'];
        $_SESSION['nama'] = $u['nama'];
        $_SESSION['role'] = $u['role'];


        if($u['role']=="admin"){

            header("Location: admin.php");
            exit();

        }


        elseif($u['role']=="kasir"){

            header("Location: kasir.php");
            exit();

        }


        elseif($u['role']=="owner"){

            header("Location: owner.php");
            exit();

        }

    }


    $member = mysqli_query($conn,"
        SELECT * FROM tb_member
        WHERE username='$username'
        AND password='$password'
    ");

    if(mysqli_num_rows($member) > 0){

        $m = mysqli_fetch_assoc($member);

        $_SESSION['id_member'] = $m['id'];
        $_SESSION['nama_member'] = $m['nama'];

        header("Location: member_dashboard.php");
        exit();

    }


    $error = "Username atau Password Salah";

}

?>

<!DOCTYPE html>
<html>
<head>
<title>Login GoLaundry</title>

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

.box{
    width:400px;
    background:white;
    padding:35px;
    border-radius:25px;
    box-shadow:0 25px 50px rgba(0,0,0,.35);
}

.logo{
    text-align:center;
    font-size:55px;
}

h2{
    text-align:center;
    color:#203a43;
    margin-top:10px;
}

p{
    color:#777;
    margin-bottom:5px;
}

input{
    width:100%;
    padding:14px;
    margin-bottom:15px;
    border:1px solid #ddd;
    border-radius:12px;
    outline:none;
    transition:.2s;
}

input:focus{
    border-color:#3498db;
}

button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#3498db,#2c3e50);
    color:white;
    font-weight:bold;
    cursor:pointer;
    transition:.2s;
}

button:hover{
    transform:scale(1.02);
}

.error{
    background:#e74c3c;
    color:white;
    padding:12px;
    border-radius:12px;
    text-align:center;
    margin-bottom:15px;
}

.link{
    text-align:center;
    margin-top:15px;
}

.link a{
    color:#3498db;
    text-decoration:none;
    font-size:14px;
}

.link a:hover{
    text-decoration:underline;
}

</style>

</head>

<body>

<div class="box">

<div class="logo">
🧺
</div>

<h2>Login GoLaundry</h2>

<?php if(isset($error)){ ?>

<div class="error">
    <?= $error; ?>
</div>

<?php } ?>

<form method="POST">

<p>Username</p>

<input
type="text"
name="username"
placeholder="Masukkan Username"
required
>

<p>Password</p>

<input
type="password"
name="password"
placeholder="Masukkan Password"
required
>

<button type="submit" name="login">
LOGIN
</button>

</form>

<div class="link">

<a href="registerM.php">
Belum punya akun? Register Member
</a>

<br><br>

<a href="index.php">
← Kembali ke Halaman Utama
</a>

</div>

</div>

</body>
</html>