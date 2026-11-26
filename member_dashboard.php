<?php
session_start();
include "koneksi.php";

if(!isset($_SESSION['id_member'])){
    header("Location: login.php");
    exit();
}

$id_member = $_SESSION['id_member'];
$nama_member = $_SESSION['nama_member'];

$paket = mysqli_query($conn,"
    SELECT tb_paket.*, tb_outlet.nama AS nama_outlet
    FROM tb_paket
    JOIN tb_outlet ON tb_paket.id_outlet = tb_outlet.id
");

if(isset($_POST['pesan'])){

    $id_paket = $_POST['id_paket'];
    $qty = $_POST['qty'];

    $data_paket = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT * FROM tb_paket WHERE id='$id_paket'
    "));

    $id_outlet = $data_paket['id_outlet'];
    $invoice = "INV".time();

    mysqli_query($conn,"
        INSERT INTO tb_transaksi
        (
            id_outlet,
            kode_invoice,
            id_member,
            tgl,
            batas_waktu,
            tgl_bayar,
            biaya_tambahan,
            diskon,
            pajak,
            status,
            dibayar,
            id_user
        )
        VALUES
        (
            '$id_outlet',
            '$invoice',
            '$id_member',
            NOW(),
            DATE_ADD(NOW(), INTERVAL 3 DAY),
            NULL,
            0,
            0,
            0,
            'baru',
            'belum_dibayar',
            1
        )
    ");

    $id_transaksi = mysqli_insert_id($conn);

    mysqli_query($conn,"
        INSERT INTO tb_detail_transaksi
        (
            id_transaksi,
            id_paket,
            qty,
            keterangan
        )
        VALUES
        (
            '$id_transaksi',
            '$id_paket',
            '$qty',
            'Pesanan dari member'
        )
    ");

    $success = "Pesanan berhasil dibuat";
}

$riwayat = mysqli_query($conn,"
    SELECT
    tb_transaksi.kode_invoice,
    tb_transaksi.tgl,
    tb_transaksi.status,
    tb_transaksi.dibayar,
    tb_paket.nama_paket,
    tb_paket.harga,
    tb_detail_transaksi.qty
    FROM tb_transaksi
    JOIN tb_detail_transaksi ON tb_transaksi.id = tb_detail_transaksi.id_transaksi
    JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id
    WHERE tb_transaksi.id_member='$id_member'
    ORDER BY tb_transaksi.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Member</title>

<style>
*{box-sizing:border-box}

body{
    margin:0;
    font-family:'Segoe UI';
    background:#eef3f8;
}

.header{
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:white;
    padding:35px;
    border-radius:0 0 30px 30px;
}

.header a{
    color:white;
    text-decoration:none;
}

.container{
    width:90%;
    max-width:1000px;
    margin:-25px auto 30px;
}

.card{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    margin-bottom:20px;
}

select,input{
    width:100%;
    padding:13px;
    margin:10px 0;
    border:1px solid #ddd;
    border-radius:12px;
}

button{
    width:100%;
    padding:13px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#3498db,#2c3e50);
    color:white;
    font-weight:bold;
}

.success{
    background:#2ecc71;
    color:white;
    padding:12px;
    border-radius:12px;
    margin-bottom:12px;
}

table{
    width:100%;
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

.badge{
    padding:6px 10px;
    border-radius:20px;
    color:white;
    font-size:12px;
}

.bayar{
    background:#2ecc71;
}

.belum{
    background:#e74c3c;
}

.status{
    background:#3498db;
}
</style>
</head>

<body>

<div class="header">
    <h1>Halo, <?= $nama_member; ?> 👋</h1>
    <p>Silahkan pesan laundry kamu</p>
    <a href="logout.php">Logout</a>
</div>

<div class="container">

<div class="card">

<h2>Pesan Laundry</h2>

<?php if(isset($success)){ ?>
    <div class="success"><?= $success; ?></div>
<?php } ?>

<form method="POST">

<label>Pilih Paket</label>
<select name="id_paket" required>
    <option value="">-- Pilih Paket --</option>

    <?php while($p=mysqli_fetch_assoc($paket)){ ?>
        <option value="<?= $p['id']; ?>">
            <?= $p['nama_paket']; ?> -
            <?= $p['jenis']; ?> -
            <?= $p['nama_outlet']; ?> -
            Rp <?= number_format($p['harga']); ?>
        </option>
    <?php } ?>
</select>

<label>Jumlah / Kg</label>
<input type="number" name="qty" min="1" required>

<button type="submit" name="pesan">
Pesan Sekarang
</button>

</form>

</div>

<div class="card">

<h2>Riwayat Pesanan Saya</h2>

<table>
<tr>
    <th>No</th>
    <th>Invoice</th>
    <th>Tanggal</th>
    <th>Paket</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Total Bayar</th>
    <th>Status Laundry</th>
    <th>Pembayaran</th>
</tr>

<?php
$no = 1;
while($r=mysqli_fetch_assoc($riwayat)){
    $total = $r['harga'] * $r['qty'];
?>

<tr>
    <td><?= $no++; ?></td>
    <td><?= $r['kode_invoice']; ?></td>
    <td><?= $r['tgl']; ?></td>
    <td><?= $r['nama_paket']; ?></td>
    <td>Rp <?= number_format($r['harga']); ?></td>
    <td><?= $r['qty']; ?></td>
    <td>Rp <?= number_format($total); ?></td>
    <td>
        <span class="badge status">
            <?= $r['status']; ?>
        </span>
    </td>
    <td>
        <?php if($r['dibayar']=="dibayar"){ ?>
            <span class="badge bayar">Dibayar</span>
        <?php }else{ ?>
            <span class="badge belum">Belum Dibayar</span>
        <?php } ?>
    </td>
</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>