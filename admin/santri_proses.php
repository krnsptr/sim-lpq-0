<?php
	require "../inc/connect.php";
	
	session_start();
	if(!isset($_SESSION['id_admin'])) { header('Location: santri.php'); exit(); }
	
	if(isset($_POST['post'])) {
		mysqli_begin_transaction($connect);
		$stmt = mysqli_stmt_init($connect);
		foreach($_POST['j'] as $key => $value) {
			if($_POST['j'][$key] != $_POST['j_lama'][$key]) {
				mysqli_stmt_prepare($stmt, 'SELECT * FROM penjadwalan_santri WHERE id_santri = ? AND program = ?');
				mysqli_stmt_bind_param($stmt, "ii", $key, $_POST['pr']);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_num_rows($stmt) > 0) {
					mysqli_stmt_prepare($stmt, 'DELETE FROM penjadwalan_santri WHERE id_santri = ? AND program = ?');
					mysqli_stmt_bind_param($stmt, "ii", $key, $_POST['pr']);
					mysqli_stmt_execute($stmt);
					if(mysqli_stmt_affected_rows($stmt) < 1) {$_SESSION['e1'] = 0; mysqli_rollback($connect); header('Location: santri.php'); exit();}
				}
				mysqli_stmt_prepare($stmt, 'UPDATE program p, anggota a, santri s SET jenjang = ? WHERE a.id_anggota = s.id_anggota AND p.id_anggota = a.id_anggota AND s.id_santri = ? AND program = ?');
				mysqli_stmt_bind_param($stmt, "iii", $_POST['j'][$key], $key, $_POST['pr']);
				mysqli_stmt_execute($stmt);
				if(mysqli_stmt_affected_rows($stmt) < 1) {mysqli_rollback($connect); $_SESSION['e1'] = 0; header('Location: santri.php'); exit();}
				else $_SESSION['o1']++;
			}
		}
		if(isset($_POST['k'])) {
			foreach($_POST['k'] as $key => $value){
				if($_POST['k'][$key] != $_POST['k_lama'][$key]) {
					if(empty($_POST['k'][$key])) $_POST['k'][$key] = NULL;
					mysqli_stmt_prepare($stmt, 'UPDATE penjadwalan_santri js SET id_kelompok = ? WHERE id_santri = ? AND program = ?');
					mysqli_stmt_bind_param($stmt, "iii", $_POST['k'][$key], $key, $_POST['pr']);
					mysqli_stmt_execute($stmt);
					if(mysqli_stmt_affected_rows($stmt) < 1) {mysqli_rollback($connect); $_SESSION['e2'] = 0; header('Location: santri.php'); exit();}
					else $_SESSION['o2']++;
				}
			}
		}
		mysqli_stmt_close($stmt); mysqli_commit($connect);
		header('Location: santri.php');
	}
?>