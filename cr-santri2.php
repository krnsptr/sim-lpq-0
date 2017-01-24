<?php
	session_start();
	require "inc/connect.php";
	
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_santri'";
	$result = mysqli_query($connect,$query);
	$p_s = mysqli_fetch_object($result);
	
	include "inc/header.php";
	
	$error = array(	"Formulir ada yang kosong. Silakan isi ulang.",
					"Format nomor HP salah. Contoh: 081234567890 (10-13 digit).",
					"Nomor identitas sudah terdaftar.",
					"Format tanggal lahir salah. Contoh: 21-12-1992.",
					);
	
	if(isset($_SESSION['e'])) {
		$e = $_SESSION['e'];
		unset($_SESSION['e']);
	}
	
	if(!(!empty($_SESSION['nl']) &&
		!empty($_SESSION['jk']) &&
		!empty($_SESSION['st']) &&
		!empty($_SESSION['nh']) &&
		!empty($_SESSION['ae']) &&
		!empty($_SESSION['un']) &&
		!empty($_SESSION['pwd']) &&
		(!empty($_SESSION['pr'][0]) || !empty($_SESSION['pr'][1]) || !empty($_SESSION['pr'][2])) &&
		!empty($_SESSION['mt']) &&
		!empty($_SESSION['pt'])))
		{
			header('Location: cr-santri.php');
			exit();
		}

	$n_i = ($_SESSION['st'] == 1) ? 'NIM' : 'Nomor KTP';
	$ni = !empty($_SESSION['ni']) ? $_SESSION['ni'] : NULL; //nomor identitas
	$tl = !empty($_SESSION['tl']) ? $_SESSION['tl'] : NULL; //tanggal lahir
	$nw = !empty($_SESSION['nw']) ? $_SESSION['nw'] : NULL; //nomor whatsapp
	$at = !empty($_SESSION['at']) ? $_SESSION['at'] : NULL; //alamat tinggal
	$mb = !empty($_SESSION['mb']) ? $_SESSION['mb'] : NULL; //nama murobbi
	$nm = !empty($_SESSION['nm']) ? $_SESSION['nm'] : NULL; //nomor murobbi
	$sl = (!empty($_SESSION['sl']) && (!empty($_SESSION['pr'][0]) || !empty($_SESSION['pr'][1]))) ? $_SESSION['sl'] : NULL; //sudah lulus?
	$bb = !empty($_SESSION['bb']) ? $_SESSION['bb'] : array(NULL, NULL, NULL, NULL, NULL); //beli/pesan buku?
	$pb = (!empty($_SESSION['pb']) && (!empty($_SESSION['pr'][2]))) ? $_SESSION['pb'] : NULL; //pengalaman belajar bahasa arab
	
