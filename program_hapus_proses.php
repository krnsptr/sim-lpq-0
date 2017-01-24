<?php
	require "inc/connect.php";
	require "inc/auth.php";
		
	if($s) {	//login sebagai santri
		$query = "SELECT * FROM santri s,anggota a WHERE id_santri = '$id' AND a.id_anggota = s.id_anggota";
		$result = mysqli_query($connect,$query);
		$user = mysqli_fetch_object($result);
	}
	else if($i) {	//login sebagai instruktur
		$query = "SELECT * FROM instruktur i,anggota a WHERE id_instruktur = '$id' AND a.id_anggota = i.id_anggota";
		$result = mysqli_query($connect,$query);
		$user = mysqli_fetch_object($result);
	}
	
	$ida = $user->id_anggota;
	
	function rollback() {
		global $prog, $connect;
		$_SESSION['e'] = 0;
		mysqli_rollback($connect);
		header("Location: dasbor.php");
		exit();
	}
	
	$keanggotaan = array(NULL,0,0,0);
	$query = "SELECT program, keanggotaan FROM program WHERE id_anggota =".$user->id_anggota;
	$result = mysqli_query($connect,$query);
	while($program = mysqli_fetch_object($result)) $keanggotaan[$program->program] = $program->keanggotaan;
	
	if(!empty($_POST['program'])) {
		$prog = (int) $_POST['program'];
		if($keanggotaan[$prog] == 0) {
			header('Location: dasbor.php');
			exit();
		}
		else {
			if($keanggotaan[$prog] == 1) {
				mysqli_begin_transaction($connect);
				$query = "SELECT id_santri FROM santri WHERE id_anggota = $ida";
				$result = mysqli_query($connect, $query);
				$data = mysqli_fetch_object($result);
				$ids = $data->id_santri;
				
				$query = "SELECT * FROM penjadwalan_santri WHERE id_santri = $ids AND program = $prog";
				$result = mysqli_query($connect, $query);
				if(mysqli_num_rows($result) > 0) {
					$query = "DELETE FROM penjadwalan_santri WHERE id_santri = $ids AND program = $prog";
					mysqli_query($connect, $query);
					if(mysqli_affected_rows($connect) < 1) rollback();
				}
			
				$query = "SELECT * FROM pertanyaan_santri WHERE id_santri = $ids AND program = $prog";
				$result = mysqli_query($connect, $query);
				if(mysqli_num_rows($result) > 0) {
					$query = "DELETE FROM pertanyaan_santri WHERE id_santri = $ids AND program = $prog";
					mysqli_query($connect, $query);
					if(mysqli_affected_rows($connect) < 1) rollback();
				}
			}
			else if($keanggotaan[$prog] == 2) {
				mysqli_begin_transaction($connect);
				$query = "SELECT id_instruktur FROM instruktur WHERE id_anggota = $ida";
				$result = mysqli_query($connect, $query);
				$data = mysqli_fetch_object($result);
				$idi = $data->id_instruktur;
				
				$query = "SELECT * FROM kelompok WHERE id_instruktur = $idi AND program = $prog";
				$result = mysqli_query($connect, $query);
				if(mysqli_num_rows($result) > 0) {
					$query = "DELETE FROM kelompok WHERE id_instruktur = $idi AND program = $prog";
					mysqli_query($connect, $query);
					if(mysqli_affected_rows($connect) < 1) rollback();
				}
			
				$query = "SELECT * FROM pertanyaan_instruktur WHERE id_instruktur = $idi AND program = $prog";
				$result = mysqli_query($connect, $query);
				if(mysqli_num_rows($result) > 0) {
					$query = "DELETE FROM pertanyaan_instruktur WHERE id_instruktur = $idi AND program = $prog";
					mysqli_query($connect, $query);
					if(mysqli_affected_rows($connect) < 1) rollback();
				}
			}
			else { header('Location: dasbor.php'); exit(); }
			
			$query = "DELETE FROM program WHERE id_anggota = $ida AND program = $prog";
			mysqli_query($connect, $query);
			if(mysqli_affected_rows($connect) < 1) rollback();
			mysqli_commit($connect);
			$_SESSION['o']=1;
			header("Location: dasbor.php");
		}
	}
	else {
		header('Location: dasbor.php'); exit();
	}
?>