<?php
	include "../inc/header_admin.php";
	
	$hari = array(1=>'Minggu',2=>'Senin',3=>'Selasa',4=>'Rabu',5=>'Kamis',6=>'Jumat',7=>'Sabtu');
	$prog = array(1=>'Tahsin',2=>'Tahfizh/Takhosus',3=>'Bahasa Arab');
	$jenj = array(
				1=>array('Belum dites','Pra-Tahsin','Tahsin 1','Tahsin 2','Lulus'),
				2=>array('Belum dites','Takhosus','Tahfizh','Lulus'),
				3=>array('Belum dites','Pemula','Tingkat 1','Lulus'));
	$sl = array('Belum pernah mengikuti Tahsin','Belum lulus Pra-Tahsin (mengulang)','Lulus Pra-Tahsin','Belum lulus Tahsin 1 (mengulang)','Lulus Tahsin 1','Belum lulus Tahsin 2 (mengulang)','Lulus Tahsin 2','Tahfizh/Takhosus (melanjutkan)');
	
	if(isset($_SESSION['o1'])) { $o1 = $_SESSION['o1']; unset($_SESSION['o1']); }
	if(isset($_SESSION['o2'])) { $o2 = $_SESSION['o2']; unset($_SESSION['o2']); }
	if(isset($_SESSION['e1'])) { $e1 = $_SESSION['e1']; unset($_SESSION['e1']); }
	if(isset($_SESSION['e2'])) { $e2 = $_SESSION['e2']; unset($_SESSION['e2']); }
?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Santri
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i>SIM LPQ</a></li>
          <li class="active">Santri</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
<?php
	if(isset($e1)) { ?>
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-ban"></i> Kesalahan!</h4>
					Jenjang santri gagal diubah.
				</div>
<?php
	}
?>
<?php
	if(isset($e2)) { ?>
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-ban"></i> Kesalahan!</h4>
					Kelompok santri gagal diubah.
				</div>
<?php
	}
?>
<?php
	if(isset($o1)) { ?>
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-check"></i> Sukses!</h4>
					<?php echo $o1; ?> jenjang santri berhasil diubah.
				</div>
<?php
	}
?>
<?php
	if(isset($o2)) { ?>
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-check"></i> Sukses!</h4>
					<?php echo $o2; ?> kelompok santri berhasil diubah.
				</div>
<?php
	}
?>
				<div class="alert alert-warning alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-warning"></i> Peringatan!</h4>
					Pengubahan jenjang santri akan menghapus data penjadwalan dan pengelompokan santri tersebut (pada program yang bersangkutan).
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
												  <form action="santri_proses.php" method="post">
												  <input type="hidden" name="post" />
												  <input type="hidden" name="pr" value="<?php echo $key; ?>" />
<?php
		$query = "SELECT * FROM santri s, anggota a, program p, pertanyaan_santri ps WHERE s.id_anggota = a.id_anggota AND p.id_anggota = a.id_anggota AND ps.id_santri = s.id_santri AND ps.program = p.program AND jenis_kelamin = 1 AND p.program = $key AND p.keanggotaan = 1 ORDER by jenjang, jawaban1, nama_lengkap";
		$result = mysqli_query($connect,$query);
?>
													<table class="table table-hover table-responsive">
														<tr>
															<th>No.</th>
															<th>Nama Lengkap</th>
															<th>Nomor Identitas</th>
															<th><?php echo ($key != 3) ? 'Sudah Lulus?' : 'Pengalaman belajar (tahun)'; ?></th>
															<th>Jenjang</th>
															<th>Jadwal</th>
															<th>Kelompok</th>
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
															<td><?php echo ($key !=3) ? $sl[$data->jawaban1] : $data->jawaban1; ?></td>
															<td>
																<input type="hidden" name="j_lama[<?php echo $data->id_santri; ?>]" value="<?php echo $data->jenjang; ?>" />
																<select name="j[<?php echo $data->id_santri; ?>]">
