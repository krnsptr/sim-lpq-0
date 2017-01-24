<?php

require_once "connect.php";

//cek alamat halaman supaya judulnya sesuai
$jd = array('Dasbor','Pendaftaran','Pendaftaran Santri','Pendaftaran Santri','Pendaftaran Instruktur','Pendaftaran Instruktur','Jadwal','Kelompok','Jadwal KBM', 'Profil', 'Program','Ubah Data Program','Daftar ke Program', 'Hapus Program','Jadwal Susulan'); //judul halaman
$nf = array('dasbor','daftar','daftar-santri','daftar-santri2','daftar-instruktur','daftar-instruktur2','jadwal','kelompok','jadwal-kbm','profil', 'program', 'program_ubah', 'program_daftar', 'program_hapus', 'jadwal-susulan'); //nama file halaman
$ac = array(0,-1,1,1,1,1,2,3,4,5,5,5,5,5,5); //active
$au = array(1,2,2,2,2,2,1,1,0,1,1,1,1,1,1); //auth
$nama_file = basename(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '.php'); //nama file halaman sekarang
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
$s = FALSE; $i = FALSE; //belum login (default)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['id_santri'])) {$id = $_SESSION['id_santri']; $s = TRUE; } //sudah login sebagai santri
else if(isset($_SESSION['id_instruktur'])) {$id = $_SESSION['id_instruktur']; $i = TRUE; } //sudah login sebagai instruktur
else if($auth == 1) { //belum login dan halaman perlu login
	$_SESSION['e'] = 2;
	header('Location: index.php');
	exit();
}
if($auth == 2 && ($i || $s)) { //sudah login dan halaman tidak boleh login
	header('Location: dasbor.php');
	exit();
}

//ambil data user dari database
	if($s) {	//login sebagai santri
		$query = "SELECT * FROM santri s,anggota a WHERE id_santri = '$id' AND a.id_anggota = s.id_anggota";
		$result = mysqli_query($connect,$query);
		$user = mysqli_fetch_object($result);
		$nama = $user->nama_lengkap;
		$id_anggota = $user->id_anggota;
		$foto_profil = $user->foto_profil;
	}
	else if($i) {	//login sebagai instruktur
		$query = "SELECT * FROM instruktur i,anggota a WHERE id_instruktur = '$id' AND a.id_anggota = i.id_anggota";
		$result = mysqli_query($connect,$query);
		$user = mysqli_fetch_object($result);
		$nama = $user->nama_lengkap;
		$id_anggota = $user->id_anggota;
		$foto_profil = $user->foto_profil;
	}
	if($i || $s) {
		$t_s = FALSE; $t_i = FALSE;
		$query = "SELECT * FROM santri WHERE id_anggota = ".$user->id_anggota;
		$result = mysqli_query($connect,$query);
		if(mysqli_num_rows($result) > 0) $t_s = TRUE;
		$query = "SELECT * FROM instruktur WHERE id_anggota = ".$user->id_anggota;
		$result = mysqli_query($connect,$query);
		if(mysqli_num_rows($result) > 0) $t_i = TRUE;
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
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bootstrap/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bootstrap/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="plugins/colorpicker/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="bootstrap/js/html5shiv.min.js"></script>
  <script src="bootstrap/js/respond.min.js"></script>
  <![endif]-->
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-green-light layout-top-nav">
<div class="wrapper"<?php if($active==-1) echo ' style ="background: url(\'img/background.jpg\')"';?>>

  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="index.php" class="navbar-brand"><b>SIM</b> LPQ</a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
			<?php if($s || $i) { ?>
            <li<?php if($active==0) echo ' class="active"';?>><a href="dasbor.php">Dasbor</a></li>
            <li<?php if($active==2) echo ' class="active"';?>><a href="jadwal.php">Jadwal</a></li>
			<!--<li<?php if($active==3) echo ' class="active"';?>><a href="kelompok.php">Kelompok</a></li>-->
			<?php } ?>
			<!--<li<?php if($active==4) echo ' class="active"';?>><a href="jadwalkbm.php">Jadwal KBM</a></li>-->
          </ul>
        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
			<?php if($s || $i) { //kalau sudah login ?>
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="img/foto-profil/<?php echo $foto_profil;?>" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs"><?php echo $nama; ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="img/foto-profil/<?php echo $foto_profil;?>" class="img-circle" alt="User Image">

                  <p>
                    <?php echo $nama; ?>
                    <small><?php echo ($i) ? "Instruktur" : "Santri" ; ?></small>
                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="profil.php" class="btn btn-default btn-flat">Profil</a>
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
    </nav><?php if(($s && $t_i) || ($i && $t_s)) { ?>
	<div class="col-md-12" style="background: #fff000; text-align: center; padding-top: 5px; padding-bottom: 5px;">
		Anda terdaftar sebagai santri dan instruktur.
		<?php if($s && $t_i) { ?><a href="ganti.php" class="btn-sm bg-purple">Login instruktur</a><?php } if($i && $t_s) { ?><a href="ganti.php" class="btn-sm bg-olive">Login santri</a><?php } ?>
	</div><br /><?php } ?>
  </header>