<?php
	require "inc/auth.php";
	require "inc/connect.php";
	
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
	
	$keanggotaan = array(3,0,0,0);
	$query = "SELECT program, keanggotaan FROM program WHERE id_anggota =".$user->id_anggota;
	$result = mysqli_query($connect,$query);
	while($program = mysqli_fetch_object($result)) $keanggotaan[$program->program] = $program->keanggotaan;
	
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_santri'";
	$result = mysqli_query($connect,$query);
	$p_s = mysqli_fetch_object($result);
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_instruktur'";
	$result = mysqli_query($connect,$query);
	$p_i = mysqli_fetch_object($result);
	
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pendaftaran_santri'";
	$result = mysqli_query($connect,$query);
	$d_s = mysqli_fetch_object($result);
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pendaftaran_instruktur'";
	$result = mysqli_query($connect,$query);
	$d_i = mysqli_fetch_object($result);
	
	if(!empty($_POST['program'])) {
		$prog = (int) $_POST['program'];
		function rollback() {
			global $prog, $connect;
			$_SESSION['e'] = 4;
			mysqli_rollback($connect);
			header("Location: program_daftar.php?program=$prog");
			exit();
		}
		if($keanggotaan[$prog] != 0) { header('Location: dasbor.php'); exit(); }
		if($_POST['sebagai'] == 1 && $d_s->isi == 1) {
			if($prog == 1 || $prog == 2 || $prog == 3) {
				if(($prog == 1 || $prog == 2) && !isset($_POST['sl'])) $e=0;
				else {
					mysqli_begin_transaction($connect);
					
					$query = "SELECT id_santri FROM santri WHERE id_anggota = $ida";
					$result = mysqli_query($connect, $query);
					if(mysqli_num_rows($result) < 1) {
						$query = "INSERT INTO santri (id_anggota, placement_test) VALUES (".$user->id_anggota.", 3)";
						$result = mysqli_query($connect, $query);
						if(mysqli_affected_rows($connect) < 1) rollback();
					}
					
					if($prog == 3) $jawaban1 = (!empty($_POST['pb'])) ? (float) $_POST['pb'] : NULL;
					else $jawaban1 = (int) $_POST['sl'];
					
					$query = "SELECT id_santri FROM santri WHERE id_anggota = $ida";
					$result = mysqli_query($connect, $query);
					$data = mysqli_fetch_object($result);
					$ids = $data->id_santri;
					
					$query = "INSERT INTO program (id_anggota, program, keanggotaan, jenjang) VALUES ($ida, $prog, 1, 0);";
					mysqli_query($connect, $query);
					if(mysqli_affected_rows($connect) < 1) rollback();
					
					$jawaban2 = NULL;
					if(!empty($_POST['bb'])) { foreach($_POST['bb'] as $i => $value) { if(!empty($_POST['bb'][$i])) $jawaban2 .= $i.':'; else $jawaban2 .= ':'; } }
					
					$stmt = mysqli_stmt_init($connect);
					mysqli_stmt_prepare($stmt, 'INSERT INTO pertanyaan_santri (id_santri, program, jawaban1, jawaban2) VALUES (?, ?, ?, ?)');
					mysqli_stmt_bind_param($stmt, "iiss", $ids, $prog, $jawaban1, $jawaban2);
					mysqli_stmt_execute($stmt);
					if(mysqli_stmt_affected_rows($stmt) < 1) rollback();
					mysqli_stmt_close($stmt);
				}
			}
			else {
				header('Location: dasbor.php');
				exit();
			}
		}
		else if($_POST['sebagai'] == 2 && $d_i->isi == 1) {
			if($prog == 1) {
				$jawaban1 = (!empty($_POST['pd'])) ? (int) $_POST['pd'] : NULL;
				$ms = $_POST['ms'];
				for($i=0; $i<3; $i++) if(!empty($ms[$i])) $jawaban2 .= '1:'; else $jawaban2 .= ':';
				$jawaban3 = (!empty($_POST['am'])) ? $_POST['am'] : NULL;
				$jawaban4 = (!empty($_POST['ik'])) ? $_POST['ik'] : NULL;
				$jawaban5 = (!empty($_POST['kt'])) ? (int) $_POST['kt'] : NULL;
				if(!$jawaban1) $e=0;
				else if($jawaban5 <50 || $jawaban5 > 100) $e=3;
				else {
					mysqli_begin_transaction($connect);
					
					$query = "SELECT id_instruktur FROM instruktur WHERE id_anggota = $ida";
					$result = mysqli_query($connect, $query);
					if(mysqli_num_rows($result) < 1) {
						$query = "INSERT INTO instruktur (id_anggota) VALUES (".$user->id_anggota.")";
						$result = mysqli_query($connect, $query);
						if(mysqli_affected_rows($connect) < 1) rollback();
					}
					
					$query = "SELECT id_instruktur FROM instruktur WHERE id_anggota = $ida";
					$result = mysqli_query($connect, $query);
					$data = mysqli_fetch_object($result);
					$idi = $data->id_instruktur;
					
					$query = "INSERT INTO program (id_anggota, program, keanggotaan, jenjang) VALUES ($ida, $prog, 2, 0);";
					mysqli_query($connect, $query);
					if(mysqli_affected_rows($connect) < 1) rollback();
					
					$stmt = mysqli_stmt_init($connect);
					mysqli_stmt_prepare($stmt, 'INSERT INTO pertanyaan_instruktur (id_instruktur, program, jawaban1, jawaban2, jawaban3, jawaban4, jawaban5) VALUES (?, ?, ?, ?, ?, ?, ?)');
					mysqli_stmt_bind_param($stmt, "iisssss", $idi, $prog, $jawaban1, $jawaban2, $jawaban3, $jawaban4, $jawaban5);
					mysqli_stmt_execute($stmt);
					if(mysqli_stmt_affected_rows($stmt) < 1) rollback();
					mysqli_stmt_close($stmt);
				}
			}
			else if($prog == 2) {
				if(empty($_POST['et'])) $e=0;
				else if($_POST['et'] != 'TF_2016_11') $e=2;
				else {
					mysqli_begin_transaction($connect);
					
					$query = "SELECT id_instruktur FROM instruktur WHERE id_anggota = $ida";
					$result = mysqli_query($connect, $query);
					if(mysqli_num_rows($result) < 1) {
						$query = "INSERT INTO instruktur (id_anggota) VALUES (".$user->id_anggota.")";
						$result = mysqli_query($connect, $query);
						if(mysqli_affected_rows($connect) < 1) rollback();
					}
					
					$query = "SELECT id_instruktur FROM instruktur WHERE id_anggota = $ida";
					$result = mysqli_query($connect, $query);
					$data = mysqli_fetch_object($result);
					$idi = $data->id_instruktur;
					
					$query = "INSERT INTO program (id_anggota, program, keanggotaan, jenjang) VALUES ($ida, $prog, 2, 0);";
					mysqli_query($connect, $query);
					if(mysqli_affected_rows($connect) < 1) rollback();
				}
			}
			else if($prog == 3) {
				$jawaban1 = (!empty($_POST['pm'])) ? (float) $_POST['pm'] : NULL;
				$jawaban2 = (!empty($_POST['bk'])) ? $_POST['bk'] : NULL;
				$jawaban3 = (!empty($_POST['mm'])) ? $_POST['mm'] : NULL;
				$jawaban4 = NULL;
				$jawaban5 = (!empty($_POST['kb'])) ? (int) $_POST['kb'] : NULL;
				if($jawaban5 <50 || $jawaban5 > 100) $e=3;
				else if($_POST['eb'] != '11_2016_BA') $e=2;
				else {
					mysqli_begin_transaction($connect);
					
					$query = "SELECT id_instruktur FROM instruktur WHERE id_anggota = $ida";
					$result = mysqli_query($connect, $query);
					if(mysqli_num_rows($result) < 1) {
						$query = "INSERT INTO instruktur (id_anggota) VALUES (".$user->id_anggota.")";
						$result = mysqli_query($connect, $query);
						if(mysqli_affected_rows($connect) < 1) rollback();
					}
					
					$query = "SELECT id_instruktur FROM instruktur WHERE id_anggota = $ida";
					$result = mysqli_query($connect, $query);
					$data = mysqli_fetch_object($result);
					$idi = $data->id_instruktur;
					
					$query = "INSERT INTO program (id_anggota, program, keanggotaan, jenjang) VALUES ($ida, $prog, 2, 0);";
					mysqli_query($connect, $query);
					if(mysqli_affected_rows($connect) < 1) rollback();
					
					$stmt = mysqli_stmt_init($connect);
					mysqli_stmt_prepare($stmt, 'INSERT INTO pertanyaan_instruktur (id_instruktur, program, jawaban1, jawaban2, jawaban3, jawaban4, jawaban5) VALUES (?, ?, ?, ?, ?, ?, ?)');
					mysqli_stmt_bind_param($stmt, "iisssss", $idi, $prog, $jawaban1, $jawaban2, $jawaban3, $jawaban4, $jawaban5);
					mysqli_stmt_execute($stmt);
					if(mysqli_stmt_affected_rows($stmt) < 1) rollback();
					mysqli_stmt_close($stmt);
				}
			}
			else {
				header('Location: dasbor.php');
				exit();
			}
		}
		else {
			header('Location: dasbor.php');
			exit();
		}
		if(isset($e)) { $_SESSION['e']=$e; header("Location: program_daftar.php?program=$prog"); }
		else { mysqli_commit($connect); $_SESSION['o']=1; header("Location: dasbor.php"); }
	}
	else {
		header('Location: dasbor.php');
	}
?>