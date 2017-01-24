<?php
	$e = FALSE; $o = FALSE;
	include "../inc/header_admin.php";
	if(isset($_POST['ps']) && isset($_POST['pi']) && isset($_POST['ds']) && isset($_POST['di']) && isset($_POST['js']) && isset($_POST['ji'])) {
		$ps = $_POST['ps']; $pi = $_POST['pi']; $ds = $_POST['ds']; $di = $_POST['di']; $js = $_POST['js']; $ji = $_POST['ji'];
		$stmt = mysqli_stmt_init($connect);
		if(mysqli_stmt_prepare($stmt, "UPDATE sistem SET isi = ? WHERE nama_pengaturan = 'pengumuman_santri'")) {
			mysqli_stmt_bind_param($stmt, "s", $ps);
			mysqli_stmt_execute($stmt) or $e = TRUE;
			mysqli_stmt_close($stmt);
		} else $e = TRUE;
		$stmt = mysqli_stmt_init($connect);
		if(mysqli_stmt_prepare($stmt, "UPDATE sistem SET isi = ? WHERE nama_pengaturan = 'pengumuman_instruktur'")) {
			mysqli_stmt_bind_param($stmt, "s", $pi);
			mysqli_stmt_execute($stmt) or $e = TRUE;
			mysqli_stmt_close($stmt);
		} else $e = TRUE;
		$stmt = mysqli_stmt_init($connect);
		if(mysqli_stmt_prepare($stmt, "UPDATE sistem SET isi = ? WHERE nama_pengaturan = 'pendaftaran_santri'")) {
			mysqli_stmt_bind_param($stmt, "s", $ds);
			mysqli_stmt_execute($stmt) or $e = TRUE;
			mysqli_stmt_close($stmt);
		} else $e = TRUE;
		$stmt = mysqli_stmt_init($connect);
		if(mysqli_stmt_prepare($stmt, "UPDATE sistem SET isi = ? WHERE nama_pengaturan = 'pendaftaran_instruktur'")) {
			mysqli_stmt_bind_param($stmt, "s", $di);
			mysqli_stmt_execute($stmt) or $e = TRUE;
			mysqli_stmt_close($stmt);
		} else $e = TRUE;
		$stmt = mysqli_stmt_init($connect);
		if(mysqli_stmt_prepare($stmt, "UPDATE sistem SET isi = ? WHERE nama_pengaturan = 'penjadwalan_santri'")) {
			mysqli_stmt_bind_param($stmt, "s", $js);
			mysqli_stmt_execute($stmt) or $e = TRUE;
			mysqli_stmt_close($stmt);
		} else $e = TRUE;
		$stmt = mysqli_stmt_init($connect);
		if(mysqli_stmt_prepare($stmt, "UPDATE sistem SET isi = ? WHERE nama_pengaturan = 'penjadwalan_instruktur'")) {
			mysqli_stmt_bind_param($stmt, "s", $ji);
			mysqli_stmt_execute($stmt) or $e = TRUE;
			mysqli_stmt_close($stmt);
		} else $e = TRUE;
		if(!$e) $o = TRUE;
	}
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_santri'";
	$result = mysqli_query($connect,$query);
	$p_s = mysqli_fetch_object($result);
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_instruktur'";
	$result = mysqli_query($connect,$query);
	$p_i = mysqli_fetch_object($result);
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pendaftaran_santri'";
	$result = mysqli_query($connect,$query);
	$d_s = mysqli_fetch_object($result);
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pendaftaran_instruktur'";
	$result = mysqli_query($connect,$query);
	$d_i = mysqli_fetch_object($result);
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'penjadwalan_santri'";
	$result = mysqli_query($connect,$query);
	$j_s = mysqli_fetch_object($result);
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'penjadwalan_instruktur'";
	$result = mysqli_query($connect,$query);
	$j_i = mysqli_fetch_object($result);
?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Dasbor
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i>SIM LPQ</a></li>
          <li class="active">Dasbor</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
		<form action="dasbor.php" method="post">
		<?php if(!$e && !$o) { ?>
        <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Login berhasil!</h4>
				Anda berhasil masuk ke dasbor.
        </div>
		<?php } ?>
		<?php if($e) { ?>
		<div class="callout callout-danger">
                <h4><i class="icon fa fa-ban"></i>&nbsp;&nbsp;&nbsp;Kesalahan!</h4>
                <p>Pengaturan sistem gagal diubah.</p>
		</div>
		<?php } ?>
		<?php if($o) { ?>
		<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Sukses!</h4>
				<p>Pengaturan sistem berhasil diubah.</p>
        </div>
		<?php } ?>		
		<h4>Pengaturan Sistem</h4>
		<div class="callout callout-info">
                <h4><i class="icon fa fa-info"></i>&nbsp;&nbsp;&nbsp;Pengumuman Santri</h4>
                <p><textarea name="ps" style="color: #000; width: 100%; height: 20%" required><?php echo $p_s->isi; ?></textarea></p>
        </div>
		<div class="callout callout-info">
                <h4><i class="icon fa fa-info"></i>&nbsp;&nbsp;&nbsp;Pengumuman Instruktur</h4>
                <p><textarea name="pi" style="color: #000; width: 100%; height: 20%" required><?php echo $p_i->isi; ?></textarea></p>
        </div>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Pembukaan Formulir</h3>
          </div>
          <div class="box-body">
            <ul>
				<label>Pendaftaran Santri:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
				<select name="ds" required>
					<option value="1">Dibuka</option>
					<option value="0"<?php if($d_s->isi==0) echo " selected"; ?>>Ditutup</option>
				</select name="di">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label>Pendaftaran Instruktur:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
				<select name="di" required>
					<option value="1">Dibuka</option>
					<option value="0"<?php if($d_i->isi==0) echo " selected"; ?>>Ditutup</option>
				</select><br />
				<label>Penjadwalan Santri:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
				<select name="js" required>
					<option value="1">Dibuka</option>
					<option value="0"<?php if($j_s->isi==0) echo " selected"; ?>>Ditutup</option>
				</select name="ji" required>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label>Penjadwalan Instruktur:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
				<select name="ji" required>
					<option value="1">Dibuka</option>
					<option value="0"<?php if($j_i->isi==0) echo " selected"; ?>>Ditutup</option>
				</select>
			</ul>
          </div>
          <!-- /.box-body-->
        </div>
        <!-- /.box -->
		<input type="submit" class="btn btn-primary pull-right" value="Ubah Pengaturan" /><br /><br />
		</form>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
<?php include "../inc/footer_admin.php"; ?>