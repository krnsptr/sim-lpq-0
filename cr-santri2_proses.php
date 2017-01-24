<?php
	session_start();
	require "inc/connect.php";
	
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

	$nl = !empty($_SESSION['nl']) ? $_SESSION['nl'] : NULL; //nama lengkap
	$jk = !empty($_SESSION['jk']) ? $_SESSION['jk'] : NULL; //jenis kelamin
	$st = !empty($_SESSION['st']) ? $_SESSION['st'] : NULL; //status
	$nh = !empty($_SESSION['nh']) ? $_SESSION['nh'] : NULL; //nomor hp
	$ae = !empty($_SESSION['ae']) ? $_SESSION['ae'] : NULL; //alamat email
	$un = !empty($_SESSION['un']) ? $_SESSION['un'] : NULL; //username
	$pr = !empty($_SESSION['pr']) ? $_SESSION['pr'] : array(NULL, NULL, NULL); //program
	$mt = !empty($_SESSION['mt']) ? (int) $_SESSION['mt'] : NULL; //mentoring
	$pt = !empty($_SESSION['pt']) ? (int) $_SESSION['pt'] : NULL; //placement test
	$pwd = !empty($_SESSION['pwd']) ? md5($_SESSION['pwd']) : NULL; //password
	
	$ni = !empty($_POST['ni']) ? htmlspecialchars($_POST['ni']) : NULL; //nomor identitas
	$tl = !empty($_POST['tl']) ? htmlspecialchars($_POST['tl']) : NULL; //tanggal lahir
	$nw = !empty($_POST['nw']) ? htmlspecialchars($_POST['nw']) : NULL; //nomor whatsapp
	$at = !empty($_POST['at']) ? htmlspecialchars($_POST['at']) : NULL; //alamat tinggal
	$mb = !empty($_POST['mb']) ? htmlspecialchars($_POST['mb']) : NULL; //nama murobbi
	$nm = !empty($_POST['nm']) ? htmlspecialchars($_POST['nm']) : NULL; //nomor murobbi
	$sl = (isset($_POST['sl']) && (!empty($_SESSION['pr'][0]) || !empty($_SESSION['pr'][1]))) ? (int) $_POST['sl'] : NULL; //sudah lulus?
	$bb = (isset($_POST['bb']) && (!empty($_SESSION['pr'][0]) || !empty($_SESSION['pr'][1]))) ? $_POST['bb'] : NULL; //beli/pesan buku?
	$pb = (!empty($_POST['pb']) && (!empty($_SESSION['pr'][2]))) ? (float) $_POST['pb'] : NULL; //pengalaman belajar bahasa arab
	
	if(isset($_POST['post'])) {
		$date = date_create($tl);
		if(!$nl || !$jk || !$st || !$nh || !$ae || !$un || !$pwd || (!$pr[0] && !$pr[1] && !$pr[2]) || !$mt || !$pt || !$pwd)
			{ $_SESSION['e'] = 0; header('Location: cr-santri.php'); exit(); }
		else if(!$ni || !$tl || !$at || ((isset($pr[0]) || isset($pr[1])) && ($sl === ''))) $e=0;
		else if($nw != '' && !preg_match('/^08[0-9]{8,11}+$/', $nw)) $e=1;
		else if($nm != '' && !preg_match('/^08[0-9]{8,11}+$/', $nm)) $e=1;
		else if(registered($ni)) $e=2;
		else if(!$date || (date_format($date, 'd-m-Y') != $tl)) $e=3;
		if(isset($e)) {
			$_SESSION['e'] = $e;
			foreach ($_POST as $key => $value) $_SESSION[$key] = $value;
			header('Location: cr-santri2.php');
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
			mysqli_stmt_prepare($stmt, 'INSERT INTO santri (id_anggota, placement_test) VALUES (?, ?)');
			mysqli_stmt_bind_param($stmt, "ii", $ida, $pt);
			mysqli_stmt_execute($stmt);
			if(mysqli_stmt_affected_rows($stmt) < 1) rollback();
			mysqli_stmt_close($stmt);
			
			$query = "SELECT id_santri FROM santri WHERE id_anggota = $ida";
			$result = mysqli_query($connect, $query);
			$data = mysqli_fetch_object($result);
			$ids = $data->id_santri;
			
			foreach($pr as $key => $value) {
				$prog = ((int) $key) + 1;
				$query = "INSERT INTO program (id_anggota, program, keanggotaan, jenjang) VALUES ($ida, $prog, 1, 0);";
				mysqli_query($connect, $query);
				if(mysqli_affected_rows($connect) < 1) rollback();
				
				if(!empty($bb) && $prog !=3) { foreach($bb as $i => $value) { if(!empty($bb[$i])) $jawaban2 .= $i.':'; else $jawaban2 .= ':'; } } else $jawaban2 = NULL;
				
				$jawaban1 = ($prog == 3) ? $pb : $sl;
				$stmt = mysqli_stmt_init($connect);
				mysqli_stmt_prepare($stmt, 'INSERT INTO pertanyaan_santri (id_santri, program, jawaban1, jawaban2) VALUES (?, ?, ?, ?)');
				mysqli_stmt_bind_param($stmt, "iiss", $ids, $prog, $jawaban1, $jawaban2);
				mysqli_stmt_execute($stmt);
				if(mysqli_stmt_affected_rows($stmt) < 1) rollback();
				mysqli_stmt_close($stmt);
				$jawaban2 = NULL;
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
			header("Location: index.php");
		}
	}
	else header('Location: cr-santri.php');
?>