<?php
	require "inc/connect.php";
	require "inc/auth.php";
	
	$error = array("Password tidak cocok.");
	
	if($s) {	//login sebagai santri
		$query = "SELECT * FROM santri s,anggota a WHERE id_santri = '$id' AND a.id_anggota = s.id_anggota";
		$result = mysqli_query($connect,$query);
		$user = mysqli_fetch_object($result);
	}
	else if($i) {	//login sebagai instruktur
		$query = "SELECT * FROM instruktur i,anggota a WHERE id_instruktur = '$id' AND a.id_anggota = i.id_anggota";
		$result = mysqli_query($connect,$query);
		$user = mysqli_fetch_object($result);
	}
	
	$keanggotaan = array(NULL,0,0,0);
	$query = "SELECT program, keanggotaan FROM program WHERE id_anggota =".$user->id_anggota;
	$result = mysqli_query($connect,$query);
	while($program = mysqli_fetch_object($result)) $keanggotaan[$program->program] = $program->keanggotaan;
	
	if(!empty($_GET['program'])) {
		$prog = (int) $_GET['program'];
		if($keanggotaan[$prog] == 0) {
			header('Location: dasbor.php');
			exit();
		}
	}
	else {
		header('Location: dasbor.php'); exit();
	}
	
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_santri'";
	$result = mysqli_query($connect,$query);
	$p_s = mysqli_fetch_object($result);
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_instruktur'";
	$result = mysqli_query($connect,$query);
	$p_i = mysqli_fetch_object($result);
	
	include "inc/header.php";
?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Hapus Program
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i>SIM LPQ</a></li>
          <li class="active">Hapus Program</li>
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
			<?php if(isset($o)) { ?>
			<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-check"></i> Berhasil!</h4>
					<?php echo $success[$o]; ?>
			</div>
			<?php } ?>
			<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" haha-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Peringatan!</h4>
				Penghapusan program akan menghapus data penjadwalan dan pengelompokan.
			</div>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Konfirmasi Hapus Program</h3>
          </div>
          <div class="box-body">
			Anda yakin?<br /><br />
			<form action="program_hapus_proses.php" method="post" data-toggle="validator" role="form" enctype="multipart/form-data">
				<input type="hidden" name="post" />
				<input type="hidden" name="program" value="<?php echo $prog; ?>" />
				<input type="submit" value="Ya" class="btn btn-success" />
				<a href="dasbor.php" class="btn btn-danger">Tidak</a>
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