<?php
	session_start();
	unset($_SESSION['id_admin']);
	$_SESSION['o'] = 0;
	header('Location: index.php');
?>