<!DOCTYPE html>
<html>
<head>
<title>GoLaundry App</title>
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
.hero{
    width:90%;
    max-width:900px;
    display:grid;
    grid-template-columns:1fr 1fr;
    background:white;
    border-radius:25px;
    overflow:hidden;
    box-shadow:0 25px 60px rgba(0,0,0,.35);
}
.left{
    padding:50px;
}
.left h1{
    font-size:42px;
    margin:0;
    color:#203a43;
}
.left p{
    color:#666;
    line-height:1.6;
}
.btn{
    display:inline-block;
    padding:13px 22px;
    margin:8px 5px 0 0;
    border-radius:12px;
    text-decoration:none;
    color:white;
    font-weight:bold;
}
.login{background:linear-gradient(135deg,#3498db,#2c3e50)}
.regis{background:linear-gradient(135deg,#2ecc71,#27ae60)}
.right{
    background:linear-gradient(135deg,#3498db,#2c3e50);
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:90px;
}
@media(max-width:700px){
    .hero{grid-template-columns:1fr}
    .right{display:none}
}
</style>
</head>
<body>

<div class="hero">
    <div class="left">
        <h1>GoLaundry 🧺</h1>
        <p>Aplikasi laundry modern untuk mengelola member, outlet, paket, transaksi, dan laporan dengan mudah.</p>

        <a href="login.php" class="btn login">Login</a>
        <a href="registerM.php" class="btn regis">Register Member</a>
    </div>

    <div class="right">
        🧼
    </div>
</div>

</body>
</html>