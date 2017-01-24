<?php
	$e=NULL;
	if(isset($_SESSION['e'])) { $e = $_SESSION['e']; unset($_SESSION['e']); }
	else if(isset($_SESSION['o'])) { $o = $_SESSION['o']; unset($_SESSION['o']); }
	
	$error = array('Penjadwalan santri telah ditutup.',
					'Anda belum mengikuti placement test.',
					'Jadwal gagal diubah.',
					);
	$success = 'Jadwal berhasil diubah.';
	
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

	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'penjadwalan_santri'";
	$result = mysqli_query($connect,$query);
	$j_s = mysqli_fetch_object($result);
	if($j_s->isi == 0) { $e=0; }

	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_santri'";
	$result = mysqli_query($connect,$query);
	$p_s = mysqli_fetch_object($result);
?>
<section class="content">
	   <div class="col-md-6">
		<div class="callout callout-info">
                <h4><i class="icon fa fa-info"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pengumuman</h4>
                <p><?php echo $p_s->isi; ?></p>
        </div>
		<?php if(!is_null($e)) { ?>
		<div class="callout callout-danger">
                <h4><i class="icon fa fa-ban"></i>&nbsp;&nbsp;&nbsp;Kesalahan!</h4>
                <p><?php echo $error[$e]; ?></p>
        </div>
		<?php } ?>
		<?php if(isset($o)) { ?>
		<div class="callout callout-success">
                <h4><i class="icon fa fa-check"></i>&nbsp;&nbsp;&nbsp;Berhasil!</h4>
                <p><?php echo $success; ?></p>
        </div>
		<?php } ?>
		
	   </div>
	   <?php 
		for($a=1; $a<=3; $a++) {
				if($keanggotaan[$a] == 1 && $e !== 0) {
					$query = "SELECT jenjang FROM program WHERE id_anggota = $ida AND keanggotaan = 1 AND program = $a";
					$result = mysqli_query($connect,$query);
					$data = mysqli_fetch_object($result);
					$jenjang = $data->jenjang;
		?>
	   <div class="col-md-6">
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo "Santri ".$prog[$a]; ?></h3>
          </div>
          <div class="box-body table-condensed">
			<?php
					if($jenjang == 0) {
			?>
				<div class="callout callout-danger ">
					<h4><i class="icon fa fa-ban"></i>&nbsp;&nbsp;&nbsp;Kesalahan!</h4>
					<p><?php echo $error[1]; ?></p>
				</div>
			<?php
					}
					else {
						$query = "SELECT * FROM penjadwalan_santri WHERE id_santri = $id AND program = $a";
						$result = mysqli_query($connect,$query);
						if(mysqli_num_rows($result) < 1) {$h ="(belum dipilih)"; $wm=$h; }
						else {
							$result = mysqli_query($connect,$query);
							$jadwal = mysqli_fetch_object($result);
							$h = $hari[$jadwal->hari];
							$wm = date('H:i', strtotime($jadwal->waktu));
						}
						$query = "SELECT h,w,sisa,jml_kelompok FROM penjadwalan_santri_view WHERE jk = ".$user->jenis_kelamin." AND pr = $a AND j = $jenjang";
						$result = mysqli_query($connect,$query);
			?>
            <table class="table">
				<tr>
                  <th width="25%">Jenjang</th>
                  <td><?php echo $jenj[$a][$jenjang]; ?></td>
                </tr>
                <tr>
                  <th width="25%">Hari</th>
                  <td><?php echo $h; ?></td>
                </tr>
                <tr>
                  <th width="25%">Waktu mulai</th>
                  <td><?php echo $wm; ?></td>
                </tr>
                <tr>
                  <th width="25%">Ubah Jadwal</th>
                  <td>
				  <div class="row" style="padding-left: 5%">
					<form action="jadwal_santri.php" method="post">
						<input type="hidden" name="pr" value = "<?php echo $a; ?>" />
						<select name="j" style="padding: 2%; max-width: 95%" required>
							<?php
							while ($data = mysqli_fetch_object($result)) {
								$selected = FALSE;
								$h = $hari[$data->h]; $wm = date('H:i', strtotime($data->w));
								if(isset($jadwal) && $data->h == $jadwal->hari && $data->w == $jadwal->waktu) { $data->sisa += 1; $selected = TRUE; }
							?>
							<option value="<?php echo $data->h."-".$data->w; ?>"<?php if($selected) echo " selected"; ?>><?php echo "$h $wm sisa ".$data->sisa." (".$data->jml_kelompok." kelompok)"; ?></option>
							<?php
							}
							?>
						</select>
						<input type="submit" class="btn btn-primary" style="margin-top: -0.7%" value="Pilih" />
					</form>
				  </div>
				  </td>
                </tr>
              </table>
			<?php
						unset($jadwal);
						$selected = FALSE;
						mysqli_commit($connect);
					}
			?>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
	   </div>
	   <?php
				}
	   }
	   ?>
</section>