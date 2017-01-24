<?php
	require "inc/connect.php";
	require "inc/auth.php";
	if(isset($_SESSION['o'])) { $o = $_SESSION['o']; unset($_SESSION['o']); }
	else if(isset($_SESSION['e'])) { $e = $_SESSION['e']; unset($_SESSION['e']); }
	
	$error = array(	"Formulir ada yang kosong. Silakan isi ulang.",
					"Format nomor HP salah. Contoh: 081234567890 (10-13 digit).",
					"Enrollment key tidak cocok.",
					"Masukkan angka komitmen antara 50 dan 100.",
					"Data gagal diubah"
					);
	
	if($s) {	//login sebagai santri
		$query = "SELECT * FROM santri s,anggota a WHERE id_santri = '$id' AND a.id_anggota = s.id_anggota";
		$result = mysqli_query($connect,$query);
		$user = mysqli_fetch_object($result);
	}
	else if($i) {	//login sebagai instruktur
		$query = "SELECT * FROM instruktur i,anggota a WHERE id_instruktur = '$id' AND a.id_anggota = i.id_anggota";
		$result = mysqli_query($connect,$query);
		$user = mysqli_fetch_object($result);
	}
	
	$keanggotaan = array(NULL,0,0,0);
	$query = "SELECT program, keanggotaan FROM program WHERE id_anggota =".$user->id_anggota;
	$result = mysqli_query($connect,$query);
	while($program = mysqli_fetch_object($result)) $keanggotaan[$program->program] = $program->keanggotaan;
	
	if(!empty($_GET['program'])) {
		$prog = (int) $_GET['program'];
		if($keanggotaan[$prog] == 2) {
			$query = 'SELECT * FROM instruktur i, pertanyaan_instruktur pi WHERE i.id_anggota ='.$user->id_anggota.' AND i.id_instruktur = pi.id_instruktur AND program = '.$prog;
		}
		else if($keanggotaan[$prog] == 1) {
			$query = 'SELECT * FROM santri s, pertanyaan_santri ps WHERE s.id_anggota ='.$user->id_anggota.' AND s.id_santri = ps.id_santri AND program = '.$prog;
		}
		else {
			header('Location: dasbor.php');
			exit();
		}
		$result = mysqli_query($connect,$query);
		$jawaban = mysqli_fetch_object($result);
	}
	else {
		header('Location: dasbor.php'); exit();
	}
	
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_santri'";
	$result = mysqli_query($connect,$query);
	$p_s = mysqli_fetch_object($result);
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_instruktur'";
	$result = mysqli_query($connect,$query);
	$p_i = mysqli_fetch_object($result);
	
	include "inc/header.php";
