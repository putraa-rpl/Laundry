<?php
session_start();
include "../koneksi.php";

if(!isset($_SESSION['role'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "admin" && $_SESSION['role'] != "kasir"){
    die("Akses ditolak");
}

function kembali(){

    if($_SESSION['role']=="admin"){
        return "../admin.php";
    }

    if($_SESSION['role']=="kasir"){
        return "../kasir.php";
    }

    return "../login.php";
}

if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];

    mysqli_query($conn,"
        DELETE FROM tb_detail_transaksi
        WHERE id='$id'
    ");

    header("Location:index.php");
    exit();
}

$data = mysqli_query($conn,"
    SELECT 
    tb_detail_transaksi.*,

    tb_transaksi.kode_invoice,
    tb_transaksi.biaya_tambahan,
    tb_transaksi.pajak,
    tb_transaksi.diskon,

    tb_paket.nama_paket,
    tb_paket.harga

    FROM tb_detail_transaksi

    JOIN tb_transaksi 
    ON tb_detail_transaksi.id_transaksi = tb_transaksi.id

    JOIN tb_paket 
    ON tb_detail_transaksi.id_paket = tb_paket.id

    ORDER BY tb_detail_transaksi.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Transaksi</title>

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

.btn{
    padding:10px 14px;
    border-radius:10px;
    color:white;
    text-decoration:none;
    font-size:13px;
    display:inline-block;
    margin:3px;
}

.edit{
    background:#f39c12;
}

.hapus{
    background:#e74c3c;
}

.back{
    background:#3498db;
}

table{
    width:100%;
    min-width:1100px;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:#3498db;
    color:white;
    padding:14px;
    white-space:nowrap;
}

td{
    padding:14px;
    border-bottom:1px solid #eee;
    text-align:center;
}

.aksi{
    display:flex;
    justify-content:center;
    gap:6px;
    flex-wrap:wrap;
}

.money{
    white-space:nowrap;
    font-weight:600;
    color:#203a43;
}

.total-box{
    margin-top:20px;
    background:#ecf9f1;
    padding:15px;
    border-radius:12px;
    font-size:18px;
    font-weight:bold;
}
</style>
</head>

<body>

<div class="card">

<h2>📄 Detail Transaksi</h2>

<a href="<?= kembali(); ?>" class="btn back">
Kembali
</a>

<table>

<tr>
    <th>No</th>
    <th>Invoice</th>
    <th>Paket</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Subtotal</th>
    <th>Tambahan</th>
    <th>Pajak</th>
    <th>Diskon</th>
    <th>Total Biaya</th>
    <th>Keterangan</th>
    <th>Aksi</th>
</tr>

<?php
$no = 1;
$grand_total = 0;

while($d=mysqli_fetch_assoc($data)){

    $subtotal = $d['harga'] * $d['qty'];

    $diskon_rupiah = ($subtotal * $d['diskon']) / 100;

    $total_biaya =
        $subtotal
        + $d['biaya_tambahan']
        + $d['pajak']
        - $diskon_rupiah;

    $grand_total += $total_biaya;
?>

<tr>
    <td><?= $no++; ?></td>

    <td><?= $d['kode_invoice']; ?></td>

    <td><?= $d['nama_paket']; ?></td>

    <td class="money">
        Rp <?= number_format($d['harga']); ?>
    </td>

    <td><?= $d['qty']; ?></td>

    <td class="money">
        Rp <?= number_format($subtotal); ?>
    </td>

    <td class="money">
        Rp <?= number_format($d['biaya_tambahan']); ?>
    </td>

    <td class="money">
        Rp <?= number_format($d['pajak']); ?>
    </td>

    <td>
        <?= $d['diskon']; ?>%
    </td>

    <td class="money">
        Rp <?= number_format($total_biaya); ?>
    </td>

    <td><?= $d['keterangan']; ?></td>

    <td>
        <div class="aksi">

            <a href="edit.php?id=<?= $d['id']; ?>" class="btn edit">
                Edit
            </a>

            <a href="?hapus=<?= $d['id']; ?>" 
               class="btn hapus" 
               onclick="return confirm('Hapus detail?')">
                Hapus
            </a>

        </div>
    </td>
</tr>

<?php } ?>

</table>

<div class="total-box">
    Grand Total Detail:
    Rp <?= number_format($grand_total); ?>
</div>

</div>

</body>
</html>