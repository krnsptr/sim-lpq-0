<?php
	session_start();
	require "inc/connect.php";
	
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pendaftaran_instruktur'";
	$result = mysqli_query($connect,$query);
	$d_i = mysqli_fetch_object($result);
	if($d_i->isi==0) { $_SESSION['e']=5; header('Location: index.php'); exit(); }
	
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_instruktur'";
	$result = mysqli_query($connect,$query);
	$p_i = mysqli_fetch_object($result);
	
	include "inc/header.php";

	$error = array(	"Formulir ada yang kosong. Silakan isi ulang.",
					"Format nomor HP salah. Contoh: 081234567890 (10-13 digit).",
					"Format alamat email salah.",
					"Username hanya boleh mengandung huruf kecil, angka, dan underscore (4-16 karakter).",
					"Password minimum 6 karakter.",
					"Nomor HP sudah terdaftar.",
					"Alamat email sudah terdaftar.",
					"Username sudah terdaftar.",
					"Password tidak sama.",
					"Pendaftaran gagal.",
					);
	
	if(isset($_SESSION['e'])) {
		$e = $_SESSION['e'];
		unset($_SESSION['e']);
	}
	
	$nl = isset($_SESSION['nl']) ? $_SESSION['nl'] : NULL; //nama lengkap
	$jk = isset($_SESSION['jk']) ? $_SESSION['jk'] : NULL; //jenis kelamin
	$st = isset($_SESSION['st']) ? $_SESSION['st'] : NULL; //status
	$nh = isset($_SESSION['nh']) ? $_SESSION['nh'] : NULL; //nomor hp
	$ae = isset($_SESSION['ae']) ? $_SESSION['ae'] : NULL; //alamat email
	$un = isset($_SESSION['un']) ? $_SESSION['un'] : NULL; //username
	$pr = isset($_SESSION['pr']) ? $_SESSION['pr'] : array(NULL, NULL, NULL); //program
	$mt = isset($_SESSION['mt']) ? $_SESSION['mt'] : NULL; //mentoring
	
?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Pendaftaran Instruktur
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i>SIM LPQ</a></li>
          <li class="active">Pendaftaran Instruktur</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
		<?php if(isset($e)) { ?>
        <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Kesalahan!</h4>
				<?php echo $error[$e]; ?>
        </div>
		<?php } ?>
		<div class="callout callout-info">
                <h4><i class="icon fa fa-info"></i>&nbsp;&nbsp;&nbsp;Pengumuman</h4>
                <p><?php echo $p_i->isi; ?></p>
        </div>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Formulir Pendaftaran (halaman 1 dari 2)</h3>
          </div>
          <div class="box-body">
            <form action="daftar-instruktur_proses.php" method="post" data-toggle="validator" role="form">
			  <input type="hidden" name="post" />
			  <div class="row">
			   <div class="col-md-2"><label for="nl">Nama lengkap</label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="text" class="form-control" maxlength="32" placeholder="Contoh: R. M. Aji Said P." name="nl" data-required-error="Nama lengkap wajib diisi." value="<?php echo $nl; ?>" required><div class="help-block with-errors"></div>
			   </div>
			   <div class="col-md-2">(sesuai EYD)</div><br />
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="jk">Jenis kelamin</label></div>
			   <div class="form-group has-feedback col-md-8">
				<select class="form-control" name="jk" required>
					<option value="1"<?php if($jk == 1) echo " selected"; ?>>Laki-Laki</option>
					<option value="2"<?php if($jk == 2) echo " selected"; ?>>Perempuan</option>
				</select>
			   </div>
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="st">Status</label></div>
			   <div class="form-group has-feedback col-md-8">
				<select class="form-control" name="st" required>
					<option value="1"<?php if($st == 1) echo " selected"; ?>>Mahasiswa IPB</option>
					<option value="2"<?php if($st == 2) echo " selected"; ?>>Umum</option>
				</select>
			   </div>
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="nh">Nomor HP</label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="text" class="form-control" maxlength="13" placeholder="08xxxxxxxx..." name="nh" value="<?php echo $nh; ?>" pattern="08[0-9]{8,11}" data-remote="inc/check.php" data-remote-error="<?php echo $error[5]; ?>" data-required-error="Nomor HP wajib diisi." data-pattern-error="<?php echo $error[1]; ?>" required><div class="help-block with-errors"></div>
			   </div>
			   <div class="col-md-2">(10-13 digit)</div><br />
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="ae">Alamat email</label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="email" class="form-control" maxlength="32" placeholder="alamat@email" name="ae" value="<?php echo $ae; ?>" data-remote="inc/check.php" data-remote-error="<?php echo $error[6]; ?>" data-error="<?php echo $error[2]; ?>" required><div class="help-block with-errors"></div>
			   </div>
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="un">Username</label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="text" class="form-control" maxlength="16" placeholder="" name="un" value="<?php echo $un; ?>" pattern="[a-z0-9_]{4,16}" data-remote="inc/check.php" data-remote-error="<?php echo $error[7]; ?>" data-required-error="Username wajib diisi." data-pattern-error = "<?php echo $error[3]; ?>" required><div class="help-block with-errors"></div>
			   </div>
			   <div class="col-md-2">(4-16 karakter)</div><br />
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="pw">Password</label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="password" id="password" class="form-control" maxlength="32" placeholder=""  name="pw" data-minlength="6" data-error="<?php echo $error[4]; ?>" required><div class="help-block with-errors"></div>
			   </div>
			   <div class="col-md-2">(minimum 6 karakter)</div><br />
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="up">Ulangi password</label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="password" class="form-control" placeholder="" name="up" data-match="#password" data-required-error="Password wajib diulangi." data-match-error="<?php echo $error[8]; ?>" required><div class="help-block with-errors"></div>
			   </div>
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="pr[]">Program</label></div>
			   <div class="form-group has-feedback col-md-8 options">
				<div class="checkbox">
					<label>
					  <input type="checkbox" name="pr[0]" <?php if(isset($pr[0])) echo " checked"; ?>>
					  Tahsin
					</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<label>
					  <input type="checkbox" name="pr[1]" <?php if(isset($pr[1])) echo " checked"; ?>>
					  Tahfizh/Takhosus
					</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<label>
					  <input type="checkbox" name="pr[2]" <?php if(isset($pr[2])) echo " checked"; ?>>
					  Bahasa Arab
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-md-3"></div>
			   </div>
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="mt">Mentoring</label></div>
			   <div class="form-group has-feedback col-md-8">
				<select class="form-control" name="mt" required>
					<option value="1"<?php if($mt == 1) echo " selected"; ?>>Belum</option>
					<option value="2"<?php if($mt == 2) echo " selected"; ?>>Sudah</option>
				</select>
			   </div>
			  </div>
			  <div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-6">
				Sudah terdaftar? Silakan <a href="index.php" class="text-center">login</a>.
				</div>
				<!-- /.col -->
				<div class="col-md-2">
					<button type="submit" class="btn btn-primary btn-block btn-flat">Berikutnya</button>
				</div>
				<div class="col-md-2"></div>
				<!-- /.col -->
			  </div>
			</form>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
<?php include "inc/footer.php"; ?>