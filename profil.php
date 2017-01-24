<?php
	require "inc/auth.php";
	
	$error = array(	"Formulir ada yang kosong. Silakan isi ulang.",
					"Format nomor HP salah. Contoh: 081234567890 (10-13 digit).",
					"Format alamat email salah.",
					"Username hanya boleh mengandung huruf kecil, angka, dan underscore (4-16 karakter).",
					"Password minimum 6 karakter.",
					"Nomor HP sudah terdaftar.",
					"Alamat email sudah terdaftar.",
					"Username sudah terdaftar.",
					"Nomor identitas sudah terdaftar",
					"Format tanggal lahir salah. Contoh: 21-12-1992.",
					"Password baru tidak sama.",
					"Password lama tidak cocok.",
					"Profil gagal/tidak diubah.",
					"Password gagal/tidak diubah."
					);
				
	$warning = array("Format foto tidak diterima.",
					"Ukuran foto terlalu besar (maksimum 3 MB).",
					"Foto profil gagal diubah.");
					
	$success = array("Profil berhasil diubah.");
	
	if(isset($_SESSION['e'])) { $e = $_SESSION['e']; unset($_SESSION['e']); }
	else if(isset($_SESSION['o'])) { $o = $_SESSION['o']; unset($_SESSION['o']);
	if(isset($_SESSION['w'])) { $w = $_SESSION['w']; unset($_SESSION['w']); } }
	
	//ambil data user dari database
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
	
	
	include "inc/header.php";
