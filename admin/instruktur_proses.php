<?php
	require "../inc/connect.php";
	
	session_start();
	if(!isset($_SESSION['id_admin'])) { header('Location: instruktur.php'); exit(); }
	
	if(isset($_POST['post'])) {
		mysqli_begin_transaction($connect);
		$stmt = mysqli_stmt_init($connect);
		foreach($_POST['j'] as $key => $value) {
			if($_POST['j'][$key] != $_POST['j_lama'][$key]) {
				mysqli_stmt_prepare($stmt, 'SELECT * FROM kelompok WHERE id_instruktur = ? AND program = ?');
				mysqli_stmt_bind_param($stmt, "ii", $key, $_POST['pr']);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_num_rows($stmt) > 0) {
					mysqli_stmt_prepare($stmt, 'DELETE FROM kelompok WHERE id_instruktur = ? AND program = ?');
					mysqli_stmt_bind_param($stmt, "ii", $key, $_POST['pr']);
					mysqli_stmt_execute($stmt);
					if(mysqli_stmt_affected_rows($stmt) < 1) {$_SESSION['e'] = $key; mysqli_rollback($connect); header('Location: instruktur.php'); exit();}
				}
				mysqli_stmt_prepare($stmt, 'UPDATE program p, anggota a, instruktur i SET jenjang = ? WHERE a.id_anggota = i.id_anggota AND p.id_anggota = a.id_anggota AND i.id_instruktur = ? AND program = ?');
				mysqli_stmt_bind_param($stmt, "iii", $_POST['j'][$key], $key, $_POST['pr']);
				mysqli_stmt_execute($stmt);
				if(mysqli_stmt_affected_rows($stmt) < 1) {mysqli_rollback($connect); $_SESSION['e'] = $key; header('Location: instruktur.php'); exit();}
				else $_SESSION['o']++;
			}
		}
		mysqli_stmt_close($stmt); mysqli_commit($connect);
		header('Location: instruktur.php');
	}
?>