<?php
			foreach($jenj[$key] as $key2 => $value2) {
?>
																	<option value="<?php echo $key2 ?>"<?php if($key2 == $data->jenjang) echo " selected"; ?>><?php echo $jenj[$key][$key2]; ?></option>
<?php
			}
?>
																</select>
															</td>
<?php
				$jadwal = NULL;
				$query2 = "SELECT * FROM penjadwalan_santri WHERE program = $key AND id_santri = ".$data->id_santri;
				$result2 = mysqli_query($connect,$query2);
				if(mysqli_num_rows($result2) > 0) $jadwal = mysqli_fetch_object($result2);
?>
															<td><?php if(!is_null($jadwal)) echo $hari[$jadwal->hari].' '.date('H:i', strtotime($jadwal->waktu)); else echo '(belum dipilih)';?></td>
															<td>
<?php
			if(!is_null($jadwal)) {
				$query2 = "SELECT id_kelompok FROM penjadwalan_santri WHERE program = $key AND id_kelompok IS NOT NULL AND id_santri = ".$data->id_santri;
				$result2 = mysqli_query($connect,$query2);
				$kelompok = mysqli_fetch_object($result2);
?>
																<input type="hidden" name="k_lama[<?php echo $data->id_santri; ?>]" value="<?php if(!empty($kelompok->id_kelompok)) echo $kelompok->id_kelompok; ?>" />
																<select name="k[<?php echo $data->id_santri; ?>]">
																	<option value="">belum ditentukan</option>
<?php
				mysqli_begin_transaction($connect);
				$query2 = "SELECT id_kelompok,nama_lengkap,kuota FROM kelompok k, instruktur i, anggota a WHERE k.id_instruktur = i.id_instruktur AND i.id_anggota = a.id_anggota AND jenis_kelamin = 1 AND program = $key AND jenjang = ".$data->jenjang." AND hari = ".$jadwal->hari." AND waktu = '".$jadwal->waktu."'";
				$result2 = mysqli_query($connect,$query2);
				while($data2 = mysqli_fetch_object($result2)) {
					$select = FALSE;
					$query3 = "SELECT id_kelompok FROM penjadwalan_santri WHERE program = $key AND id_kelompok IS NOT NULL AND id_santri = ".$data->id_santri;
					$result3 = mysqli_query($connect,$query3);
					if(mysqli_num_rows($result3) > 0) {$data3 = mysqli_fetch_object($result3); $idk = $data3->id_kelompok;};
					$query3 = "SELECT COUNT(*) as jml_milih FROM penjadwalan_santri WHERE id_kelompok = ".$data2->id_kelompok;
					$result3 = mysqli_query($connect,$query3);
					$data3 = mysqli_fetch_object($result3);
					$data2->kuota -= $data3->jml_milih;
					if(!empty($idk) && $idk == $data2->id_kelompok) {$select = TRUE; $data2->kuota += 1;}
?>
																	<option value="<?php echo $data2->id_kelompok; ?>"<?php if($select) {echo " selected";} ?>><?php echo $data2->id_kelompok.' - '.$data2->nama_lengkap. ' (sisa '.$data2->kuota.')'; ?></option>
<?php
					unset($idk);
				}
				mysqli_commit($connect);
?>
																</select>
															</td>
														</tr>
<?php
			}
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
												  <form action="santri_proses.php" method="post">
												  <input type="hidden" name="post" />
												  <input type="hidden" name="pr" value="<?php echo $key; ?>" />
<?php
		$query = "SELECT * FROM santri s, anggota a, program p, pertanyaan_santri ps WHERE s.id_anggota = a.id_anggota AND p.id_anggota = a.id_anggota AND ps.id_santri = s.id_santri AND ps.program = p.program AND jenis_kelamin = 2 AND p.program = $key AND p.keanggotaan = 1 ORDER by jenjang, jawaban1, nama_lengkap";
		$result = mysqli_query($connect,$query);
