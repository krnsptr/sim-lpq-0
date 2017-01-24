<?php
	session_start();
	
	require "inc/connect.php";
	
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
		
	$nl = !empty($_POST['nl']) ? htmlspecialchars($_POST['nl']) : NULL; //nama lengkap
	$jk = !empty($_POST['jk']) ? htmlspecialchars($_POST['jk']) : NULL; //jenis kelamin
	$st = !empty($_POST['st']) ? htmlspecialchars($_POST['st']) : NULL; //status
	$nh = !empty($_POST['nh']) ? htmlspecialchars($_POST['nh']) : NULL; //nomor hp
	$ae = !empty($_POST['ae']) ? htmlspecialchars($_POST['ae']) : NULL; //alamat email
	$un = !empty($_POST['un']) ? htmlspecialchars($_POST['un']) : NULL; //username
	$pw = !empty($_POST['pw']) ? md5($_POST['pw']) : NULL;				//password md5
	$up = !empty($_POST['up']) ? md5($_POST['up']) : NULL;				//ulangi password md5
	$pr = !empty($_POST['pr']) ? $_POST['pr'] : array(NULL, NULL, NULL); //program
	$mt = !empty($_POST['mt']) ? htmlspecialchars($_POST['mt']) : NULL; //mentoring
	$pt = !empty($_POST['pt']) ? htmlspecialchars($_POST['pt']) : NULL; //placement test
	
	if(isset($_POST['post'])) {
		if(!$nl || !$jk || !$st || !$nh || !$ae || !$un || !$pw || !$up || (!$pr[0] && !$pr[1] && !$pr[2]) || !$mt || !$pt) $e=0;
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
			header('Location: cr-santri2.php');
		}
		if(isset($e)) {
			$_SESSION['e'] = $e;
			foreach ($_POST as $key => $value) $_SESSION[$key] = $value;
			foreach ($_POST['pr'] as $key => $value) $_SESSION['pr'][$key] = $value;
			$_SESSION['pwd'] = $_SESSION['pw']; unset($_SESSION['pw']); unset($_SESSION['up']);
			header('Location: cr-santri.php');
		}
	}
	
	else header('Location: cr-santri.php');
?>