?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Profil
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i>SIM LPQ</a></li>
          <li class="active">Profil</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
		<div class="col-md-7">
		  <div class="row">
			<?php if(isset($e)) { ?>
			<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-ban"></i> Kesalahan!</h4>
					<?php echo $error[$e]; ?>
			</div>
			<?php } ?>
			<?php if(isset($w)) { ?>
			<div class="alert alert-warning alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-warning"></i> Peringatan!</h4>
					<?php echo $warning[$w]; ?>
			</div>
			<?php } ?>
			<?php if(isset($o)) { ?>
			<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-check"></i> Berhasil!</h4>
					<?php echo $success[$o]; ?>
			</div>
			<?php } ?>
         <div class="box box-default">
			  <div class="box-header with-border">
					<h3 class="box-title">Profil Saya</h3>
			  </div>
			  <div class="box-body table-condensed">
				<table class="table">
					<tr>
					  <th width="25%">Nama Lengkap</th>
					  <td><?php echo $nama; ?></td>
					</tr>
					<tr>
					  <th width="25%">Username</th>
					  <td><?php echo $user->username; ?></td>
					</tr>
					<tr>
					  <th width="25%">Keanggotaan</th>
					  <td><?php echo ($i) ? "Instruktur" : "Santri" ; ?></td>
					</tr>
					<tr>
					  <th width="25%">Jenis Kelamin</th>
					  <td><?php echo ($user->jenis_kelamin == 2) ? "Perempuan" : "Laki-Laki" ; ?></td>
					</tr>
					<tr>
					  <th width="25%">Status</th>
					  <td><?php echo ($user->status == 1) ? "Mahasiswa IPB" : "Umum" ; ?></td>
					</tr>
					<tr>
					  <th width="25%">Nomor Identitas</th>
					  <td><?php echo $user->id_status; ?></td>
					</tr>
					<tr>
					  <th width="25%">Tanggal Lahir</th>
					  <td><?php echo date_format(date_create($user->tanggal_lahir), 'd-m-Y'); ?></td>
					</tr>
					<tr>
					  <th width="25%">Nomor HP</th>
					  <td><?php echo $user->nomor_hp; ?></td>
					</tr>
					<tr>
					  <th width="25%">Nomor WhatsApp</th>
					  <td><?php echo $user->nomor_wa; ?></td>
					</tr>
					<tr>
					  <th width="25%">Alamat Email</th>
					  <td><?php echo $user->email; ?></td>
					</tr>
					<tr>
					  <th width="25%">Alamat Tinggal</th>
					  <td><?php echo $user->alamat; ?></td>
					</tr>
					<tr>
					  <th width="25%">Mentoring</th>
					  <td><?php echo ($user->mentoring == 2) ? 'Sudah' : 'Belum'; ?></td>
					</tr>
					<tr>
					  <th width="25%">Nama murobbi</th>
					  <td><?php echo $user->nama_murobbi; ?></td>
					</tr>
					<tr>
					  <th width="25%">Nomor HP murobbi</th>
					  <td><?php echo $user->nomor_murobbi; ?></td>
					</tr>
					<?php 
						if($s) {
							$query = "SELECT placement_test FROM santri WHERE id_santri = $id";
							$result = mysqli_query($connect,$query);
							$pts = mysqli_fetch_object($result);
					?>
					<tr>
					  <th width="25%">Placement Test</th>
					  <td><?php echo ($pts->placement_test == 1) ? "Sabtu, 10 September 2016" : (($pts->placement_test == 2) ? "Ahad, 11 September 2016" : "Tidak bisa keduanya" ); ?></td>
					</tr>
					<?php
						}
					?>
					<tr>
					  <th width="25%">Foto Profil</th>
					  <td>
						<div class="col-md-8">
						  <img class="img-circle" src="img/foto-profil/<?php echo $user->foto_profil;?>" alt="<?php echo $user->nama_lengkap; ?>" style="max-width: 125px" />
						</div>
						<!-- /.col -->
					  </td>
					</tr>
				</table>
			  </div>
			  <!-- /.box-body -->
			</div>
			<!-- /.box -->
		  </div>
		  <div class="row">
			<div class="box box-default">
			  <div class="box-header with-border">
					<h3 class="box-title">Ubah Password</h3>
			  </div>
			  <div class="box-body table-condensed">
				<form action="profil_proses.php" method="post" data-toggle="validator" role="form" enctype="multipart/form-data">
					  <input type="hidden" name="ubah_password" />
					  <div class="form-group has-feedback">
						<label>Password lama</label>
						<input type="password" class="form-control"  placeholder="" name="pl" value="" data-error="Password wajib diisi." required /><div class="help-block with-errors"></div>
					  </div>
					  <div class="form-group has-feedback">
						<label>Password baru</label>
						<input type="password" class="form-control"  placeholder="" name="pb" id="pb" value=""  data-minlength="6" data-error="<?php echo $error[4]; ?>" required /><div class="help-block with-errors"></div>
					  </div>
					  <div class="form-group has-feedback">
						<label>Ulangi password baru</label>
						<input type="password" class="form-control"  placeholder="" name="up" value="" data-match="#pb" data-required-error="Password wajib dulangi." data-match-error="<?php echo $error[10]; ?>" required /><div class="help-block with-errors"></div>
					  </div>
					  <input type="submit" class="btn btn-primary pull-right" value="Ubah Password" />
				</form>
			  </div>
			</div>
		  </div>
		</div>
		<div class="col-md-5">
				<div class="box box-default">
				  <div class="box-header with-border">
					<h3 class="box-title">Edit Profil</h3>
				  </div>
				  <div class="box-body table-condensed">
					<form action="profil_proses.php" method="post" data-toggle="validator" role="form" enctype="multipart/form-data">
					  <input type="hidden" name="post" />
					  <div class="form-group has-feedback">
						<label>Nama lengkap (sesuai EYD)</label>
						<input type="text" class="form-control" maxlength="32" placeholder="Contoh: R. M. Aji Said P." name="nl" data-required-error="Nama lengkap wajib diisi." value="<?php echo $nama; ?>" required><div class="help-block with-errors"></div>
					  </div>
					  <div class="form-group has-feedback">
						<label>Username</label>
						<input type="text" class="form-control" maxlength="16" placeholder="" name="un" value="<?php echo $user->username; ?>" pattern="[a-z0-9_]{4,16}" data-remote="inc/check_auth.php" data-remote-error="<?php echo $error[7]; ?>" data-required-error="Username wajib diisi." data-pattern-error = "<?php echo $error[3]; ?>" required><div class="help-block with-errors"></div>
					  </div>
					  <div class="form-group has-feedback">
						<label>Status</label>
						<select class="form-control" name="st" required>
							<option value="1"<?php if($user->status == 1) echo " selected"; ?>>Mahasiswa IPB</option>
							<option value="2"<?php if($user->status == 2) echo " selected"; ?>>Umum</option>
						</select>
					  </div>
					  <div class="form-group has-feedback">
						<label>Nomor identitas (NIM / Nomor KTP)</label>
						<input type="text" class="form-control" maxlength="32" placeholder="" name="ni" data-remote="inc/check_auth.php" data-remote-error="<?php echo $error[8]; ?>" data-required-error="<?php echo "Nomor identitas wajib diisi."; ?>" value="<?php echo $user->id_status; ?>" required><div class="help-block with-errors"></div>
					  </div>
					  <div class="form-group has-feedback">
						<label>Tanggal lahir (HH-BB-TTTT)</label>
						<div class="input-group date" id="dp">
						  <div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						  </div>
						  <input type="text" class="form-control" id="datemask" name="tl" data-required-error="Tanggal lahir wajib diisi." value="<?php echo date_format(date_create($user->tanggal_lahir), 'd-m-Y');?>" required>
						</div>	
						<div class="help-block with-errors"></div>
					  </div>
					  <div class="form-group has-feedback">
						<label>Nomor HP</label>
						<input type="text" class="form-control" maxlength="13" placeholder="08xxxxxxxx..." name="nh" value="<?php echo $user->nomor_hp; ?>" pattern="08[0-9]{8,11}" data-remote="inc/check_auth.php" data-remote-error="<?php echo $error[5]; ?>" data-required-error="Nomor HP wajib diisi." data-pattern-error="<?php echo $error[1]; ?>" required><div class="help-block with-errors"></div>
					  </div>
					  <div class="form-group has-feedback">
						<label>Nomor WhatsApp (tidak wajib)</label>
						<input type="text" class="form-control" maxlength="13" placeholder="08xxxxxxxx..." name="nw" value="<?php echo $user->nomor_wa; ?>" pattern="08[0-9]{8,11}" data-pattern-error="<?php echo $error[1]; ?>" ><div class="help-block with-errors"></div>
					  </div>
					  <div class="form-group has-feedback">
						<label>Alamat email</label>
						<input type="email" class="form-control" maxlength="32" placeholder="alamat@email" name="ae" value="<?php echo $user->email; ?>" data-remote="inc/check_auth.php" data-remote-error="<?php echo $error[6]; ?>" data-error="<?php echo $error[2]; ?>" required><div class="help-block with-errors"></div>
					  </div>
					  <div class="form-group has-feedback">
						<label>Alamat tinggal</label>
						<textarea class="form-control" placeholder="" name="at" data-error="Alamat wajib diisi." required><?php echo $user->alamat; ?></textarea><div class="help-block with-errors"></div>
					  </div>
					  <div class="form-group has-feedback">
						<label>Mentoring</label>
						<select class="form-control" name="mt" required>
							<option value="1"<?php if($user->mentoring == 1) echo " selected"; ?>>Belum</option>
							<option value="2"<?php if($user->mentoring == 2) echo " selected"; ?>>Sudah</option>
						</select>
					  </div>
					  <div class="form-group has-feedback">
						<label>Nama murobbi (tidak wajib)</label>
						<input type="text" class="form-control" placeholder="" name="mb" value="<?php echo $user->nama_murobbi; ?>">
					  </div>
					  <div class="form-group has-feedback">
						<label>Nomor HP murobbi (tidak wajib)</label>
						<input type="text" class="form-control" maxlength="13" placeholder="08xxxxxxxx..." name="nm" value="<?php echo $user->nomor_murobbi; ?>" pattern="08[0-9]{8,11}" data-pattern-error="<?php echo $error[1]; ?>" ><div class="help-block with-errors"></div>
					  </div>
					  <?php if($s) { ?>
					  <div class="form-group has-feedback">
						<label>Placement Test</label>
						<select class="form-control" name="pt" required>
							<option value="1"<?php if($user->placement_test == 1) echo " selected"; ?>>Sabtu, 10 September 2016</option>
							<option value="2"<?php if($user->placement_test == 2) echo " selected"; ?>>Ahad, 11 September 2016</option>
							<option value="3"<?php if($user->placement_test == 3) echo " selected"; ?>>Tidak bisa keduanya</option>
						</select>
					  </div>
					  <?php } ?>
					  <div class="form-group has-feedback">
						<label>Ganti foto profil (tidak wajib)</label>
						<input type="file" class="form-control" name="fp" accept=".jpg,.jpeg,.png,.gif" style="padding-bottom: 40px">
						<p class="help-block">Format yang diterima: JPG, GIF, PNG. Ukuran maksimum 3 MB.</p>
					  </div>
					  <div class="row">
						<div class="col-xs-8">
						</div>
						<!-- /.col -->
						<div class="col-xs-4">
						  <button type="submit" class="btn btn-primary btn-block btn-flat">Edit Profil</button>
						</div>
						<!-- /.col -->
					  </div>
					</form>
				  </div>
				</div>
		</div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
<?php include "inc/footer.php"; ?>