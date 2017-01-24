<?php
	//cek status login
	$s = FALSE; $i = FALSE; //belum login (default)
	session_start();
	if(isset($_SESSION['id_santri'])) {$id = $_SESSION['id_santri']; $s = TRUE; } //sudah login sebagai santri
	else if(isset($_SESSION['id_instruktur'])) {$id = $_SESSION['id_instruktur']; $i = TRUE; } //sudah login sebagai instruktur
	else { //belum login
		$_SESSION['e'] = 2;
		header('Location: index.php');
		exit();
	}
?>