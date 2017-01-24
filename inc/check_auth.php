<?php
	require "connect.php";
	require "auth.php";
	
	if(isset($_REQUEST['un'])) { $val = $_REQUEST['un']; $col = "username"; }
	else if(isset($_REQUEST['ae'])) { $val = $_REQUEST['ae']; $col = "email"; }
	else if(isset($_REQUEST['nh'])) { $val = $_REQUEST['nh']; $col = "nomor_hp"; }
	else if(isset($_REQUEST['ni'])) { $val = $_REQUEST['ni']; $col = "id_status"; }
	
	if($s) {	//login sebagai santri
			$query = "SELECT * FROM santri s,anggota a WHERE id_santri = $id AND a.id_anggota = s.id_anggota";
			$result = mysqli_query($connect,$query);
			$user = mysqli_fetch_object($result);
		}
		else if($i) {	//login sebagai instruktur
			$query = "SELECT * FROM instruktur i,anggota a WHERE id_instruktur = $id AND a.id_anggota = i.id_anggota";
			$result = mysqli_query($connect,$query);
			$user = mysqli_fetch_object($result);
		}
		
		$stmt = mysqli_stmt_init($connect);
		mysqli_stmt_prepare($stmt, "SELECT * FROM anggota WHERE $col = ?");
		mysqli_stmt_bind_param($stmt, "s", $val);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		if(mysqli_stmt_num_rows($stmt) > 0 && $val != $user->$col) header('HTTP/1.0 404 Not Found'); else header('HTTP/1.0 200 OK');
		mysqli_stmt_close($stmt);
?>