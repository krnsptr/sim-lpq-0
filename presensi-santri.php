<?php
 $j = array(array('Pra-Tahsin','Tahsin 1','Tahsin 2'),array('Takhossus','Tahfizh'),array('Bahasa Arab Tingkat 0','Bahasa Arab Tingkat 1'));
 $h = array('Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu');
 require "inc/connect.php";
 $query = "SELECT * FROM jadwal_view ORDER by jenis_kelamin, program, jenjang, hari, waktu";
 $result = mysqli_query($connect, $query);
?>
<html>
 <head>
  <title>Presensi Santri LPQ Al-Hurriyyah Angkatan 11</title>
  <style type="text/css">
	body {
	  font-family: "Helvetica Neue", Helvetica, Arial;
	  font-size: 14px;
	  line-height: 20px;
	  font-weight: 400;
	  color: #3b3b3b;
	  -webkit-font-smoothing: antialiased;
	  font-smoothing: antialiased;
	}

	.wrapper {
	  margin: 0 auto;
	  padding: 40px;
	  max-width: 800px;
	}

	.table {
	  margin: 0 0 40px 0;
	  width: 100%;
	  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
	  display: table;
	}
	@media screen and (max-width: 580px) {
	  .table {
		display: block;
	  }
	}

	.row {
	  display: table-row;
	  background: #f6f6f6;
	}
	.row:nth-of-type(odd) {
	  background: #e9e9e9;
	}
	.row.header {
	  font-weight: 900;
	  color: #ffffff;
	  background: #ea6153;
	}
	.row.green {
	  background: #27ae60;
	}
	.row.blue {
	  background: #2980b9;
	}
	@media screen and (max-width: 580px) {
	  .row {
		padding: 8px 0;
		display: block;
	  }
	}

	.cell {
	  padding: 6px 12px;
	  display: table-cell;
	}
	@media screen and (max-width: 580px) {
	  .cell {
		padding: 2px 12px;
		display: block;
	  }
	}
  </style>
 </head>
 <body>
  <table class="table">
    <tr class="row header">
	 <th class="cell">Jenjang</th>
	 <th class="cell">Hari</th>
	 <th class="cell">Waktu</th>
	 <th class="cell">Nama Instruktur</th>
	 <th class="cell">Nomor HP</th>
<?php for($i=1; $i<=15; $i++) { ?>
	 <th class="cell">Santri <?php echo $i; ?></th>
	 <th class="cell">No. HP <?php echo $i; ?></th>
<?php } ?>
	</tr>
<?php
 while($data = mysqli_fetch_object($result)) {
?>
	<tr class="row">
	 <td class="cell"><?php echo $j[$data->program-1][$data->jenjang-1]; ?></td>
	 <td class="cell"><?php echo $h[$data->hari-1]; ?></td>
	 <td class="cell"><?php echo date('H:i', strtotime($data->waktu)); ?></td>
	 <td class="cell"><?php echo $data->nama_lengkap; ?></td>
	 <td class="cell">'<?php echo $data->nomor_hp; ?></td>
<?php
	$query2 = "SELECT nama_lengkap, nomor_hp FROM penjadwalan_santri ps, santri s, anggota a WHERE a.id_anggota = s.id_anggota AND s.id_santri = ps.id_santri AND id_kelompok =".$data->id_kelompok." ORDER BY nama_lengkap";
	$result2 = mysqli_query($connect, $query2);
	while($data2 = mysqli_fetch_object($result2)) {
?>
	 <td class="cell"><?php echo $data2->nama_lengkap; ?></th>
	 <td class="cell">'<?php echo $data2->nomor_hp; ?></th>
<?php } ?>
	</tr>
<?php
 }
?>
  </table>
 </body>
</html>