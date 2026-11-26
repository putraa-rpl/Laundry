<?php
session_start();
include "koneksi.php";

/* =========================
   CEK LOGIN
========================= */

if(!isset($_SESSION['role'])){
    header("Location: login.php");
    exit();
}

/* =========================
   ROLE OWNER
========================= */

if($_SESSION['role'] != "owner"){
    die("Akses ditolak");
}

/* =========================
   DATA DASHBOARD
========================= */

$total_member =
mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) as t FROM tb_member
"))['t'];

$total_outlet =
mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) as t FROM tb_outlet
"))['t'];

$total_paket =
mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) as t FROM tb_paket
"))['t'];

$total_transaksi =
mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) as t FROM tb_transaksi
"))['t'];

$nama = $_SESSION['nama'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Owner</title>

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:#eef3f8;
}

.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    color:white;
    padding:25px;
    background:linear-gradient(180deg,#0f2027,#203a43,#2c5364);
}

.sidebar h2{
    margin-top:0;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:12px;
    border-radius:12px;
    margin:8px 0;
}

.sidebar a:hover{
    background:rgba(255,255,255,.15);
}

.main{
    margin-left:240px;
    padding:30px;
}

.top,
.card{
    background:white;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.07);
}

.top{
    padding:22px;
    margin-bottom:20px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:18px;
}

.card{
    padding:25px;
}

.card h1{
    margin:0;
    font-size:35px;
    color:#203a43;
}

.card p{
    color:#777;
}

.icon{
    font-size:30px;
}

.logout{
    background:#e74c3c !important;
}

@media(max-width:700px){

    .sidebar{
        position:relative;
        width:100%;
        height:auto;
    }

    .main{
        margin-left:0;
    }

}

</style>
</head>

<body>

<div class="sidebar">

    <h2>🧺 GoLaundry</h2>

    <a href="owner.php">
        Dashboard
    </a>

    <a href="L/laporan.php">
        📊 Laporan Laundry
    </a>

    <a href="logout.php" class="logout">
        Logout
    </a>

</div>

<div class="main">

    <div class="top">

        <h2>Dashboard Owner</h2>

        <p>
            Selamat datang,
            <?= $nama; ?> 👑
        </p>

    </div>

    <div class="grid">

        <div class="card">

            <div class="icon">👤</div>

            <h1><?= $total_member; ?></h1>

            <p>Total Member</p>

        </div>

        <div class="card">

            <div class="icon">🏢</div>

            <h1><?= $total_outlet; ?></h1>

            <p>Total Outlet</p>

        </div>

        <div class="card">

            <div class="icon">📦</div>

            <h1><?= $total_paket; ?></h1>

            <p>Total Paket</p>

        </div>

        <div class="card">

            <div class="icon">💰</div>

            <h1><?= $total_transaksi; ?></h1>

            <p>Total Transaksi</p>

        </div>

    </div>

</div>

</body>
</html>