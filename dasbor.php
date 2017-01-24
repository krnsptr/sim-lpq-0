<?php
	include "inc/header.php";
	if(isset($_SESSION['o'])) { $o = $_SESSION['o']; unset($_SESSION['o']); }
	else if(isset($_SESSION['e'])) { $e = $_SESSION['e']; unset($_SESSION['e']); }
	
	$keanggotaan = array(NULL,0,0,0);
	$query = "SELECT program, keanggotaan FROM program WHERE id_anggota =".$user->id_anggota;
	$result = mysqli_query($connect,$query);
	while($program = mysqli_fetch_object($result)) $keanggotaan[$program->program] = $program->keanggotaan;
	
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_santri'";
	$result = mysqli_query($connect,$query);
	$p_s = mysqli_fetch_object($result);
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_instruktur'";
	$result = mysqli_query($connect,$query);
	$p_i = mysqli_fetch_object($result);
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
		<?php if(isset($o) && $o == 0) { ?>
        <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
				Anda berhasil masuk ke dasbor.
        </div>
		<?php } ?>
		<?php if(isset($o) && $o == 1) { ?>
        <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
				Program berhasil diperbarui.
        </div>
		<?php } ?>
		<?php if(isset($e)) { ?>
        <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Kesalahan!</h4>
				Program gagal diperbarui.
        </div>
		<?php } ?>
		<div class="callout callout-info">
                <h4><i class="icon fa fa-info"></i>&nbsp;&nbsp;&nbsp;Pengumuman</h4>
                <p><?php echo ($i) ? $p_i->isi : $p_s->isi; ?></p>
        </div>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Petunjuk Singkat</h3>
          </div>
          <div class="box-body">
            <ul>
				<li>Untuk memilih jadwal, klik menu <b>Jadwal</b>.</li>
				<li><s>Untuk melihat kelompok, klik menu <b>Kelompok</b>.</s> (dalam perbaikan)</li>
				<li>Untuk melihat/mengubah profil atau keluar sistem (logout), klik foto profil di pojok kanan atas.</li>
				<li><s>Untuk melihat jadwal KBM LPQ keseluruhan, klik menu <b>Jadwal KBM</b>.</s> (dalam perbaikan)</li>
			</ul>
          </div>
          <!-- /.box-body -->
        </div>
		<div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Program</h3>
          </div>
          <div class="box-body table-condensed">
            <table class="table">
					<th>Program</th>
					<th>Keanggotaan</th>
					<th></th>
				</tr>
				<tr>
				  <th width="25%">Tahsin</th>
				  <td><?php if($keanggotaan[1] == 2) echo "Instruktur"; else if($keanggotaan[1] == 1) echo "Santri"; else echo "Tidak terdaftar" ?></td>
				  <td>
					<?php if($keanggotaan[1] == 2 || $keanggotaan[1] == 1) echo '<a href="program_ubah.php?program=1"><button class="btn btn-primary">Ubah data</button></a> <a href="program_hapus.php?program=1"><button class="btn btn-danger">Hapus</button></a>';
					else echo '<a href="program_daftar.php?program=1"><button class="btn btn-success">Daftar</button></a>'; ?>
				  </td>
				</tr>
				<tr>
				  <th width="25%">Tahfizh/Takhosus</th>
				  <td><?php if($keanggotaan[2] == 2) echo "Instruktur"; else if($keanggotaan[2] == 1) echo "Santri"; else echo "Tidak terdaftar" ?></td>
				  <td>
					<?php if($keanggotaan[2] == 2 || $keanggotaan[2] == 1) echo '<a href="program_ubah.php?program=2"><button class="btn btn-primary">Ubah data</button></a> <a href="program_hapus.php?program=2"><button class="btn btn-danger">Hapus</button></a>';
					else echo '<a href="program_daftar.php?program=2"><button class="btn btn-success">Daftar</button></a>'; ?>
				  </td>
				</tr>
				<tr>
				  <th width="25%">Bahasa Arab</th>
				  <td><?php if($keanggotaan[3] == 2) echo "Instruktur"; else if($keanggotaan[3] == 1) echo "Santri"; else echo "Tidak terdaftar" ?></td>
				  <td>
					<?php if($keanggotaan[3] == 2 || $keanggotaan[3] == 1) echo '<a href="program_ubah.php?program=3"><button class="btn btn-primary">Ubah data</button></a> <a href="program_hapus.php?program=3"><button class="btn btn-danger">Hapus</button></a>';
					else echo '<a href="program_daftar.php?program=3"><button class="btn btn-success">Daftar</button></a>'; ?>
				  </td>
				</tr>
				<tr>
					<td colspan="3">
						<ul>
							<li>Anda tidak dapat menjadi instruktur sekaligus santri pada program yang sama.</li>
							<li>Untuk mengubah keanggotaan, Anda harus menghapus dan mendaftar ulang program yang bersangkutan.</li>
							<li>Penghapusan program akan menghapus data penjadwalan dan pengelompokan.</li>
						<ul>
					</td>
				</tr>
				<tr>
			</table>
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