<?php
$hari = array(NULL,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Ahad');
$tahsin = array('Tidak terdaftar','Belum dites','Pra-Tahsin','Tahsin 1','Tahsin 2','Lulus');
$tahfizh = array('Tidak terdaftar','Belum dites','Takhosus','Tahfizh','Lulus');
$b_arab = array('Tidak terdaftar','Belum dites','Pemula','Tingkat 1','Lulus');

require "connect.php";

//cek alamat halaman supaya judulnya sesuai
$jd = array('Dasbor','Santri','Instruktur'); //judul halaman
$nf = array('dasbor','santri','instruktur'); //nama file halaman
$ac = array(0,1,2); //active
$au = array(1,1,1); //auth
$nama_file = basename($_SERVER["REQUEST_URI"], '.php'); //nama file halaman sekarang
$active = -1; //link di navbar nonaktif semua, default untuk halaman home (index.php)
$auth = 2; //halaman tidak ditampilkan kalau sudah login, default untuk halaman home (index.php)

foreach($nf as $key => $value) {
	if($nama_file == $nf[$key]) {$judul = $jd[$key]; $active = $ac[$key]; $auth = $au[$key]; break;}
}

/*
$auth == 0: halaman tidak perlu login. kalau belum login, tetap di halaman.
$auth == 1: halaman perlu login. kalau belum login, redirect ke halaman login (index.php).
$auth == 2: halaman tidak ditampilkan kalau sudah login. kalau sudah login, redirect ke dasbor.
*/

//cek status login
$a = FALSE; //belum login (default)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['id_admin'])) {$id = $_SESSION['id_admin']; $a = TRUE; } //sudah login sebagai admin
else if($auth == 1) { //belum login dan halaman perlu login
	$_SESSION['e'] = 2;
	header('Location: index.php');
	exit();
}
if($auth == 2 && ($a)) { //sudah login dan halaman tidak ditampilkan kalau sudah login
	header('Location: dasbor.php');
	exit();
}

//ambil data admin dari database
	if($a) {	//login sebagai admin
		$query = "SELECT * FROM admin WHERE id_admin = '$id'";
		$result = mysqli_query($connect,$query);
		$admin = mysqli_fetch_object($result);
		$username = $admin->username;
		$id_admin = $admin->id_admin;
		$foto_profil = $admin->foto_profil;
	}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SIM LPQ<?php if(isset($judul)) echo ' - '.$judul; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../bootstrap/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../bootstrap/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker-bs3.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../plugins/datepicker/datepicker3.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="../plugins/colorpicker/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../plugins/select2/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="../bootstrap/js/html5shiv.min.js"></script>
  <script src="../bootstrap/js/respond.min.js"></script>
  <![endif]-->
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-green-light layout-top-nav">
<div class="wrapper"<?php if($active==-1) echo ' style ="background: #666"';?>>

  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="../index.php" class="navbar-brand"><b>SIM</b> LPQ</a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
			<?php if($a) { ?>
            <li<?php if($active==0) echo ' class="active"';?>><a href="dasbor.php">Dasbor</a></li>
            <li<?php if($active==1) echo ' class="active"';?>><a href="santri.php">Santri</a></li>
			<li<?php if($active==2) echo ' class="active"';?>><a href="instruktur.php">Instruktur</a></li>
			<?php } ?>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
			<?php if($a) { //kalau sudah login ?>
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="../img/foto-profil/<?php echo $foto_profil;?>" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs"><?php echo $username; ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="../img/foto-profil/<?php echo $foto_profil;?>" class="img-circle" alt="User Image">
                  <p>
                    <?php echo $username; ?>
                    <small>Administrator</small>
                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                  </div>
                  <div class="pull-right">
                    <a href="logout.php" class="btn btn-default btn-flat">Logout</a>
                  </div>
                </li>
              </ul>
            </li>
			<?php } ?>
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>