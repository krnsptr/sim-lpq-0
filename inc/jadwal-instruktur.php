<?php
	$e=NULL;
	if(isset($_SESSION['e'])) { $e = $_SESSION['e']; unset($_SESSION['e']); }
	else if(isset($_SESSION['o'])) { $o = $_SESSION['o']; unset($_SESSION['o']); }
	
	$error = array('Penjadwalan instruktur telah ditutup.',
					'Anda belum mengikuti placement test.',
					'Jadwal gagal ditambahkan.',
					'Jadwal gagal diubah.',
					'Jadwal gagal dihapus.'
					);
	$success = array('Jadwal berhasil ditambahkan.',
					'Jadwal berhasil diubah.',
					'Jadwal berhasil dihapus.'
					);
	$keanggotaan = array(NULL,0,0,0);
	$query = "SELECT program, keanggotaan FROM program WHERE id_anggota =".$user->id_anggota;
	$result = mysqli_query($connect,$query);
	while($program = mysqli_fetch_object($result)) $keanggotaan[$program->program] = $program->keanggotaan;
	
	$hari = array(NULL,'Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu');
	$prog = array(NULL,'Tahsin','Tahfizh/Takhosus','Bahasa Arab');
	$jenj = array(NULL,
				array('Belum dites','Pra-Tahsin','Tahsin 1','Tahsin 2','Lulus'),
				array('Belum dites','Takhosus','Tahfizh','Lulus'),
				array('Belum dites','Pemula','Tingkat 1','Lulus'));
	$ida = $user->id_anggota;

	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'penjadwalan_instruktur'";
	$result = mysqli_query($connect,$query);
	$j_i = mysqli_fetch_object($result);
	if($j_i->isi == 0) { $e=0; }
	
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_instruktur'";
	$result = mysqli_query($connect,$query);
	$p_i = mysqli_fetch_object($result);
