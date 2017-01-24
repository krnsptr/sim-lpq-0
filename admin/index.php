<?php
	require "../inc/connect.php";
	$error = array("Username dan Password tidak cocok.",
					"Login gagal.",
					"Anda belum login. Silakan login terlebih dahulu.");
	$success = array("Logout berhasil.");
	
	session_start();
	if(isset($_SESSION['e'])) { $e = $_SESSION['e']; unset($_SESSION['e']); }
	else if(isset($_SESSION['o'])) { $o = $_SESSION['o']; unset($_SESSION['o']); }
	
	$un = (isset($_POST['un'])) ? $_POST['un'] : NULL ;
	$pw = (isset($_POST['pw'])) ? md5($_POST['pw']) : NULL ;
	
	if(isset($_POST['post'])){
		if(!$un || !$pw) { $_SESSION['e'] = 0; header('Location: index.php'); }
		else {
			$stmt = mysqli_stmt_init($connect);
			if(mysqli_stmt_prepare($stmt, 'SELECT password, id_admin FROM admin where username = ?')) {
				mysqli_stmt_bind_param($stmt, "s", $un);
				if(!(mysqli_stmt_execute($stmt))) { $_SESSION['e'] = 1; header('Location: index.php'); }
				mysqli_stmt_bind_result($stmt, $pwa, $ida);
				mysqli_stmt_fetch($stmt);
				mysqli_stmt_close($stmt);
			}
			if(isset($pwa) && ($pw == $pwa)) {
				$_SESSION['id_admin'] = $ida;
				header('Location: dasbor.php');
				exit();
			}
			else { $_SESSION['e'] = 0; header('Location: index.php'); }
		}
	}
	include "../inc/header_admin.php";
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
		<?php if(isset($o)) { ?>
        <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" haha-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-success"></i> Berhasil!</h4>
				<?php echo $success[$o]; ?>
        </div>
		<?php } ?>
	</div>
	<div class="col-md-4"></div>
  </div>
  <div class="login-box">
  <!-- /.login-logo -->
  <div class="login-box-body" style="margin-bottom: 42%; margin-top: 35%">
    <h3 class="login-box-msg">Login ke SIM LPQ</h3>

    <form action="index.php" method="post">
	  <input type="hidden" name="post" />
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Username" name="un">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="pw">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
	<br />
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<?php include "../inc/footer_admin.php"; ?>