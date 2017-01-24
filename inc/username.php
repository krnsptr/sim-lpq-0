<?php
	require "connect.php";
	$un = (isset($_REQUEST['un'])) ? $_REQUEST['un'] : NULL;
	
	$stmt = mysqli_stmt_init($connect);
	mysqli_stmt_prepare($stmt, 'SELECT * FROM anggota WHERE username = ?');
	mysqli_stmt_bind_param($stmt, "s", $un);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_store_result($stmt);
	if(mysqli_stmt_num_rows($stmt) > 0) header('HTTP/1.0 404 Not Found'); else header('HTTP/1.0 200 OK');
	mysqli_stmt_close($stmt);
?>