?>
<section class="content">
		<div class="row">
				<div class="callout callout-info col-md-6">
						<h4><i class="icon fa fa-info"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pengumuman</h4>
						<p><?php echo $p_i->isi; ?></p>
				</div>
				<?php if(!is_null($e)) { ?>
				<div class="callout callout-danger col-md-6">
						<h4><i class="icon fa fa-ban"></i>&nbsp;&nbsp;&nbsp;Kesalahan!</h4>
						<p><?php echo $error[$e]; ?></p>
				</div>
				<?php } ?>
				<?php if(isset($o)) { ?>
				<div class="callout callout-success col-md-6">
					<h4><i class="icon fa fa-check"></i> Berhasil!</h4>
					<p><?php echo $success[$o]; ?></p>
				</div>
				<?php } ?>
		</div>
		<?php
			for($a=1; $a<=3; $a++) {
				if($keanggotaan[$a] == 2 && $e !== 0) {
					$query = "SELECT jenjang FROM program WHERE id_anggota = $ida AND keanggotaan = 2 AND program = $a";
					$result = mysqli_query($connect,$query);
					$data = mysqli_fetch_object($result);
					$jenjang = $data->jenjang;
		?>
		<h4><?php echo ($a==3) ? "Mu'alim " : "Instruktur "; echo $prog[$a]; ?></h4>
		<?php
					if($jenjang == 0) {
		?>
		<div class="row">
			<div class="callout callout-danger col-md-6">
				<h4><i class="icon fa fa-ban"></i>&nbsp;&nbsp;&nbsp;Kesalahan!</h4>
				<p><?php echo $error[1]; ?></p>
			</div>
		</div>
		<?php
					}
					else {
		?>
		<div class="row">
			<div class="col-md-6">
				<div class="box box-default">
				  <div class="box-header with-border">
					<h4 class="box-title">Tambah Kelompok</h4>
				  </div>
				  <div class="box-body">
					<form action="jadwal_instruktur.php" method="post">
					   <input type="hidden" name="tambah" />
					   <input type="hidden" name="pr" value="<?php echo $a; ?>" />
					   <div class="col-md-3">
						<label>Jenjang:</label><br />
						<select name="j" style="padding-top: 6px; padding-bottom: 6px; width: 100%">
								<?php for($b=1; $b<=$jenjang; $b++) { ?>
								<option value="<?php echo $b; ?>"<?php if($b==$jenjang) echo " selected"; ?>><?php echo $jenj[$a][$b]; ?></option>
								<?php } ?>
						</select>
					   </div>
					   <div class="col-md-3">
							<label>Hari:</label><br />
							<select name="h" style="padding-top: 6px; padding-bottom: 6px; width: 100%">
								<?php for($b=1; $b<=7; $b++) { ?>
								<option value="<?php echo $b; ?>"><?php echo $hari[$b]; ?></option>
								<?php } ?>
							</select>
					   </div>
					   <div class="col-md-3">
						<!-- time Picker -->
							  <div class="bootstrap-timepicker" style="margin-right: -5%">
								<div class="form-group">
								  <label>Waktu mulai:</label>
								  <div class="input-group">
									<input type="text" name="wm" class="form-control timepicker" value="05:00" data-show-meridian="false" data-default-time="05:00" data-minute-step="15">
									<div class="input-group-addon">
									  <i class="fa fa-clock-o"></i>
									</div>
								  </div>
								<!-- /.input group -->
								</div>
								<!-- /.form group -->
							  </div>
					   </div>
					   <div class="col-md-3" style="padding-top: 5%">
						<input type="submit" class="btn btn-flat btn-success" value="Tambah" />
					   </div>
					</form>
				 </div>
				 <!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<?php
						$query = "SELECT * FROM kelompok WHERE id_instruktur=$id AND program=$a";
						$result = mysqli_query($connect,$query);
						if(mysqli_num_rows($result) > 0) {
							while($kelompok = mysqli_fetch_object($result)) {
			?>
			<div class="col-md-6">
				<div class="box box-default">
				  <div class="box-header with-border">
					<h4 class="box-title">Kelompok <?php echo $kelompok->id_kelompok; ?></h4>
				  </div>
				  <div class="box-body table-condensed">
					<table class="table">
						<tr>
						  <th width="10%">Jenjang</th>
						  <td><?php echo $jenj[$a][$kelompok->jenjang] ?></td>
						</tr>
						<tr>
						  <th width="10%">Hari</th>
						  <td><?php echo $hari[$kelompok->hari] ?></td>
						</tr>
						<tr>
						  <th width="10%">Waktu</th>
						  <td><?php echo date('H:i', strtotime($kelompok->waktu)); ?></td>
						</tr>
						<tr>
						  <td colspan="2">
						  <div class="row" style="padding-left: 2%">
							<form action="jadwal_instruktur.php" method="post">
							   <input type="hidden" name="ubah" value="<?php echo $kelompok->id_kelompok; ?>" />
							   <div class="col-md-3">
								<label>Jenjang:</label><br />
								<select name="j" style="padding-top: 6px; padding-bottom: 6px; width: 100%">
										<?php for($b=1; $b<=$jenjang; $b++) { ?>
										<option value="<?php echo $b; ?>"<?php if($b == $kelompok->jenjang) echo " selected"; ?>><?php echo $jenj[$a][$b]; ?></option>
										<?php } ?>
								</select>
							   </div>
							   <div class="col-md-2">
									<label>Hari:</label><br />
									<select name="h" style="padding-top: 6px; padding-bottom: 6px; width: 100%">
										<?php for($b=1; $b<=7; $b++) { ?>
										<option value="<?php echo $b; ?>"<?php if($kelompok->hari == $b) echo " selected"; ?>><?php echo $hari[$b]; ?></option>
										<?php } ?>
									</select>
							   </div>
							   <div class="col-md-3">
								<!-- time Picker -->
									  <div class="bootstrap-timepicker">
											<div class="form-group">
										  <label>Waktu mulai:</label>
										  <div class="input-group">
											<input type="text" name="wm" class="form-control timepicker" value="<?php echo date('H:i', strtotime($kelompok->waktu)); ?>" data-show-meridian="false" data-default-time="" data-minute-step="15">
											<div class="input-group-addon">
											  <i class="fa fa-clock-o"></i>
											</div>
										  </div>
										  <!-- /.input group -->
										</div>
										<!-- /.form group -->
									  </div>
							   </div>
							   <div class="col-md-4" style="padding-top: 6%">
								<input type="submit" class="btn-sm btn-primary" value="Ubah" />
								<a href="jadwal_instruktur.php?hapus=<?php echo $kelompok->id_kelompok; ?>" class="btn-sm btn-danger" style="padding: 10px">Hapus</a>
							   </div>
							</form>
						  </div>
						  </td>
						</tr>
					</table>
				  </div>
				  <!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<?php
							}
						}
			?>
		</div>
		<?php
					}
				}
			}
		?>
      </section>