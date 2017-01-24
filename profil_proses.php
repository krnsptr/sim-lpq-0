<?php
	require "inc/auth.php";
	require "inc/connect.php";
	
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
		
	function registered($val, $col) {
		global $connect;
		global $user;
		$stmt = mysqli_stmt_init($connect);
		mysqli_stmt_prepare($stmt, "SELECT * FROM anggota WHERE $col = ?");
		mysqli_stmt_bind_param($stmt, "s", $val);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		if(mysqli_stmt_num_rows($stmt) > 0 && $val != $user->$col) $regd = TRUE; else $regd = FALSE;
		mysqli_stmt_close($stmt);
		return $regd;
	}
	
	if(isset($_POST['post'])) {
	$nl = !empty($_POST['nl']) ? $_POST['nl'] : NULL; //nama lengkap
	$st = !empty($_POST['st']) ? $_POST['st'] : NULL; //status
	$nh = !empty($_POST['nh']) ? $_POST['nh'] : NULL; //nomor hp
	$ae = !empty($_POST['ae']) ? $_POST['ae'] : NULL; //alamat email
	$un = !empty($_POST['un']) ? $_POST['un'] : NULL; //username
	$mt = !empty($_POST['mt']) ? (int) $_POST['mt'] : NULL; //mentoring
	$pt = !empty($_POST['pt']) ? (int) $_POST['pt'] : NULL; //placement test	
	$ni = !empty($_POST['ni']) ? htmlspecialchars($_POST['ni']) : NULL; //nomor identitas
	$tl = !empty($_POST['tl']) ? htmlspecialchars($_POST['tl']) : NULL; //tanggal lahir
	$nw = !empty($_POST['nw']) ? htmlspecialchars($_POST['nw']) : NULL; //nomor whatsapp
	$at = !empty($_POST['at']) ? htmlspecialchars($_POST['at']) : NULL; //alamat tinggal
	$mb = !empty($_POST['mb']) ? htmlspecialchars($_POST['mb']) : NULL; //nama murobbi
	$nm = !empty($_POST['nm']) ? htmlspecialchars($_POST['nm']) : NULL; //nomor murobbi
	$date = date_create($tl);
		if(!$nl || !$un || !$st || !$ni || !$tl || !$nh || !$ae || !$at || ($s && !$pt)) $e = 0;
		else if(!preg_match('/^08[0-9]{8,11}+$/', $nh)) $e=1;
		else if(filter_var($ae, FILTER_VALIDATE_EMAIL)  === FALSE) $e=2;
		else if(!preg_match('/^[a-z0-9_]{4,16}+$/', $un)) $e=3;
		else if(registered($nh, 'nomor_hp')) $e=5;
		else if(registered($ae, 'email')) $e=6;
		else if(registered($un, 'username')) $e=7;
		else if($nw != '' && !preg_match('/^08[0-9]{8,11}+$/', $nw)) $e=1;
		else if($nm != '' && !preg_match('/^08[0-9]{8,11}+$/', $nm)) $e=1;
		else if(registered($ni, 'id_status')) $e=8;
		else if(!$date || (date_format($date, 'd-m-Y') != $tl)) $e=9;
		else {
			$tl = date_format($date, 'Y-m-d');
			$stmt = mysqli_stmt_init($connect);
			if(mysqli_stmt_prepare($stmt, 'UPDATE anggota SET nama_lengkap = ?, username = ?, status = ?, id_status = ?, tanggal_lahir = ?, nomor_hp = ?, nomor_wa = ?, email = ?, alamat = ?, mentoring = ?, nama_murobbi = ?, nomor_murobbi = ? WHERE id_anggota = '.$user->id_anggota)) {
				mysqli_stmt_bind_param($stmt, "ssissssssiss", $nl, $un, $st, $ni, $tl, $nh, $nw, $ae, $at, $mt, $mb, $nm);
				mysqli_stmt_execute($stmt) or $e = 13;
				if(mysqli_stmt_affected_rows($stmt) < 1);
				mysqli_stmt_close($stmt);
			} else $e = 12;
			if($s) {
				$stmt = mysqli_stmt_init($connect);
				if(mysqli_stmt_prepare($stmt, 'UPDATE santri SET placement_test = ? WHERE id_anggota = '.$user->id_anggota)) {
					mysqli_stmt_bind_param($stmt, "i", $pt);
					mysqli_stmt_execute($stmt) or $e = 12;
					mysqli_stmt_close($stmt);
				} else $e = 12;
			}
			if(!empty($_FILES['fp']['name'])) {
				$ukuran_foto = $_FILES['fp']['size'];
				$tipe_foto = $_FILES['fp']['type'];
				$foto_sementara = $_FILES['fp']['tmp_name'];
				$foto_akhir = 'img/foto-profil/'.$user->id_anggota;
				if($ukuran_foto > 3000000) $w = 1;
				else if($tipe_foto != 'image/jpg' && $tipe_foto != 'image/jpeg' && $tipe_foto != 'image/gif' && $tipe_foto != 'image/png' ) $w = 0;
				else {
					if($tipe_foto == 'image/jpg' || $tipe_foto == 'image/jpeg') $tipe = '.jpg';
					if($tipe_foto == 'image/gif') $tipe = '.gif';
					if($tipe_foto == 'image/png') $tipe = '.png';
					$foto_akhir .= $tipe;
					if(!move_uploaded_file($foto_sementara, $foto_akhir)) $w = 2;
					$foto_akhir = $user->id_anggota.$tipe;
					$stmt = mysqli_stmt_init($connect);
					if (mysqli_stmt_prepare($stmt, 'UPDATE anggota SET foto_profil = ? WHERE id_anggota = ?')) {
						mysqli_stmt_bind_param($stmt, "si", $foto_akhir, $user->id_anggota);
						if(!(mysqli_stmt_execute($stmt))) $w = 2;
						mysqli_stmt_close($stmt);
					} else $w = 2;
				}
			}
		}
		if(isset($e)) $_SESSION['e'] = $e; else $_SESSION['o'] = 0;
		if(isset($w)) $_SESSION['w'] = $w;
		header("Location: profil.php"); exit();
	}
	else if(isset($_POST['ubah_password']) && isset($_POST['pl']) && isset($_POST['pb']) && isset($_POST['up'])) {
		$pl = md5($_POST['pl']); $pb = md5($_POST['pb']); $up = md5($_POST['up']);
		if($pb != $up) $e = 10;
		else {
			$stmt = mysqli_stmt_init($connect);
			if(mysqli_stmt_prepare($stmt, 'SELECT password FROM anggota where username = ? and id_anggota = ?')) {
				mysqli_stmt_bind_param($stmt, "si", $user->username, $user->id_anggota);
				if(!(mysqli_stmt_execute($stmt))) {$e = 13;}
				mysqli_stmt_bind_result($stmt, $pwu);
				mysqli_stmt_fetch($stmt);
				mysqli_stmt_close($stmt);
			}
			if($pl == $pwu) {
				$stmt = mysqli_stmt_init($connect);
				if(mysqli_stmt_prepare($stmt, 'UPDATE anggota SET password = ? WHERE id_anggota = ?')) {
					mysqli_stmt_bind_param($stmt, "si", $pb, $user->id_anggota);
					if(!(mysqli_stmt_execute($stmt))) $e = 13;
					if(mysqli_stmt_affected_rows($stmt) < 1) $e = 13;
					mysqli_stmt_close($stmt);
				}
			} else $e = 11;
		}
		if(isset($e)) $_SESSION['e'] = $e; else $_SESSION['o'] = 0;
		header("Location: profil.php"); exit();
	}
?>