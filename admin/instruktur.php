<?php
	include "../inc/header_admin.php";
	
	$hari = array(1=>'Minggu',2=>'Senin',3=>'Selasa',4=>'Rabu',5=>'Kamis',6=>'Jumat',7=>'Sabtu');
	$prog = array(1=>'Tahsin',2=>'Tahfizh/Takhosus',3=>'Bahasa Arab');
	$jenj = array(
				1=>array('Belum dites','Pra-Tahsin','Tahsin 1','Tahsin 2'),
				2=>array('Belum dites','Takhosus','Tahfizh'),
				3=>array('Belum dites','Pemula','Tingkat 1'));
	
	if(isset($_SESSION['o'])) { $o = $_SESSION['o']; unset($_SESSION['o']); }
	if(isset($_SESSION['e'])) { $e = $_SESSION['e']; unset($_SESSION['e']); }
?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Instruktur
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i>SIM LPQ</a></li>
          <li class="active">Instruktur</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
<?php
	if(isset($e)) { ?>
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-ban"></i> Kesalahan!</h4>
					Jenjang instruktur gagal diubah. Pastikan tidak ada santri yang masuk ke kelompok instruktur tersebut. (id 	 = <?php echo $e; ?>)
				</div>
<?php
	}
?>
<?php
	if(isset($o)) { ?>
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-check"></i> Sukses!</h4>
					<?php echo $o; ?> jenjang instruktur berhasil diubah.
				</div>
<?php
	}
?>

				<div class="alert alert-warning alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-warning"></i> Peringatan!</h4>
					Pengubahan jenjang instruktur akan menghapus data penjadwalan instruktur tersebut (pada program yang bersangkutan).
				</div>
			<div class="row">
				<div class="col-md-12">
					<!-- Custom Tabs -->
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
						  <li class="active"><a href="#tab_1" data-toggle="tab">Laki-Laki</a></li>
						  <li><a href="#tab_2" data-toggle="tab">Perempuan</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab_1">
								<div class="box-body table-responsive no-padding">
									<!-- Custom Tabs -->
									<div class="nav-tabs-custom">
										<ul class="nav nav-tabs">
<?php
	foreach($prog as $key=>$value) {
?>
										  <li<?php if($key==1) echo' class="active"'; ?>><a href="<?php echo"#tab_1_$key"; ?>" data-toggle="tab"><?php echo"$prog[$key]"; ?></a></li>
<?php
	}
?>
										</ul>
										<div class="tab-content">
<?php
	foreach($prog as $key=>$value) {
?>
											<div class="tab-pane<?php if($key==1) echo' active'; ?>" id="<?php echo"tab_1_$key"; ?>">
												<div class="box-body table-responsive no-padding">
												  <form action="instruktur_proses.php" method="post">
												  <input type="hidden" name="post" />
												  <input type="hidden" name="pr" value="<?php echo $key; ?>" />
<?php
		$query = "SELECT * FROM instruktur i, anggota a, program p WHERE i.id_anggota = a.id_anggota AND p.id_anggota = a.id_anggota AND jenis_kelamin = 1 AND p.program = $key AND p.keanggotaan = 2 ORDER by nama_lengkap";
		$result = mysqli_query($connect,$query);
?>
													<table class="table table-hover table-responsive">
														<tr>
															<th>No.</th>
															<th>Nama Lengkap</th>
															<th>Nomor Identitas</th>
															<th>Jenjang</th>
														</tr>
<?php
		$a = 0;
		while($data = mysqli_fetch_object($result)) {
			$a++;
?>
														<tr>
															<td><?php echo $a; ?></td>
															<td><?php echo $data->nama_lengkap; ?></td>
															<td><?php echo $data->id_status; ?></td>
															<td>
																<input type="hidden" name="j_lama[<?php echo $data->id_instruktur; ?>]" value="<?php echo $data->jenjang; ?>" />
																<select name="j[<?php echo $data->id_instruktur; ?>]">
<?php
			foreach($jenj[$key] as $key2 => $value2) {
?>
																	<option value="<?php echo $key2 ?>"<?php if($key2 == $data->jenjang) echo " selected"; ?>><?php echo $jenj[$key][$key2]; ?></option>
<?php
			}
?>
																</select>
															</td>
														</tr>
<?php
		}
?>
														<tr>
															<td colspan="7">
																<?php if(mysqli_num_rows($result) > 0 ) { ?><input type="submit" class="btn btn-warning pull-right" value="Ubah"/><?php } ?>
															</td>
														</tr>
													</table>
												  </form>
												</div>
											</div>
<?php
	}
