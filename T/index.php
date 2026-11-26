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
    if($_SESSION['role']=="admin") return "../admin.php";
    if($_SESSION['role']=="kasir") return "../kasir.php";
    if($_SESSION['role']=="owner") return "../owner.php";
    return "../login.php";
}

if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    mysqli_query($conn,"DELETE FROM tb_transaksi WHERE id='$id'");
    header("Location:index.php");
    exit();
}

$data = mysqli_query($conn,"
SELECT tb_transaksi.*, tb_member.nama AS nama_member,
tb_paket.nama_paket, tb_paket.harga, tb_detail_transaksi.qty
FROM tb_transaksi
JOIN tb_member ON tb_transaksi.id_member = tb_member.id
JOIN tb_detail_transaksi ON tb_transaksi.id = tb_detail_transaksi.id_transaksi
JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id
ORDER BY tb_transaksi.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Kelola Transaksi</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:'Segoe UI';background:#eef3f8;padding:30px}
.card{background:white;padding:25px;border-radius:22px;box-shadow:0 10px 25px rgba(0,0,0,.08)}
.header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px}
.btn{padding:10px 14px;border-radius:10px;color:white;text-decoration:none;font-size:13px;display:inline-block}
.back{background:#3498db}.tambah{background:#2ecc71}.edit{background:#f39c12}.nota{background:#9b59b6}.hapus{background:#e74c3c}
.table-wrap{overflow-x:auto;margin-top:20px}
table{width:100%;min-width:1300px;border-collapse:collapse}
th{background:#3498db;color:white;padding:14px}
td{padding:14px;border-bottom:1px solid #eee;text-align:center}
.badge{padding:6px 12px;border-radius:20px;color:white;font-size:12px}
.baru{background:#3498db}.proses{background:#f39c12}.selesai{background:#2ecc71}.diambil{background:#9b59b6}
.dibayar{background:#2ecc71}.belum_dibayar{background:#e74c3c}
.aksi{display:flex;gap:6px;justify-content:center;flex-wrap:wrap}
.total-box{margin-top:20px;background:#ecf9f1;padding:15px;border-radius:14px;font-size:18px;font-weight:bold}
</style>
</head>
<body>

<div class="card">
<div class="header">
<h2>🧺 Kelola Transaksi</h2>
<div>
<a href="<?= kembali(); ?>" class="btn back">Kembali</a>
<a href="tambah.php" class="btn tambah">+ Entri Transaksi</a>
</div>
</div>

<div class="table-wrap">
<table>
<tr>
<th>No</th><th>Invoice</th><th>Member</th><th>Paket</th><th>Qty</th><th>Harga</th>
<th>Subtotal</th><th>Tambahan</th><th>Pajak</th><th>Diskon</th><th>Total</th>
<th>Status</th><th>Pembayaran</th><th>Aksi</th>
</tr>

<?php
$no=1;
$grand_total=0;
while($d=mysqli_fetch_assoc($data)){
    $subtotal = $d['harga'] * $d['qty'];
    $diskon_rupiah = ($subtotal * $d['diskon']) / 100;
    $total_bayar = $subtotal + $d['biaya_tambahan'] + $d['pajak'] - $diskon_rupiah;
    $grand_total += $total_bayar;
?>
<tr>
<td><?= $no++; ?></td>
<td><?= $d['kode_invoice']; ?></td>
<td><?= $d['nama_member']; ?></td>
<td><?= $d['nama_paket']; ?></td>
<td><?= $d['qty']; ?></td>
<td>Rp <?= number_format($d['harga']); ?></td>
<td>Rp <?= number_format($subtotal); ?></td>
<td>Rp <?= number_format($d['biaya_tambahan']); ?></td>
<td>Rp <?= number_format($d['pajak']); ?></td>
<td><?= $d['diskon']; ?>%</td>
<td><strong>Rp <?= number_format($total_bayar); ?></strong></td>
<td><span class="badge <?= $d['status']; ?>"><?= ucfirst($d['status']); ?></span></td>
<td><span class="badge <?= $d['dibayar']; ?>"><?= $d['dibayar']=="dibayar" ? "Dibayar" : "Belum"; ?></span></td>
<td>
<div class="aksi">
<a href="edit.php?id=<?= $d['id']; ?>" class="btn edit">Proses</a>
<a href="nota.php?id=<?= $d['id']; ?>" class="btn nota">Nota</a>
<a href="?hapus=<?= $d['id']; ?>" class="btn hapus" onclick="return confirm('Hapus transaksi?')">Hapus</a>
</div>
</td>
</tr>
<?php } ?>
</table>
</div>

<div class="total-box">
Grand Total Pendapatan : Rp <?= number_format($grand_total); ?>
</div>

</div>
</body>
</html>