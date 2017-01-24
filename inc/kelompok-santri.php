<?php
	$query = "SELECT k.* from kelompok k, santri s WHERE s.id_santri = $id AND k.id_kelompok = s.id_kelompok ";
	$result = mysqli_query($connect, $query);
	if(mysqli_num_rows($result) < 1) {$e = TRUE;}
	else {
		$kelompok = mysqli_fetch_object($result);
		$query = "SELECT a.* from instruktur i, anggota a, kelompok k WHERE i.id_anggota = a.id_anggota AND i.id_instruktur = k.id_instruktur AND k.id_kelompok = ".$kelompok->id_kelompok;
		$result = mysqli_query($connect, $query);
		$instruktur = mysqli_fetch_object($result);
		$query = "SELECT a.* from santri s, anggota a WHERE s.id_anggota = a.id_anggota AND s.id_kelompok = ".$kelompok->id_kelompok;
		$result = mysqli_query($connect, $query);
	}
	$i = 0;
?>
<!-- Main content -->
      <section class="content">
		<?php if($e) { ?>
		<div class="callout callout-danger">
                <h4><i class="icon fa fa-ban"></i>&nbsp;&nbsp;&nbsp;Kesalahan!</h4>
                <p>Anda belum memilih jadwal.</p>
		</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		<?php } ?>
		<?php if(!$e) { ?>
		<div class="col-md-5">
			<h2 class="page-header">Kelompok Saya</h2>
			<div class="box box-default">
			  <div class="box-body table-condensed">
				<table class="table">
					<tr>
					  <th width="30%">Kelompok</th>
					  <td><?php echo $kelompok->id_kelompok; ?></td>
					</tr>
					<tr>
					  <th width="30%">Jenjang</th>
					  <td><?php echo $j; ?></td>
					</tr>
					<tr>
					  <th width="30%">Hari</th>
					  <td><?php echo $hari[$kelompok->hari]; ?></td>
					</tr>
					<tr>
					  <th width="30%">Waktu</th>
					  <td><?php echo substr($kelompok->waktu,0,5); ?></td>
					</tr>
					<tr>
					  <th width="30%">Jumlah Santri</th>
					  <td><?php echo 10-($kelompok->sisa); ?></td>
					</tr>
					<tr>
					  <th width="30%">Instruktur</th>
					  <td>
						<div class="col-md-12">
						  <!-- Widget: user widget style 1 -->
						  <div class="box box-widget widget-user-2">
							<!-- Add the bg color to the header using any of the bg-* classes -->
							<div class="widget-user-header bg-light-blue">
							  <div class="widget-user-image">
								<img class="img-circle" src="img/foto-profil/<?php echo $instruktur->foto_profil;?>" alt="<?php echo $instruktur->nama_lengkap; ?>">
							  </div>
							  <!-- /.widget-user-image -->
							  <h3 class="widget-user-username"><?php echo $instruktur->nama_lengkap; ?></h3>
							  <h5 class="widget-user-desc"><?php echo $instruktur->nomor_hp; ?></h5>
							</div>
						  </div>
						  <!-- /.widget-user -->
						</div>
						<!-- /.col -->
					  </td>
					</tr>
				</table>
			  </div>
			  <!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<div class="col-md-7">
			<h2 class="page-header">Santri</h2>
			<div class="box">
				<div class="box-body table-responsive no-padding">
					  <table class="table table-hover">
						<tr>
						  <th>No.</th>
						  <th>Nama Lengkap</th>
						  <th>Nomor HP</th>
						  <th>Nomor Identitas</th>
						  <th>Foto</th>
						</tr>
						<?php while($data = mysqli_fetch_object($result)) { $i=1; ?>
						<tr>
						  <td><?php echo $i++; ?></td>
						  <td><?php echo $data->nama_lengkap; ?></td>
						  <td><?php echo $data->nomor_hp; ?></td>
						  <td><?php echo $data->id_status; ?></td>
						  <td>
							<img class="img-circle" src="img/foto-profil/<?php echo $data->foto_profil;?>" alt="<?php echo $data->nama_lengkap; ?>" style="max-width: 50px">
						  </td>
						</tr>
						<?php } ?>
					  </table>
					</div>
			</div>
			<!-- /.box -->
		</div>
		<?php } ?>
      </section>
      <!-- /.content -->