?>
											<!-- /.tab-pane -->
										</div>
										<!-- /.tab-content -->
									</div>
									<!-- nav-tabs-custom -->
								</div>
							</div>
							<div class="tab-pane" id="tab_2">
								<div class="box-body table-responsive no-padding">
									<!-- Custom Tabs -->
									<div class="nav-tabs-custom">
										<ul class="nav nav-tabs">
<?php
	foreach($prog as $key=>$value) {
?>
										  <li<?php if($key==1) echo' class="active"'; ?>><a href="<?php echo"#tab_2_$key"; ?>" data-toggle="tab"><?php echo"$prog[$key]"; ?></a></li>
<?php
	}
?>
										</ul>
										<div class="tab-content">
<?php
	foreach($prog as $key=>$value) {
?>
											<div class="tab-pane<?php if($key==1) echo' active'; ?>" id="<?php echo"tab_2_$key"; ?>">
												<div class="box-body table-responsive no-padding">
												  <form action="instruktur_proses.php" method="post">
												  <input type="hidden" name="post" />
												  <input type="hidden" name="pr" value="<?php echo $key; ?>" />
<?php
		$query = "SELECT * FROM instruktur i, anggota a, program p WHERE i.id_anggota = a.id_anggota AND p.id_anggota = a.id_anggota AND jenis_kelamin = 2 AND p.program = $key AND p.keanggotaan = 2 ORDER by nama_lengkap";
		$result = mysqli_query($connect,$query);
?>
													<table class="table table-hover table-responsive">
														<tr>
															<th>No.</th>
															<th>Nama Lengkap</th>
															<th>Nomor Identitas</th>
															<th>Jenjang</th>
														</tr>
<?php
		$a = 0;
		while($data = mysqli_fetch_object($result)) {
			$a++;
?>
														<tr>
															<td><?php echo $a; ?></td>
															<td><?php echo $data->nama_lengkap; ?></td>
															<td><?php echo $data->id_status; ?></td>
															<td>
																<input type="hidden" name="j_lama[<?php echo $data->id_instruktur; ?>]" value="<?php echo $data->jenjang; ?>" />
																<select name="j[<?php echo $data->id_instruktur; ?>]">
<?php
			foreach($jenj[$key] as $key2 => $value2) {
?>
																	<option value="<?php echo $key2 ?>"<?php if($key2 == $data->jenjang) echo " selected"; ?>><?php echo $jenj[$key][$key2]; ?></option>
<?php
			}
?>
																</select>
															</td>
														</tr>
<?php
		}
?>
														<tr>
															<td colspan="7">
																<?php if(mysqli_num_rows($result) > 0 ) { ?><input type="submit" class="btn btn-warning pull-right" value="Ubah"/><?php } ?>
															</td>
														</tr>
													</table>
												  </form>
												</div>
											</div>
<?php
	}
?>
											<!-- /.tab-pane -->
										</div>
										<!-- /.tab-content -->
									</div>
									<!-- nav-tabs-custom -->
								</div>
							</div>
							<!-- /.tab-pane -->
						</div>
						<!-- /.tab-content -->
					</div>
					<!-- nav-tabs-custom -->
				</div>
				<!-- /.col -->
			</div>
			<div class="row">
				
			</div>
      </section>
      <!-- /.content -->
	</div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
<?php include "../inc/footer_admin.php"; ?>