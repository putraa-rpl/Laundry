<?php
session_start();
include "koneksi.php";



if(!isset($_SESSION['role'])){
    header("Location: login.php");
    exit();
}


if($_SESSION['role'] != "kasir"){
    die("Akses ditolak");
}



$total_member =
mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) as t FROM tb_member
"))['t'];

$total_transaksi =
mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) as t FROM tb_transaksi
"))['t'];

$total_belum_bayar =
mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) as t
FROM tb_transaksi
WHERE dibayar='belum_dibayar'
"))['t'];

$total_selesai =
mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) as t
FROM tb_transaksi
WHERE status='selesai'
"))['t'];

$nama = $_SESSION['nama'];

?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Kasir</title>

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Segoe UI';
    background:#eef3f8;
}

/* =========================
   SIDEBAR
========================= */

.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    background:linear-gradient(180deg,#0f2027,#203a43,#2c5364);
    padding:25px;
    color:white;
}

.sidebar h2{
    margin-top:0;
}

.sidebar a{
    display:block;
    padding:12px;
    margin:10px 0;
    color:white;
    text-decoration:none;
    border-radius:10px;
    transition:.2s;
}

.sidebar a:hover{
    background:rgba(255,255,255,.15);
}

.logout{
    background:#e74c3c;
}

/* =========================
   MAIN
========================= */

.main{
    margin-left:240px;
    padding:30px;
}

.top{
    background:white;
    padding:20px;
    border-radius:20px;
    margin-bottom:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.card{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    transition:.2s;
}

.card:hover{
    transform:translateY(-5px);
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
    font-size:35px;
    margin-bottom:10px;
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:700px){

    .sidebar{
        width:100%;
        height:auto;
        position:relative;
    }

    .main{
        margin-left:0;
    }

}

</style>

</head>

<body>

<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <h2>🧺 GoLaundry</h2>

    <a href="kasir.php">
        🏠 Dashboard
    </a>

    <!-- =========================
         INI YANG BENAR
    ========================= -->

    <a href="M/tambah.php">
        👤 Registrasi Pelanggan
    </a>

    <a href="T/index.php">
        💰 Kelola Transaksi
    </a>

    <a href="DT/index.php">
        📄 Detail Transaksi
    </a>

    <a href="L/laporan.php">
        📊 Generate Laporan
    </a>

    <a href="logout.php" class="logout">
        🚪 Logout
    </a>

</div>

<!-- =========================
     MAIN
========================= -->

<div class="main">

    <div class="top">

        <h2>Dashboard Kasir</h2>

        <p>
            Selamat datang,
            <strong><?= $nama; ?></strong> 👋
        </p>

    </div>

    <div class="grid">

        <div class="card">

            <div class="icon">👤</div>

            <h1><?= $total_member; ?></h1>

            <p>Total Member</p>

        </div>

        <div class="card">

            <div class="icon">💰</div>

            <h1><?= $total_transaksi; ?></h1>

            <p>Total Transaksi</p>

        </div>

        <div class="card">

            <div class="icon">⏳</div>

            <h1><?= $total_belum_bayar; ?></h1>

            <p>Belum Dibayar</p>

        </div>

        <div class="card">

            <div class="icon">✅</div>

            <h1><?= $total_selesai; ?></h1>

            <p>Laundry Selesai</p>

        </div>

    </div>

</div>

</body>
</html>