?>
													<table class="table table-hover table-responsive">
														<tr>
															<th>No.</th>
															<th>Nama Lengkap</th>
															<th>Nomor Identitas</th>
															<th><?php echo ($key != 3) ? 'Sudah Lulus?' : 'Pengalaman belajar (tahun)'; ?></th>
															<th>Jenjang</th>
															<th>Jadwal</th>
															<th>Kelompok</th>
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
															<td><?php echo ($key !=3) ? $sl[$data->jawaban1] : $data->jawaban1; ?></td>
															<td>
																<input type="hidden" name="j_lama[<?php echo $data->id_santri; ?>]" value="<?php echo $data->jenjang; ?>" />
																<select name="j[<?php echo $data->id_santri; ?>]">
<?php
			foreach($jenj[$key] as $key2 => $value2) {
?>
																	<option value="<?php echo $key2 ?>"<?php if($key2 == $data->jenjang) echo " selected"; ?>><?php echo $jenj[$key][$key2]; ?></option>
<?php
			}
?>
																</select>
															</td>
<?php
				$jadwal = NULL;
				$query2 = "SELECT * FROM penjadwalan_santri WHERE program = $key AND id_santri = ".$data->id_santri;
				$result2 = mysqli_query($connect,$query2);
				if(mysqli_num_rows($result2) > 0) $jadwal = mysqli_fetch_object($result2);
?>
															<td><?php if(!is_null($jadwal)) echo $hari[$jadwal->hari].' '.date('H:i', strtotime($jadwal->waktu)); else echo '(belum dipilih)';?></td>
															<td>
<?php
			if(!is_null($jadwal)) {
				$query2 = "SELECT id_kelompok FROM penjadwalan_santri WHERE program = $key AND id_kelompok IS NOT NULL AND id_santri = ".$data->id_santri;
				$result2 = mysqli_query($connect,$query2);
				$kelompok = mysqli_fetch_object($result2);
?>
																<input type="hidden" name="k_lama[<?php echo $data->id_santri; ?>]" value="<?php if(!empty($kelompok->id_kelompok)) echo $kelompok->id_kelompok; ?>" />
																<select name="k[<?php echo $data->id_santri; ?>]">
																	<option value="">belum ditentukan</option>
<?php
				mysqli_begin_transaction($connect);
				$query2 = "SELECT id_kelompok,nama_lengkap,kuota FROM kelompok k, instruktur i, anggota a WHERE k.id_instruktur = i.id_instruktur AND i.id_anggota = a.id_anggota AND jenis_kelamin = 2 AND program = $key AND jenjang = ".$data->jenjang." AND hari = ".$jadwal->hari." AND waktu = '".$jadwal->waktu."'";
				$result2 = mysqli_query($connect,$query2);
				while($data2 = mysqli_fetch_object($result2)) {
					$select = FALSE;
					$query3 = "SELECT id_kelompok FROM penjadwalan_santri WHERE program = $key AND id_kelompok IS NOT NULL AND id_santri = ".$data->id_santri;
					$result3 = mysqli_query($connect,$query3);
					if(mysqli_num_rows($result3) > 0) {$data3 = mysqli_fetch_object($result3); $idk = $data3->id_kelompok;};
					$query3 = "SELECT COUNT(*) as jml_milih FROM penjadwalan_santri WHERE id_kelompok = ".$data2->id_kelompok;
					$result3 = mysqli_query($connect,$query3);
					$data3 = mysqli_fetch_object($result3);
					$data2->kuota -= $data3->jml_milih;
					if(!empty($idk) && $idk == $data2->id_kelompok) {$select = TRUE; $data2->kuota += 1;}
?>
																	<option value="<?php echo $data2->id_kelompok; ?>"<?php if($select) {echo " selected";} ?>><?php echo $data2->id_kelompok.' - '.$data2->nama_lengkap. ' (sisa '.$data2->kuota.')'; ?></option>
<?php
					unset($idk);
				}
				mysqli_commit($connect);
?>
																</select>
															</td>
														</tr>
<?php
			}
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