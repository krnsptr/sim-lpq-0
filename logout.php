<?php
	session_start();
	unset($_SESSION['id_santri']);
	unset($_SESSION['id_instruktur']);
	$_SESSION['o'] = 1;
	header('Location: index.php');
?>