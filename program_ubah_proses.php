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
	
	$keanggotaan = array(NULL,0,0,0);
	$query = "SELECT program, keanggotaan FROM program WHERE id_anggota =".$user->id_anggota;
	$result = mysqli_query($connect,$query);
	while($program = mysqli_fetch_object($result)) $keanggotaan[$program->program] = $program->keanggotaan;
	
	if(!empty($_POST['program'])) {
		$prog = (int) $_POST['program'];
		if($keanggotaan[$prog] == 1) {
			if($prog == 1 || $prog == 2 || $prog == 3) {
				if(($prog == 1 || $prog == 2) && !isset($_POST['sl'])) $e=0;
				else {
					if($prog == 3) $jawaban1 = (!empty($_POST['pb'])) ? (float) $_POST['pb'] : NULL;
					else $jawaban1 = (int) $_POST['sl'];
					$jawaban2 = NULL;
					if(!empty($_POST['bb'])) { foreach($_POST['bb'] as $i => $value) { if(!empty($_POST['bb'][$i])) $jawaban2 .= $i.':'; else $jawaban2 .= ':'; } }
					$stmt = mysqli_stmt_init($connect);
					mysqli_stmt_prepare($stmt, 'UPDATE pertanyaan_santri ps, santri s SET jawaban1 = ?, jawaban2 = ? WHERE id_anggota = ? AND s.id_santri = ps.id_santri AND program = ?');
					mysqli_stmt_bind_param($stmt, "ssii", $jawaban1, $jawaban2, $user->id_anggota, $prog);
					mysqli_stmt_execute($stmt) or $e=4;
					mysqli_stmt_close($stmt);
					if(($prog == 1 && $keanggotaan[2] == 1) || ($prog == 2 && $keanggotaan[1] == 1)) {
						$prog = ($prog == 2) ? 1 : 2;
						$stmt = mysqli_stmt_init($connect);
						mysqli_stmt_prepare($stmt, 'UPDATE pertanyaan_santri ps, santri s SET jawaban1 = ?, jawaban2 = ? WHERE id_anggota = ? AND s.id_santri = ps.id_santri AND program = ?');
						mysqli_stmt_bind_param($stmt, "ssii", $jawaban1, $jawaban2, $user->id_anggota, $prog);
						mysqli_stmt_execute($stmt) or $e=4;
						mysqli_stmt_close($stmt);
						$prog = ($prog == 2) ? 1 : 2;
					}
				}
			}
			else {
				header('Location: dasbor.php');
				exit();
			}
		}
		else if($keanggotaan[$prog] == 2) {
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
					$stmt = mysqli_stmt_init($connect);
					mysqli_stmt_prepare($stmt, 'UPDATE pertanyaan_instruktur pi, instruktur i SET jawaban1 = ?, jawaban2 = ?, jawaban3 = ?, jawaban4 = ?, jawaban5 = ? WHERE id_anggota = ? AND i.id_instruktur = pi.id_instruktur AND program = ?');
					mysqli_stmt_bind_param($stmt, "sssssii", $jawaban1, $jawaban2, $jawaban3, $jawaban4, $jawaban5, $user->id_anggota, $prog);
					mysqli_stmt_execute($stmt) or $e=4;
					mysqli_stmt_close($stmt);
				}
			}
			else if($prog == 3) {
				$jawaban1 = (!empty($_POST['pm'])) ? (float) $_POST['pm'] : NULL;
				$jawaban2 = (!empty($_POST['bk'])) ? $_POST['bk'] : NULL;
				$jawaban3 = (!empty($_POST['mm'])) ? $_POST['mm'] : NULL;
				$jawaban4 = NULL;
				$jawaban5 = (!empty($_POST['kb'])) ? (int) $_POST['kb'] : NULL;
				if($jawaban5 <50 || $jawaban5 > 100) $e=3;
				else {
					$stmt = mysqli_stmt_init($connect);
					mysqli_stmt_prepare($stmt, 'UPDATE pertanyaan_instruktur pi, instruktur i SET jawaban1 = ?, jawaban2 = ?, jawaban3 = ?, jawaban4 = ?, jawaban5 = ? WHERE id_anggota = ? AND i.id_instruktur = pi.id_instruktur AND program = ?');
					mysqli_stmt_bind_param($stmt, "sssssii", $jawaban1, $jawaban2, $jawaban3, $jawaban4, $jawaban5, $user->id_anggota, $prog);
					mysqli_stmt_execute($stmt) or $e=4;
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
		if(isset($e)) $_SESSION['e']=$e;
		else $_SESSION['o']=0;
		header("Location: program_ubah.php?program=$prog");
	}
	else {
		header('Location: dasbor.php');
	}
?>