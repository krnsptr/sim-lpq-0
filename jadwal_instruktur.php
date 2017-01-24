<?php
	require "inc/auth.php";
	require "inc/connect.php";
	
	function rollback() {
		global $connect;
		$_SESSION['e'] = 3;
		mysqli_rollback($connect);
		header("Location: jadwal.php");
		exit();
	}
	
	if($i) {	//login sebagai instruktur
		$query = "SELECT * FROM instruktur i,anggota a WHERE id_instruktur = '$id' AND a.id_anggota = i.id_anggota";
		$result = mysqli_query($connect,$query);
		$user = mysqli_fetch_object($result);
	} else { header('Location: jadwal.php'); exit(); }
	
	$ida = $user->id_anggota;

	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'penjadwalan_instruktur'";
	$result = mysqli_query($connect,$query);
	$j_i = mysqli_fetch_object($result);
	if($j_i->isi == 0) { header('Location: jadwal.php'); exit(); }

	if(isset($_POST['ubah'])) {
		$k = (!empty($_POST['ubah'])) ? (int) $_POST['ubah'] : NULL;
		$j = (!empty($_POST['j'])) ? (int) $_POST['j'] : NULL;
		$h = (!empty($_POST['h'])) ? (int) $_POST['h'] : NULL;
		$wm = (!empty($_POST['wm'])) ? $_POST['wm'] : NULL;
		
		if(!$k || !$j || !$h || !$wm) { $_SESSION['e']=3; header('Location: jadwal.php'); exit(); }
		else {
			mysqli_begin_transaction($connect);
			$query = "SELECT program FROM kelompok WHERE id_kelompok = $k";
			$result = mysqli_query($connect,$query);
			if(mysqli_num_rows($result) < 1) rollback();
			$data = mysqli_fetch_object($result);
			$pr = $data->program;
			
			$keanggotaan = array(NULL,0,0,0);
			$query = "SELECT program, keanggotaan FROM program WHERE id_anggota = $ida";
			$result = mysqli_query($connect,$query);
			while($program = mysqli_fetch_object($result)) $keanggotaan[$program->program] = $program->keanggotaan;
			if($keanggotaan[$pr] != 2) rollback();
				
			$query = "SELECT jenjang FROM program WHERE id_anggota = $ida AND keanggotaan = 2 AND program = $pr";
			$result = mysqli_query($connect,$query);
			if(mysqli_num_rows($result) < 1) rollback();
			$data = mysqli_fetch_object($result);
			$jenjang = $data->jenjang;
			if($j > $jenjang) rollback();
			
			$query = "SELECT * FROM kelompok WHERE id_instruktur = $id AND id_kelompok = $k";
			$result = mysqli_query($connect,$query);
			if(mysqli_num_rows($result) < 1) rollback();
			
			$stmt = mysqli_stmt_init($connect);
			mysqli_stmt_prepare($stmt, 'UPDATE kelompok SET jenjang = ?, hari = ?, waktu = ? WHERE id_kelompok = ?');
			mysqli_stmt_bind_param($stmt, "iisi", $j, $h, $wm, $k);
			mysqli_stmt_execute($stmt);
			if(mysqli_stmt_affected_rows($stmt) < 1) rollback();
			mysqli_stmt_close($stmt);
		}
		mysqli_commit($connect);
		$_SESSION['o'] = 1; header('Location: jadwal.php');
	}
	else if(isset($_GET['hapus'])) {
		if(empty($_GET['hapus'])) { $_SESSION['e']=3; header('Location: jadwal.php'); exit(); }
		else {
			$k = (int) $_GET['hapus'];
			
			$query = "SELECT * FROM kelompok WHERE id_instruktur = $id AND id_kelompok = $k";
			$result = mysqli_query($connect,$query);
			if(mysqli_num_rows($result) < 1) { $_SESSION['e']=4; header('Location: jadwal.php'); exit(); }
			
			$query = "DELETE FROM kelompok WHERE id_instruktur = $id AND id_kelompok = $k";
			$result = mysqli_query($connect,$query);
			if(mysqli_affected_rows($connect) < 1) { $_SESSION['e']=4; header('Location: jadwal.php'); exit(); }
			
			$_SESSION['o'] = 2; header('Location: jadwal.php'); exit();
		}
	}
	else if(isset($_POST['tambah'])) {
		$pr = (!empty($_POST['pr'])) ? (int) $_POST['pr'] : NULL;
		$j = (!empty($_POST['j'])) ? (int) $_POST['j'] : NULL;
		$h = (!empty($_POST['h'])) ? (int) $_POST['h'] : NULL;
		$wm =(!empty($_POST['wm'])) ? $_POST['wm'] : NULL;
		
		if(!$pr || !$j || !$h || !$wm) { $_SESSION['e']=2; header('Location: jadwal.php'); exit(); }
		
		$keanggotaan = array(NULL,0,0,0);
		$query = "SELECT program, keanggotaan FROM program WHERE id_anggota = $ida";
		$result = mysqli_query($connect,$query);
		while($program = mysqli_fetch_object($result)) $keanggotaan[$program->program] = $program->keanggotaan;
		if($keanggotaan[$pr] != 2) { $_SESSION['e']=2; header('Location: jadwal.php'); exit(); }
		
		$query = "SELECT jenjang FROM program WHERE id_anggota = $ida AND keanggotaan = 2 AND program = $pr";
		$result = mysqli_query($connect,$query);
		if(mysqli_num_rows($result) < 1) { $_SESSION['e']=2; header('Location: jadwal.php'); exit(); }
		$data = mysqli_fetch_object($result);
		$jenjang = $data->jenjang;
		if($j > $jenjang) { $_SESSION['e']=2; header('Location: jadwal.php'); exit(); }
		
		$stmt = mysqli_stmt_init($connect);
		mysqli_stmt_prepare($stmt, 'INSERT INTO kelompok (id_instruktur,program,jenjang,hari,waktu) VALUES (?,?,?,?,?)');
		mysqli_stmt_bind_param($stmt, "iiiis", $id, $pr, $j, $h, $wm);
		mysqli_stmt_execute($stmt);
		if(mysqli_stmt_affected_rows($stmt) < 1) { $_SESSION['e']=2; header('Location: jadwal.php'); exit(); }
		mysqli_stmt_close($stmt);
		
		$_SESSION['o'] = 0; header('Location: jadwal.php');
	}