?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Ubah Data Program
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i>SIM LPQ</a></li>
          <li class="active">Ubah Data Program</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
		<?php if(isset($e)) { ?>
			<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-ban"></i> Kesalahan!</h4>
					<?php echo $error[$e]; ?>
			</div>
			<?php } ?>
			<?php if(isset($o)) { ?>
			<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-check"></i> Berhasil!</h4>
					Data berhasil diubah.
			</div>
			<?php } ?>
		<?php
			if($keanggotaan[$prog] == 1) {
				if($prog == 1 || $prog == 2) {
					$sl = $jawaban->jawaban1;
					$jawaban2 = explode(':',$jawaban->jawaban2,-1);
					foreach($jawaban2 as $key => $value) $bb[$value] = $value;
		?>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Santri Tahsin/Tahfizh/Takhosus</h3>
          </div>
          <div class="box-body">
			<form action="program_ubah_proses.php" method="post" data-toggle="validator" role="form" enctype="multipart/form-data">
				<input type="hidden" name="post" />
				<input type="hidden" name="program" value="<?php echo $prog; ?>" />
				<div class="form-group has-feedback">
					<label for="sl">Sudah lulus?</label>
					<div class="radio">
						<label>
						  <input type="radio" name="sl" value="0"<?php if($sl==0) echo " checked"; ?> required>
						  Belum pernah mengikuti Tahsin
						</label>
					</div>
					<div class="radio">
						<label>
						  <input type="radio" name="sl" value="1"<?php if($sl==1) echo " checked"; ?> required>
						  Belum lulus Pra-Tahsin (mengulang)
						</label>
					</div>
					<div class="radio">
						<label>
						  <input type="radio" name="sl" value="2"<?php if($sl==2) echo " checked"; ?> required>
						  Lulus Pra-Tahsin
						</label>
					</div>
					<div class="radio">
						<label>
						  <input type="radio" name="sl" value="3"<?php if($sl==3) echo " checked"; ?> required>
						  Belum lulus Tahsin 1 (mengulang)
						</label>
					</div>
					<div class="radio">
						<label>
						  <input type="radio" name="sl" value="4"<?php if($sl==4) echo " checked"; ?> required>
						  Lulus Tahsin 1
						</label>
					</div>
					<div class="radio">
						<label>
						  <input type="radio" name="sl" value="5"<?php if($sl==5) echo " checked"; ?> required>
						  Belum lulus Tahsin 2 (mengulang)
						</label>
					</div>
					<div class="radio">
						<label>
						  <input type="radio" name="sl" value="6"<?php if($sl==6) echo " checked"; ?> required>
						  Lulus Tahsin 2
						</label>
					</div>
					<div class="radio">
						<label>
						  <input type="radio" name="sl" value="7"<?php if($sl==7) echo " checked"; ?> required>
						  Tahfizh/Takhosus (melanjutkan)
						</label>
					</div>
				</div>
				<div class="form-group has-feedback">
					<label for="bb">Pesan buku?</label>
					<div class="checkbox">
						<label>
						  <input type="checkbox" name="bb[1]" value="on"<?php if(!empty($bb[1])) echo " checked"; ?>>
						  Buku Tahsin 1: <em>Panduan Tahsin Tilawah</em> (Rp22.000)
						</label>
					</div>
					<div class="checkbox">
						<label>
						  <input type="checkbox" name="bb[2]" value="on"<?php if(!empty($bb[2])) echo " checked"; ?>>
						  Buku Tahsin 2: <em>Pedoman Daurah Al-Qur'an Panduan Ilmu Tajwid Aplikatif</em> (Rp40.000)
						</label>
					</div>
					<div class="checkbox">
						<label>
						  <input type="checkbox" name="bb[3]" value="on"<?php if(!empty($bb[3])) echo " checked"; ?>>
						  Buku Takhosus: <em>Panduan Talaqqi Bacaan Ghorib</em> (Rp10.000)
						</label>
					</div>
					<div class="checkbox">
						<label>
						  <input type="checkbox" name="bb[4]" value="on"<?php if(!empty($bb[4])) echo " checked"; ?>>
						  Buku <em>`Ulumul Qur'an Program Tahsin-Tahfizh</em> (Rp20.000)
						</label>
					</div>
					<div class="checkbox">
						<label>
						  <input type="checkbox" name="bb[5]" value="on"<?php if(!empty($bb[5])) echo " checked"; ?>>
						  Mushaf Utsmani kecil (Rp40.000)
						</label>
					</div>
					<p class="help-block">Harga dapat berubah sewaktu-waktu.</p>
				</div><br />
				<div class="row">
					 <div class="col-md-2"><strong>Contoh buku</strong></div>
					 <div class="col-md-2" style="text-align: center">
						<img src="img/bb1.jpg" alt="Buku Tahsin 1: Pedoman Tahsin Tilawah" style="max-width: 100px;"/><br />
						Buku Tahsin 1
					</div>
					 <div class="col-md-2" style="text-align: center">
						<img src="img/bb2.jpg" alt="Buku Tahsin 2: Pedoman Daurah Al-Qur'an" style="max-width: 100px;"/><br />
						Buku Tahsin 2
					 </div>
					 <div class="col-md-2" style="text-align: center">
						<img src="img/bb3.jpg" alt="Buku Takhosus: Pedoman Talaqqi Bacaan Ghorib" style="max-width: 100px;"/><br />
						Buku Takhosus
					 </div>
					 <div class="col-md-2" style="text-align: center">
						<img src="img/bb4.jpg" alt="Buku `Ulumul Qur'an Program Tahsin-Tahfizh" style="max-width: 100px;"/><br />
						`Ulumul Qur'an
					 </div>
					 <div class="col-md-2" style="text-align: center">
						<img src="img/bb5.jpg" alt="Mushaf Utsmani kecil" style="max-width: 105px;"/><br />
						Mushaf Utsmani kecil
					 </div>
				</div><br />
				<div class="row">
				   <div class="col-md-5"><h4>Informasi Pembayaran</h4></div>
				</div>
				<div class="row">
					 <div class="col-md-2"><strong>Biaya Administrasi (SPP)</strong></div>
					 <div class="col-md-2" style="text-align: center">
						<strong>Tahsin</strong><br />
						Rp50.000/semester
					 </div>
					 <div class="col-md-2" style="text-align: center">
						<strong>Tahfizh/Takhosus</strong><br />
						Rp50.000/semester
					 </div>
					 <div class="col-md-2" style="text-align: center">
						<strong>Bahasa Arab</strong><br />
						Rp50.000/semester
					 </div>
					 <div class="col-md-4"></div>
				</div><br />
				<div class="row">
					 <div class="col-md-2"></div>
					 <div class="col-md-10">Periode pembayaran: 10&ndash;18 September 2016.<br />
					 Pembayaran dapat dilakukan secara langsung di Sekretariat LPQ Al-Hurriyyah atau di Asrama Masjid Al-Hurriyyah.<br />
					 CP: 0877-6484-6361 (Luqni), 0852-2595-2060 (Annis)</div>
				</div><br />
				<input type="submit" value="Ubah data" class="btn btn-primary" />
			</form>
		  </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
		<?php
				}
				else if($prog == 3) {
					$pb = $jawaban->jawaban1;
		?>
		<div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Santri Bahasa Arab</h3>
          </div>
          <div class="box-body">
			<form action="program_ubah_proses.php" method="post" data-toggle="validator" role="form" enctype="multipart/form-data">
				<input type="hidden" name="post" />
				<input type="hidden" name="program" value="<?php echo $prog; ?>" />
				<label for="pb">Pengalaman belajar (tahun)</label><br />
				<div class="form-group has-feedback col-md-5">
					<input type="number" class="form-control" placeholder="" name="pb" value="<?php if($pb != '') echo $pb; ?>" step="any" min="0">
					<div class="help-block">Kosongkan jika belum pernah belajar Bahasa Arab sebelumnya.</div>
				</div>
				<input type="submit" value="Ubah data" class="btn btn-primary" />
			</form>
				<div class="row">
				   <div class="col-md-5"><h4>Informasi Pembayaran</h4></div>
				</div>
				<div class="row">
					 <div class="col-md-2"><strong>Biaya Administrasi (SPP)</strong></div>
					 <div class="col-md-2" style="text-align: center">
						<strong>Tahsin</strong><br />
						Rp50.000/semester
					 </div>
					 <div class="col-md-2" style="text-align: center">
						<strong>Tahfizh/Takhosus</strong><br />
						Rp50.000/semester
					 </div>
					 <div class="col-md-2" style="text-align: center">
						<strong>Bahasa Arab</strong><br />
						Rp50.000/semester
					 </div>
					 <div class="col-md-4"></div>
				</div><br />
				<div class="row">
					 <div class="col-md-2"></div>
					 <div class="col-md-10">Periode pembayaran: 10&ndash;18 September 2016.<br />
					 Pembayaran dapat dilakukan secara langsung di Sekretariat LPQ Al-Hurriyyah atau di Asrama Masjid Al-Hurriyyah.<br />
					 CP: 0877-6484-6361 (Luqni), 0852-2595-2060 (Annis)</div>
				</div><br />
		  </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
		<?php		
				}
			}
			else if($keanggotaan[$prog] == 2) {
				if($prog == 1) {
					$pd = $jawaban->jawaban1;
					$ms = array_filter(explode(':', $jawaban->jawaban2, -1), 'strlen');
					$am = $jawaban->jawaban3;
					$ik = $jawaban->jawaban4;
					$kt = $jawaban->jawaban5;
		?>
		<div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Instruktur Tahsin</h3>
          </div>
          <div class="box-body">
			<form action="program_ubah_proses.php" method="post" data-toggle="validator" role="form" enctype="multipart/form-data">
				<input type="hidden" name="post" />
				<input type="hidden" name="program" value="<?php echo $prog; ?>" />
				  <div class="row">
				   <div class="col-md-2"><label for="pd">Pendaftaran</label></div>
				   <div class="form-group has-feedback col-md-8">
					<select class="form-control" name="pd" required>
						<option value="1"<?php  if($pd == 1) echo " selected"; ?>>Pendaftaran baru</option>
						<option value="2"<?php  if($pd == 2) echo " selected"; ?>>Pendaftaran ulang</option>
					</select>
					<div class="help-block">Pendaftaran ulang khusus untuk instruktur Tahsin lama yang sudah pernah mengikuti wawancara.</div>
				   </div>
				  </div>
				  <div class="row">
				   <div class="col-md-2"><label for="ms">Memenuhi syarat?</label></div>
				   <div class="form-group has-feedback col-md-8">
					<div class="checkbox">
						<label>
						  <input type="checkbox" name="ms[0]" value="on"<?php if(isset($ms[0])) echo " checked"; ?>>
						  Lulus Tahsin 2
						</label>
					</div>
					<div class="checkbox">
						<label>
						  <input type="checkbox" name="ms[1]" value="on"<?php if(isset($ms[1])) echo " checked"; ?>>
						  Lulus Dauroh Syahadah
						</label>
					</div>
					<div class="checkbox">
						<label>
						  <input type="checkbox" name="ms[2]" value="on"<?php if(isset($ms[2])) echo " checked"; ?>>
						  Berkompetensi mengajar
						</label>
					</div>
				   </div>
				   <div class="col-md-2"></div>
				  </div>
				  <div class="row">
				   <div class="col-md-2"><label for="am">Alasan mendaftar</label></div>
				   <div class="form-group has-feedback col-md-8">
					<textarea class="form-control" placeholder="" name="am"><?php echo $am; ?></textarea>
				   </div>
				   <div class="col-md-2">(tidak wajib)</div>
				  </div>
				  <div class="row">
				   <div class="col-md-2"><label for="ik">Ide untuk KBM</label></div>
				   <div class="form-group has-feedback col-md-8">
					<textarea class="form-control" placeholder="" name="ik"><?php echo $ik; ?></textarea>
				   </div>
				   <div class="col-md-2">(tidak wajib)</div>
				  </div>
				  <div class="row">
				   <div class="col-md-2"><label for="kt">Komitmen (%)</label></div>
				   <div class="form-group has-feedback col-md-8">
					<input type="number" class="form-control" placeholder="" name="kt" value="<?php echo $kt; ?>" min="50" max="100" data-error="<?php echo $error[3]; ?>" required>
					<div class="help-block with-errors"></div>
				   </div>
				   <div class="col-md-2">(50-100)</div>
				  </div>
				  <input type="submit" value="Ubah data" class="btn btn-primary" />
			</form>
		  </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
		<?php
				}
				else if($prog == 2) {
		?>
		<div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Instruktur Tahfizh/Takhosus</h3>
          </div>
          <div class="box-body">
			Tidak ada data yang perlu diubah.
		  </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
		<?php
				}
				else if($prog == 3) {
					$pm = $jawaban->jawaban1;
					$bk = $jawaban->jawaban2;
					$mm = $jawaban->jawaban3;
					$kb = $jawaban->jawaban5;
		?>
		<div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Mu'alim Bahasa Arab</h3>
          </div>
          <div class="box-body">
			<form action="program_ubah_proses.php" method="post" data-toggle="validator" role="form" enctype="multipart/form-data">
				<input type="hidden" name="post" />
				<input type="hidden" name="program" value="<?php echo $prog; ?>" />
				  <div class="row">
				   <div class="col-md-2"><label for="pm">Pengalaman mengajar (tahun)</label></div>
				   <div class="form-group has-feedback col-md-5">
					<input type="number" class="form-control" placeholder="" name="pm" value="<?php echo $pm; ?>" step="any" min="0">
					<div class="help-block">Kosongkan jika belum pernah mengajar Bahasa Arab sebelumnya.</div>
				   </div>
				   <div class="col-md-3">tahun</div>
				   <div class="col-md-2">(tidak wajib)</div><br />
				  </div>
				  <div class="row">
				   <div class="col-md-2"><label for="bk">Buku yang pernah dipelajari</label></div>
				   <div class="form-group has-feedback col-md-8">
					<textarea class="form-control" placeholder="" name="bk"><?php echo $bk; ?></textarea>
				   </div>
				   <div class="col-md-2">(tidak wajib)</div>
				  </div>
				  <div class="row">
				   <div class="col-md-2"><label for="mm">Motivasi mengajar</label></div>
				   <div class="form-group has-feedback col-md-8">
					<textarea class="form-control" placeholder="" name="mm"><?php echo $mm; ?></textarea>
				   </div>
				   <div class="col-md-2">(tidak wajib)</div>
				  </div>
				  <div class="row">
				   <div class="col-md-2"><label for="kb">Komitmen (%)</label></div>
				   <div class="form-group has-feedback col-md-8">
					<input type="number" class="form-control" placeholder="" name="kb" value="<?php echo $kb; ?>" min="50" max="100" data-error="<?php echo $error[3]; ?>" required>
					<div class="help-block with-errors"></div>
				   </div>
				   <div class="col-md-2">(50-100)</div>
				  </div>
				  <input type="submit" value="Ubah data" class="btn btn-primary" />
			</form>
		  </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
		<?php
				}
			}
		?>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
<?php include "inc/footer.php"; ?>