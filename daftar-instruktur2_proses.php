<?php
	session_start();
	require "inc/connect.php";
	
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pendaftaran_instruktur'";
	$result = mysqli_query($connect,$query);
	$d_i = mysqli_fetch_object($result);
	if($d_i->isi==0) { $_SESSION['e']=5; header('Location: index.php'); exit(); }
	
	function registered($val) {
		global $connect;
		$stmt = mysqli_stmt_init($connect);
		mysqli_stmt_prepare($stmt, "SELECT * FROM anggota WHERE id_status = ?");
		mysqli_stmt_bind_param($stmt, "s", $val);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		if(mysqli_stmt_num_rows($stmt) > 0) $regd = TRUE; else $regd = FALSE;
		mysqli_stmt_close($stmt);
		return $regd;
	}
	
	function rollback() {
		$_SESSION['e'] = 3;
		mysqli_rollback($connect);
		header("Location: index.php");
		exit();
	}

	$nl = isset($_SESSION['nl']) ? $_SESSION['nl'] : NULL; //nama lengkap
	$jk = isset($_SESSION['jk']) ? $_SESSION['jk'] : NULL; //jenis kelamin
	$st = isset($_SESSION['st']) ? $_SESSION['st'] : NULL; //status
	$nh = isset($_SESSION['nh']) ? $_SESSION['nh'] : NULL; //nomor hp
	$ae = isset($_SESSION['ae']) ? $_SESSION['ae'] : NULL; //alamat email
	$un = isset($_SESSION['un']) ? $_SESSION['un'] : NULL; //username
	$pr = isset($_SESSION['pr']) ? $_SESSION['pr'] : array(NULL, NULL, NULL); //program
	$mt = isset($_SESSION['mt']) ? (int) $_SESSION['mt'] : NULL; //mentoring
	$pwd = isset($_SESSION['pwd']) ? md5($_SESSION['pwd']) : NULL; //password
	
	$ni = isset($_POST['ni']) ? htmlspecialchars($_POST['ni']) : NULL; //nomor identitas
	$tl = isset($_POST['tl']) ? htmlspecialchars($_POST['tl']) : NULL; //tanggal lahir
	$nw = isset($_POST['nw']) ? htmlspecialchars($_POST['nw']) : NULL; //nomor whatsapp
	$at = isset($_POST['at']) ? htmlspecialchars($_POST['at']) : NULL; //alamat tinggal
	$mb = isset($_POST['mb']) ? htmlspecialchars($_POST['mb']) : NULL; //nama murobbi
	$nm = isset($_POST['nm']) ? htmlspecialchars($_POST['nm']) : NULL; //nomor murobbi
	$pd = (isset($_POST['pd']) && (isset($_SESSION['pr'][0]))) ? (int) $_POST['pd'] : NULL; //pendaftar
	$ms = (isset($_POST['ms']) && (isset($_SESSION['pr'][0]))) ? $_POST['ms'] : NULL; //memenuhi syarat
	$am = (isset($_POST['am']) && (isset($_SESSION['pr'][0]))) ? $_POST['am'] : NULL; //alasan mendaftar
	$ik = (isset($_POST['ik']) && (isset($_SESSION['pr'][0]))) ? $_POST['ik'] : NULL; //ide untuk kbm
	$kt = (isset($_POST['kt']) && (isset($_SESSION['pr'][0]))) ? (int) $_POST['kt'] : NULL; //komitmen tahsin
	$et = (isset($_POST['et']) && (isset($_SESSION['pr'][1]))) ? $_POST['et'] : NULL; //enrollment key tahfizh
	$pm = (isset($_POST['pm']) && (isset($_SESSION['pr'][2]))) ? (float) $_POST['pm'] : NULL; //pengalaman mengajar
	$bk = (isset($_POST['bk']) && (isset($_SESSION['pr'][2]))) ? $_POST['bk'] : NULL; //buku yang pernah dipelajari
	$mm = (isset($_POST['mm']) && (isset($_SESSION['pr'][2]))) ? $_POST['mm'] : NULL; //motivasi mengajar
	$eb = (isset($_POST['eb']) && (isset($_SESSION['pr'][2]))) ? $_POST['eb'] : NULL; //enrollment key bahasa arab
	$kb = (isset($_POST['kb']) && (isset($_SESSION['pr'][2]))) ? (int) $_POST['kb'] : NULL; //komitmen bahasa arab
	
	if(isset($_POST['post'])) {
		$date = date_create($tl);
		if(!$nl || !$jk || !$st || !$nh || !$ae || !$un || !$pwd || (!$pr[0] && !$pr[1] && !$pr[2]) || !$mt || !$pwd)
			{ $_SESSION['e'] = 0; header('Location: daftar-instruktur.php'); exit(); }
		else if(!$ni || !$tl || !$at || (isset($pr[0]) && (!$pd || !$kt)) || (isset($pr[1]) && !$et) || (isset($pr[2]) && (!$eb || !$kb))) $e=0;
		else if($nw != '' && !preg_match('/^08[0-9]{8,11}+$/', $nw)) $e=1;
		else if($nm != '' && !preg_match('/^08[0-9]{8,11}+$/', $nm)) $e=1;
		else if(registered($ni)) $e=2;
		else if(!$date || (date_format($date, 'd-m-Y') != $tl)) $e=3;
		else if((isset($pr[1]) && $et != 'TF_2016_11') || (isset($pr[2]) && $eb != '11_2016_BA')) $e=4;
		else if((isset($pr[0]) && ($kt<50 || $kt>100)) || (isset($pr[2]) && ($kb<50 || $kb>100))) $e=5;
		if(isset($e)) {
			$_SESSION['e'] = $e;
			foreach ($_POST as $key => $value) $_SESSION[$key] = $value;
			header('Location: daftar-instruktur2.php');
		}
		else {
			$tl = date_format($date, 'Y-m-d');
			
			mysqli_begin_transaction($connect);
			$stmt = mysqli_stmt_init($connect);
			mysqli_stmt_prepare($stmt, 'INSERT INTO anggota (nama_lengkap, jenis_kelamin, status, id_status, nomor_hp, email, alamat, username, password, tanggal_lahir, nomor_wa, mentoring, nama_murobbi, nomor_murobbi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
			mysqli_stmt_bind_param($stmt, "siissssssssiss", $nl, $jk, $st, $ni, $nh, $ae, $at, $un, $pwd, $tl, $nw, $mt, $mb, $nm);
			mysqli_stmt_execute($stmt);
			if(mysqli_stmt_affected_rows($stmt) < 1) rollback();
			mysqli_stmt_close($stmt);
			
			$query = "SELECT MAX(id_anggota) as id FROM anggota";
			$result = mysqli_query($connect, $query);
			$data = mysqli_fetch_object($result);
			$ida = $data->id;
			
			$stmt = mysqli_stmt_init($connect);
			mysqli_stmt_prepare($stmt, 'INSERT INTO instruktur (id_anggota) VALUES (?)');
			mysqli_stmt_bind_param($stmt, "i", $ida);
			mysqli_stmt_execute($stmt);
			if(mysqli_stmt_affected_rows($stmt) < 1) rollback();
			mysqli_stmt_close($stmt);
			
			$query = "SELECT id_instruktur FROM instruktur WHERE id_anggota = $ida";
			$result = mysqli_query($connect, $query);
			$data = mysqli_fetch_object($result);
			$idi = $data->id_instruktur;
			
			for($i=0; $i<3; $i++) if(!empty($ms[$i])) $syarat .= '1:'; else $syarat .= ':';
			
			foreach($pr as $key => $value) {
				$prog = ((int) $key) + 1;
				$query = "INSERT INTO program (id_anggota, program, keanggotaan, jenjang) VALUES ($ida, $prog, 2, 0);";
				mysqli_query($connect, $query);
				if(mysqli_affected_rows($connect) < 1) rollback();
				
				if($prog === 1) $jawaban = array($pd, $syarat, $am, $ik, $kt);
				else if($prog == 3) $jawaban = array($pm, $bk, $mm, NULL, $kb);
				
				if($prog != 2) {
					$stmt = mysqli_stmt_init($connect);
					mysqli_stmt_prepare($stmt, 'INSERT INTO pertanyaan_instruktur (id_instruktur, program, jawaban1, jawaban2, jawaban3, jawaban4, jawaban5) VALUES (?, ?, ?, ?, ?, ?, ?)');
					mysqli_stmt_bind_param($stmt, "iisssss", $idi, $prog, $jawaban[0], $jawaban[1], $jawaban[2], $jawaban[3], $jawaban[4]);
					mysqli_stmt_execute($stmt);
					if(mysqli_stmt_affected_rows($stmt) < 1) rollback();
					mysqli_stmt_close($stmt);
				}
				
			}
			mysqli_commit($connect);
			
			if(!empty($_FILES['fp']['name'])) {
				$ukuran_foto = $_FILES['fp']['size'];
				$tipe_foto = $_FILES['fp']['type'];
				$foto_sementara = $_FILES['fp']['tmp_name'];
				$foto_akhir = 'img/foto-profil/'.$ida;
				if($ukuran_foto > 3000000) $w=1;
				else if($tipe_foto != 'image/jpg' && $tipe_foto != 'image/jpeg' && $tipe_foto != 'image/gif' && $tipe_foto != 'image/png' ) $w = 0;
				else {
					if($tipe_foto == 'image/jpg' || $tipe_foto == 'image/jpeg') $tipe = '.jpg';
					if($tipe_foto == 'image/gif') $tipe = '.gif';
					if($tipe_foto == 'image/png') $tipe = '.png';
					$foto_akhir .= $tipe;
					move_uploaded_file($foto_sementara, $foto_akhir);
					$foto_akhir = $ida.$tipe;
					$stmt = mysqli_stmt_init($connect);
					mysqli_stmt_prepare($stmt, 'UPDATE anggota SET foto_profil = ? WHERE id_anggota = ?');
					mysqli_stmt_bind_param($stmt, "si", $foto_akhir, $ida);
					mysqli_stmt_execute($stmt);
					if(mysqli_stmt_affected_rows($stmt) < 1) $w=2;
					mysqli_stmt_close($stmt);
				}
			}
			
			if(isset($w)) $_SESSION['w'] = $w; else $_SESSION['o'] = 0;
			header("Location: index.php"); exit();
		}
	}
	else {header('Location: daftar-instruktur.php');}
?>