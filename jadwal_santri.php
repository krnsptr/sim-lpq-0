<?php
	require "inc/auth.php";
	require "inc/connect.php";
	
	function rollback() {
		global $connect;
		$_SESSION['e'] = 2;
		mysqli_rollback($connect);
		header("Location: jadwal.php?q=");
		exit();
	}
	
	if($s) {	//login sebagai santri
		$query = "SELECT * FROM santri i,anggota a WHERE id_santri = '$id' AND a.id_anggota = i.id_anggota";
		$result = mysqli_query($connect,$query);
		$user = mysqli_fetch_object($result);
	} else { header('Location: jadwal.php'); exit(); }
	
	$ida = $user->id_anggota;

	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'penjadwalan_santri'";
	$result = mysqli_query($connect,$query);
	$j_s = mysqli_fetch_object($result);
	if($j_s->isi == 0) { header('Location: jadwal.php'); exit(); }

	if(!empty($_POST['pr']) && !empty($_POST['j'])) {
		$pr = (int) $_POST['pr'];
		$j = explode('-',$_POST['j'],2);
		$j[0] = (int) $j[0];
		$j[1] = date('H:i', strtotime($j[1]));
		
		$keanggotaan = array(NULL,0,0,0);
		$jenjang = array(NULL,0,0,0);
		$query = "SELECT program, keanggotaan, jenjang FROM program WHERE id_anggota = $ida";
		$result = mysqli_query($connect,$query);
		while($program = mysqli_fetch_object($result)) { $keanggotaan[$program->program] = $program->keanggotaan; $jenjang[$program->program] = $program->jenjang; }
		if($keanggotaan[$pr] != 1) { header('Location: jadwal.php'); exit(); }
		
		mysqli_begin_transaction($connect);
		
		$query = "SELECT sisa FROM penjadwalan_santri_view WHERE jk = ".$user->jenis_kelamin." AND pr = $pr AND j = ".$jenjang[$pr]." AND h = ".$j[0]." AND w = '".$j[1]."'";
		$result = mysqli_query($connect,$query);
		$data = mysqli_fetch_object($result);

		if($data->sisa < 1) rollback();
		
		$query = "SELECT * FROM penjadwalan_santri WHERE id_santri = $id AND program = $pr";
		$result = mysqli_query($connect,$query);
		if(mysqli_num_rows($result) < 1) {
			$query = "INSERT INTO penjadwalan_santri (id_santri,program,hari,waktu) VALUES ($id,$pr,".$j[0].",'".$j[1]."')";
			mysqli_query($connect,$query);
			if(mysqli_affected_rows($connect) < 1) rollback();
		} 
		else {
			$query = "UPDATE penjadwalan_santri SET hari = ".$j[0].", waktu = '".$j[1]."' WHERE id_santri = $id AND program = $pr";
			mysqli_query($connect,$query);
			if(mysqli_affected_rows($connect) < 1) rollback();
		}
		//echo $query;
		$_SESSION['o']= 1; mysqli_commit($connect); header('Location: jadwal.php');
	} else { header('Location: jadwal.php'); exit(); }
?>