<?php
 $j = array(array('Pra-Tahsin','Tahsin 1','Tahsin 2'),array('Takhossus','Tahfizh'),array('Tingkat 0','Tingkat 1'));
 $h = array('Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu');
 require "inc/connect.php";
 $query = "SELECT * FROM plot_view WHERE jk = 1";
 $result = mysqli_query($connect, $query);
?>
<html>
 <head>
  <title>Plot Santri LPQ Al-Hurriyyah Angkatan 11 Ikhwan</title>
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
	 <th class="cell">Nama Santri</th>
	 <th class="cell">Nomor HP</th>
	 <th class="cell">Nama Instruktur</th>
	 <th class="cell">Nomor HP</th>
	</tr>
<?php
 while($data = mysqli_fetch_object($result)) {
?>
	<tr class="row">
	 <td class="cell"><?php echo $j[$data->pr-1][$data->j-1]; ?></td>
	 <td class="cell"><?php echo $h[$data->h-1]; ?></td>
	 <td class="cell"><?php echo date('H:i', strtotime($data->w)); ?></td>
	 <td class="cell"><?php echo $data->nama_lengkap; ?></td>
	 <td class="cell"><?php echo $data->nomor_hp; ?></td>
	 <td class="cell"><?php echo $data->nama_instruktur; ?></td>
	 <td class="cell"><?php echo $data->nomor_instruktur; ?></td>
	</tr>
<?php
 }
?>
  </table>
 </body>
</html>