?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Pendaftaran Santri
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i>SIM LPQ</a></li>
          <li class="active">Pendaftaran Santri</li>
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
		<div class="callout callout-info">
                <h4><i class="icon fa fa-info"></i>&nbsp;&nbsp;&nbsp;Pengumuman</h4>
                <p><?php echo $p_s->isi; ?></p>
        </div>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Formulir Pendaftaran (halaman 2 dari 2)</h3>
          </div>
          <div class="box-body">
            <form action="cr-santri2_proses.php" method="post" data-toggle="validator" role="form" enctype="multipart/form-data">
			  <input type="hidden" name="post" />
			  <div class="row">
			   <div class="col-md-2"><label for="ni"><?php echo $n_i; ?></label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="text" class="form-control" maxlength="32" placeholder="" name="ni" data-remote="inc/check.php" data-remote-error="<?php echo $error[2]; ?>" data-required-error="<?php echo "$n_i wajib diisi."; ?>" value="<?php echo $ni; ?>" required><div class="help-block with-errors"></div>
			   </div>
			   <div class="col-md-2"></div>
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="tl">Tanggal lahir</label></div>
			   <div class="form-group has-feedback col-md-8">
				<div class="input-group date" id="">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" placeholder="HH-BB-TTTT" class="form-control" id="datemask" name="tl" data-required-error="Tanggal lahir wajib diisi." value="<?php echo $tl; ?>" required>
                </div>
				<div class="help-block with-errors"></div>
			   </div>
			   <div class="col-md-2">HH-BB-TTTT</div><br />
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="nw">Nomor WhatsApp</label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="text" class="form-control" maxlength="13" placeholder="08xxxxxxxx..." name="nw" value="<?php echo $nw; ?>" pattern="08[0-9]{8,11}" data-pattern-error="<?php echo $error[1]; ?>" ><div class="help-block with-errors"></div>
			   </div>
			   <div class="col-md-2">(tidak wajib)</div><br />
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="at">Alamat tinggal</label></div>
			   <div class="form-group has-feedback col-md-8">
				<textarea class="form-control" placeholder="" name="at" data-error="Alamat wajib diisi." required><?php echo $at; ?></textarea><div class="help-block with-errors"></div>
			   </div>
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="fp">Foto profil</label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="file" class="form-control" name="fp" accept=".jpg,.jpeg,.png,.gif" style="padding-bottom: 40px">
				<p class="help-block">Format yang diterima: JPG, GIF, PNG. Ukuran maksimum 3 MB.</p>
			   </div>
			   <div class="col-md-2">(tidak wajib)</div><br />
			  </div>
			  <?php if($_SESSION['mt'] == 2) { ?>
			  <div class="row">
			   <div class="col-md-2"><label for="mb">Nama murobbi</label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="text" class="form-control" placeholder="" name="mb" value="<?php echo $mb; ?>">
			   </div>
			   <div class="col-md-2">(tidak wajib)</div><br />
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="nm">Nomor HP murobbi</label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="text" class="form-control" maxlength="13" placeholder="08xxxxxxxx..." name="nm" value="<?php echo $nm; ?>" pattern="08[0-9]{8,11}" data-pattern-error="<?php echo $error[1]; ?>" ><div class="help-block with-errors"></div>
			   </div>
			   <div class="col-md-2">(tidak wajib)</div><br />
			  </div>
			  <?php } ?>
			  <?php if(isset($_SESSION['pr'][0]) || isset($_SESSION['pr'][1])) { ?>
			  <div class="row">
			   <div class="col-md-3"><h4>Santri Tahsin/Tahfizh/Takhosus</h4></div>
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="sl">Sudah lulus?</label></div>
			   <div class="form-group has-feedback col-md-8">
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
			   <div class="col-md-2"></div>
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="bb">Pesan buku?</label></div>
			   <div class="form-group has-feedback col-md-8">
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
			   </div>
			   <div class="col-md-2">(tidak wajib)</div>
			  </div>
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
			  <?php } ?>
			  <?php if(isset($_SESSION['pr'][2])) { ?>
			  <div class="row">
			   <div class="col-md-3"><h4>Santri Bahasa Arab</h4></div>
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="pb">Pengalaman belajar (tahun)</label></div>
			   <div class="form-group has-feedback col-md-5">
				<input type="number" class="form-control" placeholder="" name="pb" value="<?php echo $pb; ?>" step="any" min="0">
				<div class="help-block">Kosongkan jika belum pernah belajar Bahasa Arab sebelumnya.</div>
			   </div>
			   <div class="col-md-3">tahun</div>
			   <div class="col-md-2">(tidak wajib)</div><br />
			  </div>
			  <?php } ?>
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
			  <div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-6">
				Sudah terdaftar? Silakan <a href="index.php" class="text-center">login</a>.
				</div>
				<!-- /.col -->
				<div class="col-md-2"></div>
				<div class="col-md-2">
					<button type="submit" class="btn btn-success btn-block btn-flat">Daftar</button>
				</div>
				<!-- /.col -->
			  </div>
			</form>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
<?php include "inc/footer.php"; ?>