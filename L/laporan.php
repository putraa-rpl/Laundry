<?php
session_start();
include "../koneksi.php";

if(!isset($_SESSION['role'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role']!="admin" && $_SESSION['role']!="kasir" && $_SESSION['role']!="owner"){
    die("Akses ditolak");
}

function kembali(){
    if($_SESSION['role']=="admin") return "../admin.php";
    if($_SESSION['role']=="kasir") return "../kasir.php";
    if($_SESSION['role']=="owner") return "../owner.php";
    return "../login.php";
}

$where = "WHERE 1=1";

if(!empty($_GET['dari']) && !empty($_GET['sampai'])){
    $dari = $_GET['dari'];
    $sampai = $_GET['sampai'];
    $where .= " AND DATE(tb_transaksi.tgl) BETWEEN '$dari' AND '$sampai'";
}

if(!empty($_GET['status_bayar'])){
    $status_bayar = $_GET['status_bayar'];
    $where .= " AND tb_transaksi.dibayar='$status_bayar'";
}

$outlet = mysqli_query($conn,"SELECT * FROM tb_outlet ORDER BY nama");

$data = mysqli_query($conn,"
    SELECT
    tb_outlet.nama AS nama_outlet,
    tb_transaksi.kode_invoice,
    tb_transaksi.tgl,
    tb_transaksi.status,
    tb_transaksi.dibayar,
    tb_transaksi.biaya_tambahan,
    tb_transaksi.diskon,
    tb_transaksi.pajak,
    tb_member.nama AS nama_member,
    tb_paket.nama_paket,
    tb_paket.harga,
    tb_detail_transaksi.qty

    FROM tb_transaksi

    JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id
    JOIN tb_member ON tb_transaksi.id_member = tb_member.id
    JOIN tb_detail_transaksi ON tb_transaksi.id = tb_detail_transaksi.id_transaksi
    JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id

    $where

    ORDER BY tb_outlet.nama ASC, tb_transaksi.tgl DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Laporan Laundry Per Outlet</title>

<style>
*{box-sizing:border-box}

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
    overflow-x:auto;
}

h2{
    margin-top:0;
    color:#203a43;
}

.filter{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
    margin-bottom:20px;
}

input,select{
    padding:10px;
    border:1px solid #ddd;
    border-radius:10px;
}

button,.btn{
    padding:10px 14px;
    border:none;
    border-radius:10px;
    color:white;
    text-decoration:none;
    cursor:pointer;
}

button{background:#3498db}
.reset{background:#f39c12}
.print{background:#2ecc71}
.back{background:#e74c3c}

.outlet-title{
    margin-top:25px;
    padding:12px 15px;
    background:#203a43;
    color:white;
    border-radius:12px;
    font-weight:bold;
}

table{
    width:100%;
    min-width:1200px;
    border-collapse:collapse;
    margin-top:15px;
}

th{
    background:#3498db;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:center;
}

.total-box{
    margin-top:15px;
    padding:15px;
    background:#ecf9f1;
    border-radius:12px;
    font-weight:bold;
    font-size:17px;
}

.badge{
    padding:6px 10px;
    border-radius:20px;
    color:white;
    font-size:12px;
}

.dibayar{
    background:#2ecc71;
}

.belum_dibayar{
    background:#e74c3c;
}

@media print{
    .filter,.btn,button{
        display:none;
    }

    body{
        background:white;
        padding:0;
    }

    .card{
        box-shadow:none;
        border-radius:0;
    }
}
</style>
</head>

<body>

<div class="card">

<h2>📊 Laporan Laundry Per Outlet</h2>

<div class="filter">

<form method="GET">

<input type="date" name="dari" value="<?= isset($_GET['dari']) ? $_GET['dari'] : ''; ?>">

<input type="date" name="sampai" value="<?= isset($_GET['sampai']) ? $_GET['sampai'] : ''; ?>">

<select name="status_bayar">
    <option value="">Semua Pembayaran</option>

    <option value="dibayar" <?= isset($_GET['status_bayar']) && $_GET['status_bayar']=="dibayar" ? "selected" : ""; ?>>
        Dibayar
    </option>

    <option value="belum_dibayar" <?= isset($_GET['status_bayar']) && $_GET['status_bayar']=="belum_dibayar" ? "selected" : ""; ?>>
        Belum Dibayar
    </option>
</select>

<button type="submit">
Generate Laporan
</button>

<a href="laporan.php" class="btn reset">
Reset
</a>

<a href="#" onclick="window.print()" class="btn print">
Cetak
</a>

<a href="<?= kembali(); ?>" class="btn back">
Kembali
</a>

</form>

</div>

<?php
$grand_semua = 0;

while($o=mysqli_fetch_assoc($outlet)){

    $id_outlet = $o['id'];
    $nama_outlet = $o['nama'];

    $where_outlet = $where . " AND tb_transaksi.id_outlet='$id_outlet'";

    $laporan = mysqli_query($conn,"
        SELECT
        tb_transaksi.kode_invoice,
        tb_transaksi.tgl,
        tb_transaksi.status,
        tb_transaksi.dibayar,
        tb_transaksi.biaya_tambahan,
        tb_transaksi.diskon,
        tb_transaksi.pajak,
        tb_member.nama AS nama_member,
        tb_paket.nama_paket,
        tb_paket.harga,
        tb_detail_transaksi.qty

        FROM tb_transaksi

        JOIN tb_member ON tb_transaksi.id_member = tb_member.id
        JOIN tb_detail_transaksi ON tb_transaksi.id = tb_detail_transaksi.id_transaksi
        JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id

        $where_outlet

        ORDER BY tb_transaksi.tgl DESC
    ");

    if(mysqli_num_rows($laporan) == 0){
        continue;
    }
?>

<div class="outlet-title">
    Outlet: <?= $nama_outlet; ?>
</div>

<table>

<tr>
    <th>No</th>
    <th>Invoice</th>
    <th>Tanggal</th>
    <th>Member</th>
    <th>Paket</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Subtotal</th>
    <th>Tambahan</th>
    <th>Pajak</th>
    <th>Diskon</th>
    <th>Total</th>
    <th>Status</th>
    <th>Pembayaran</th>
</tr>

<?php
$no = 1;
$total_outlet = 0;
$total_dibayar = 0;
$total_belum = 0;

while($d=mysqli_fetch_assoc($laporan)){

    $subtotal = $d['harga'] * $d['qty'];
    $diskon_rupiah = ($subtotal * $d['diskon']) / 100;

    $total =
        $subtotal
        + $d['biaya_tambahan']
        + $d['pajak']
        - $diskon_rupiah;

    $total_outlet += $total;
    $grand_semua += $total;

    if($d['dibayar']=="dibayar"){
        $total_dibayar += $total;
    }else{
        $total_belum += $total;
    }
?>

<tr>
    <td><?= $no++; ?></td>
    <td><?= $d['kode_invoice']; ?></td>
    <td><?= $d['tgl']; ?></td>
    <td><?= $d['nama_member']; ?></td>
    <td><?= $d['nama_paket']; ?></td>
    <td>Rp <?= number_format($d['harga']); ?></td>
    <td><?= $d['qty']; ?></td>
    <td>Rp <?= number_format($subtotal); ?></td>
    <td>Rp <?= number_format($d['biaya_tambahan']); ?></td>
    <td>Rp <?= number_format($d['pajak']); ?></td>
    <td><?= $d['diskon']; ?>%</td>
    <td><strong>Rp <?= number_format($total); ?></strong></td>
    <td><?= $d['status']; ?></td>
    <td>
        <span class="badge <?= $d['dibayar']; ?>">
            <?= $d['dibayar']=="dibayar" ? "Dibayar" : "Belum Dibayar"; ?>
        </span>
    </td>
</tr>

<?php } ?>

</table>

<div class="total-box">
    Total Outlet <?= $nama_outlet; ?> : Rp <?= number_format($total_outlet); ?><br>
    Total Dibayar : Rp <?= number_format($total_dibayar); ?><br>
    Total Belum Dibayar : Rp <?= number_format($total_belum); ?>
</div>

<?php } ?>

<div class="total-box">
    Grand Total Semua Outlet : Rp <?= number_format($grand_semua); ?>
</div>

</div>

</body>
</html>