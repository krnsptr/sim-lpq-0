 <?php
	$query = "SELECT * FROM kelompok WHERE id_instruktur = $id";
	$result = mysqli_query($connect, $query);
	if(mysqli_num_rows($result) < 1) $e = 1;
 ?>
 <!-- Main content -->
      <section class="content">
		<?php if($e == 1) { ?>
		<div class="callout callout-danger">
                <h4><i class="icon fa fa-ban"></i>&nbsp;&nbsp;&nbsp;Kesalahan!</h4>
                <p>Anda belum memilih jadwal.</p>
		</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		<?php } ?>
		<?php
			if($e != 1) {
				while($kelompok = mysqli_fetch_object($result)) {
				$i = 1;
		?>
		<div class="row">
			<div class="col-md-3">
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
					</table>
				  </div>
				  <!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<?php
			$query = "SELECT a.* FROM santri s, anggota a WHERE s.id_anggota = a.id_anggota AND id_kelompok = ".$kelompok->id_kelompok;
			$result2 = mysqli_query($connect, $query);
			if(mysqli_num_rows($result2) < 1) $e = 2; else $e = NULL;
			?>
			<div class="col-md-9">
				<div class="box">
					<div class="box-body table-responsive no-padding">
						  <table class="table table-hover">
							<tr>
							  <th>No.</th>
							  <th>Nama Santri</th>
							  <th>Nomor HP</th>
							  <th>Nomor Identitas</th>
							  <th>Alamat Tinggal</th>
							  <th>Foto</th>
							</tr>
							<?php if($e == 2) { ?>
							<tr><td colspan="6" align="center">Belum ada santri.</td></tr>
							<?php } ?>
							<?php
								if($e != 2) {
									while($santri = mysqli_fetch_object($result2)) {
							?>
							<tr>
							  <td><?php echo $i++; ?></td>
							  <td><?php echo $santri->nama_lengkap; ?></td>
							  <td><?php echo $santri->nomor_hp; ?></td>
							  <td><?php echo $santri->id_status; ?></td>
							  <td><?php echo $santri->alamat; ?></td>
							  <td>
								<img class="img-circle" src="img/foto-profil/<?php echo $santri->foto_profil;?>" alt="<?php echo $santri->nama_lengkap; ?>" style="max-width: 50px" />
							  </td>
							</tr>
							<?php
									}
								}
							?>
						  </table>
						</div>
				</div>
				<!-- /.box -->
			</div>
		</div>
		<?php 
				}
			}
		?>
      </section>
      <!-- /.content -->