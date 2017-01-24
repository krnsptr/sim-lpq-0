<?php
include "inc/header.php";
$jk = (isset($_GET['jk']) && $_GET['jk'] == 2) ? 2 : 1;
?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Jadwal KBM
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i>SIM LPQ</a></li>
          <li class="active">Jadwal KBM</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
	   <div class="col-md-3"></div>
	   <div class="col-md-6">
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Cari Jadwal</h3>
          </div>
          <div class="box-body">
			<form action="jadwalkbm.php" method="GET">
				<label>Jenis Kelamin:</label>&nbsp;&nbsp;&nbsp;
				<select name="jk" style="width: 60%; padding-top: 6px; padding-bottom: 6px">
					<option value="1">Laki-Laki</option>
					<option value="2"<?php if($jk == 2) echo " selected";?>>Perempuan</option>
				</select>&nbsp;&nbsp;&nbsp;
				<input type="submit" class="btn btn-primary" value="Cari" />
			</form>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
	   </div>
	   <div class="col-md-3"></div>
	   <?php
			$j = array('Pra-Tahsin','Tahsin 1','Tahsin 2');
			for($i = 0; $i <= 2; $i++) {
	   ?>
	   <div class="col-md-12">
			<div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo $j[$i]; ?></h3>
          </div>
          <div class="box-body table-responsive no-padding">
			<table class="table table-hover">
				<tr>
					<th>Hari</th>
					<th>Waktu</th>
					<th>Kelompok</th>
					<th>Nama Instruktur</th>
					<th>Nomor HP Instruktur</th>
				</tr>
				<?php
				$query = "SELECT k.*, a.nama_lengkap, a.nomor_hp FROM kelompok k, instruktur i, anggota a WHERE i.id_instruktur = k.id_instruktur AND i.id_anggota = a.id_anggota AND a.jenis_kelamin = $jk AND jenjang = $i ORDER BY hari,waktu";
				$result = mysqli_query($connect, $query);
				if(mysqli_num_rows($result) < 1) echo '<tr><td colspan="5" align="center">Belum ada jadwal.</td></tr>';
				else {
					while($kelompok = mysqli_fetch_object($result)) {
				?>
				<tr>
					<td><?php echo $hari[$kelompok->hari]; ?></td>
					<td><?php echo substr($kelompok->waktu,0,5); ?></td>
					<td><?php echo $kelompok->id_kelompok; ?></td>
					<td><?php echo $kelompok->nama_lengkap; ?></td>
					<td><?php echo $kelompok->nomor_hp; ?></td>
				</tr>
				<?php
					}
				}
				?>
			</table>			
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
	   </div>
	   <?php } ?>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
<?php include "inc/footer.php"; ?>