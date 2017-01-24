<?php
	session_start();
	
	require "inc/connect.php";
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pendaftaran_instruktur'";
	$result = mysqli_query($connect,$query);
	$d_i = mysqli_fetch_object($result);
	if($d_i->isi==0) { $_SESSION['e']=5; header('Location: index.php'); exit(); }
	
	function registered($val, $col) {
		global $connect;
		$stmt = mysqli_stmt_init($connect);
		mysqli_stmt_prepare($stmt, "SELECT * FROM anggota WHERE $col = ?");
		mysqli_stmt_bind_param($stmt, "s", $val);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		if(mysqli_stmt_num_rows($stmt) > 0) $regd = TRUE; else $regd = FALSE;
		mysqli_stmt_close($stmt);
		return $regd;
	}
		
	$nl = isset($_POST['nl']) ? htmlspecialchars($_POST['nl']) : NULL; //nama lengkap
	$jk = isset($_POST['jk']) ? htmlspecialchars($_POST['jk']) : NULL; //jenis kelamin
	$st = isset($_POST['st']) ? htmlspecialchars($_POST['st']) : NULL; //status
	$nh = isset($_POST['nh']) ? htmlspecialchars($_POST['nh']) : NULL; //nomor hp
	$ae = isset($_POST['ae']) ? htmlspecialchars($_POST['ae']) : NULL; //alamat email
	$un = isset($_POST['un']) ? htmlspecialchars($_POST['un']) : NULL; //username
	$pw = isset($_POST['pw']) ? md5($_POST['pw']) : NULL;				//password md5
	$up = isset($_POST['up']) ? md5($_POST['up']) : NULL;				//ulangi password md5
	$pr = isset($_POST['pr']) ? $_POST['pr'] : array(NULL, NULL, NULL); //program
	$mt = isset($_POST['mt']) ? htmlspecialchars($_POST['mt']) : NULL; //mentoring
	
	if(isset($_POST['post'])) {
		if(!$nl || !$jk || !$st || !$nh || !$ae || !$un || !$pw || !$up || (!$pr[0] && !$pr[1] && !$pr[2]) || !$mt) $e=0;
		else if(!preg_match('/^08[0-9]{8,11}+$/', $nh)) $e=1;
		else if(filter_var($ae, FILTER_VALIDATE_EMAIL)  === FALSE) $e=2;
		else if(!preg_match('/^[a-z0-9_]{4,16}+$/', $un)) $e=3;
		else if(strlen($_POST['pw']) < 6) $e=4;
		else if(registered($nh, 'nomor_hp')) $e=5;
		else if(registered($ae, 'email')) $e=6;
		else if(registered($un, 'username')) $e=7;
		else if($pw != $up) $e=8;
		else {
			foreach ($_POST as $key => $value) $_SESSION[$key] = $value;
			foreach ($_POST['pr'] as $key => $value) $_SESSION['pr'][$key] = $value;
			$_SESSION['pwd'] = $_SESSION['pw']; unset($_SESSION['pw']); unset($_SESSION['up']);
			header('Location: daftar-instruktur2.php');
		}
		if(isset($e)) {
			$_SESSION['e'] = $e;
			foreach ($_POST as $key => $value) $_SESSION[$key] = $value;
			foreach ($_POST['pr'] as $key => $value) $_SESSION['pr'][$key] = $value;
			$_SESSION['pwd'] = $_SESSION['pw']; unset($_SESSION['pw']); unset($_SESSION['up']);
			header('Location: daftar-instruktur.php');
		}
	}
	
	else header('Location: daftar-instruktur.php');
?>