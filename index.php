<?php
	require "inc/connect.php";
	$error = array("Username dan Password tidak cocok.",
					"Login gagal.",
					"Anda belum login. Silakan login terlebih dahulu.",
					"Pendaftaran gagal.",
					"Pendaftaran santri sudah ditutup.",
					"Pendaftaran instruktur sudah ditutup.");
	$warning = array("Pendaftaran berhasil, tetapi format foto tidak diterima. Silakan login.",
					"Pendaftaran berhasil, tetapi ukuran foto terlalu besar (maksimum 3 MB). Silakan login.",
					"Pendaftaran berhasil, tetapi foto profil gagal diubah. Silakan login.");
	$success = array("Pendaftaran berhasil. Silakan login.",
					"Logout berhasil.");
	session_start();
	if(isset($_SESSION['e'])) { $e = $_SESSION['e']; unset($_SESSION['e']); }
	else if(isset($_SESSION['w'])) { $w = $_SESSION['w']; unset($_SESSION['w']); }
	else if(isset($_SESSION['o'])) { $o = $_SESSION['o']; unset($_SESSION['o']); }
	
	$un = (isset($_POST['un'])) ? $_POST['un'] : NULL ;
	$pw = (isset($_POST['pw'])) ? md5($_POST['pw']) : NULL ;
	
	if(isset($_POST['post'])){
		if(!$un || !$pw) { $_SESSION['e'] = 0; header('Location: index.php'); exit();}
		else { 
			$stmt = mysqli_stmt_init($connect);
			if(mysqli_stmt_prepare($stmt, 'SELECT password, id_santri FROM anggota a, santri s where a.username = ? and a.id_anggota = s.id_anggota')) {
				mysqli_stmt_bind_param($stmt, "s", $un);
				if(!(mysqli_stmt_execute($stmt))) { $_SESSION['e'] = 1; header('Location: index.php'); exit();}
				mysqli_stmt_bind_result($stmt, $pws, $ids);
				mysqli_stmt_fetch($stmt);
				mysqli_stmt_close($stmt);
			}
			if(isset($pws) && ($pw == $pws)) {
				$_SESSION['id_santri'] = $ids;
				$_SESSION['o'] = 0;
				header('Location: dasbor.php');
				exit();
			}
			else {
				$stmt = mysqli_stmt_init($connect);
				if(mysqli_stmt_prepare($stmt, 'SELECT password, id_instruktur FROM anggota a, instruktur i where a.username = ? and a.id_anggota = i.id_anggota')) {
					mysqli_stmt_bind_param($stmt, "s", $un);
					if(!(mysqli_stmt_execute($stmt))) { $_SESSION['e'] = 1; header('Location: index.php'); exit();}
					mysqli_stmt_bind_result($stmt, $pwi, $idi);
					mysqli_stmt_fetch($stmt);
					mysqli_stmt_close($stmt);
				}
				if(isset($pwi) && ($pw == $pwi)) {
					$_SESSION['id_instruktur'] = $idi;
					$_SESSION['o'] = 0;
					header('Location: dasbor.php');
					exit();
				}
				else { $_SESSION['e'] = 0; header('Location: index.php'); exit();}
			}
		}
	}
	include "inc/header.php";
	session_unset();
?>
  <!-- Full Width Column -->
  <br /><br />
  <div class="col-lg-12">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<?php if(isset($e)) { ?>
        <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" haha-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Kesalahan!</h4>
				<?php echo $error[$e]; ?>
        </div>
		<?php } ?>
		<?php if(isset($w)) { ?>
        <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" haha-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Peringatan!</h4>
				<?php echo $warning[$w]; ?>
        </div>
		<?php } ?>
		<?php if(isset($o)) { ?>
        <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" haha-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
				<?php echo $success[$o]; ?>
        </div>
		<?php } ?>
	</div>
	<div class="col-md-4"></div>
  </div>
  <div class="login-box">
  <!-- /.login-logo -->
  <div class="login-box-body" style="margin-top: 30%; margin-bottom: 30%">
    <h3 class="login-box-msg">Login ke SIM LPQ</h3>

    <form action="index.php" method="post">
	  <input type="hidden" name="post" />
      <div class="form-group has-feedback">
		<label for="username">Username:</label>
        <input type="text" class="form-control" placeholder="Username" name="un" id="username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
		<label for="password">Password:</label>
        <input type="password" class="form-control" placeholder="Password" name="pw" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
			Belum terdaftar?<br />
			<a href="daftar.php" class="text-center">Daftar</a><br /><br />
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <!--<a href="#">Lupa password?</a><br>-->


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<?php include "inc/footer.php"; ?>