<?php
	require "inc/connect.php";
	require "inc/auth.php";
	
	//ambil data user dari database
	if($s) {	//login sebagai santri
		$query = "SELECT * FROM santri s,anggota a WHERE id_santri = '$id' AND a.id_anggota = s.id_anggota";
		$result = mysqli_query($connect,$query);
		$user = mysqli_fetch_object($result);
		$nama = $user->nama_lengkap;
		$id_anggota = $user->id_anggota;
		$foto_profil = $user->foto_profil;
	}
	else if($i) {	//login sebagai instruktur
		$query = "SELECT * FROM instruktur i,anggota a WHERE id_instruktur = '$id' AND a.id_anggota = i.id_anggota";
		$result = mysqli_query($connect,$query);
		$user = mysqli_fetch_object($result);
		$nama = $user->nama_lengkap;
		$id_anggota = $user->id_anggota;
		$foto_profil = $user->foto_profil;
	}

	$t_s = FALSE; $t_i = FALSE;
	$query = "SELECT * FROM santri anggota WHERE id_anggota = ".$user->id_anggota;
	$result = mysqli_query($connect,$query);
	if(mysqli_num_rows($result) > 0) $t_s = TRUE;
	$s2 = mysqli_fetch_object($result);
	
	$query = "SELECT * FROM instruktur WHERE id_anggota = ".$user->id_anggota;
	$result = mysqli_query($connect,$query);
	if(mysqli_num_rows($result) > 0) $t_i = TRUE;
	$i2 = mysqli_fetch_object($result);
	
	if($s && $t_i) {
		unset($_SESSION['id_santri']);
		$_SESSION['id_instruktur'] = $i2->id_instruktur;
	}
	else if($i && $t_s) {
		unset($_SESSION['id_instruktur']);
		$_SESSION['id_santri'] = $s2->id_santri;
	}
	header('Location: dasbor.php');
?>