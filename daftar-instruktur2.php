<?php
	session_start();
	require "inc/connect.php";
	
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pendaftaran_instruktur'";
	$result = mysqli_query($connect,$query);
	$d_i = mysqli_fetch_object($result);
	if($d_i->isi==0) { $_SESSION['e']=5; header('Location: index.php'); exit(); }
	
	$query = "SELECT isi FROM sistem WHERE nama_pengaturan = 'pengumuman_instruktur'";
	$result = mysqli_query($connect,$query);
	$p_i = mysqli_fetch_object($result);
	
	include "inc/header.php";
	
	$error = array(	"Formulir ada yang kosong. Silakan isi ulang.",
					"Format nomor HP salah. Contoh: 081234567890 (10-13 digit).",
					"Nomor identitas sudah terdaftar.",
					"Format tanggal lahir salah. Contoh: 21-12-1992.",
					"Enrollment key tidak cocok.",
					"Masukkan angka komitmen antara 50 dan 100."
					);
	
	if(isset($_SESSION['e'])) {
		$e = $_SESSION['e'];
		unset($_SESSION['e']);
	}
	
	if(!(isset($_SESSION['nl']) &&
		isset($_SESSION['jk']) &&
		isset($_SESSION['st']) &&
		isset($_SESSION['nh']) &&
		isset($_SESSION['ae']) &&
		isset($_SESSION['un']) &&
		isset($_SESSION['pwd']) &&
		(isset($_SESSION['pr'][0]) || isset($_SESSION['pr'][1]) || isset($_SESSION['pr'][2])) &&
		isset($_SESSION['mt'])))
		{
			header('Location: daftar-instruktur.php');
			exit();
		}

	$n_i = ($_SESSION['st'] == 1) ? 'NIM' : 'Nomor KTP';
	$ni = isset($_SESSION['ni']) ? $_SESSION['ni'] : NULL; //nomor identitas
	$tl = isset($_SESSION['tl']) ? $_SESSION['tl'] : NULL; //tanggal lahir
	$nw = isset($_SESSION['nw']) ? $_SESSION['nw'] : NULL; //nomor whatsapp
	$at = isset($_SESSION['at']) ? $_SESSION['at'] : NULL; //alamat tinggal
	$mb = isset($_SESSION['mb']) ? $_SESSION['mb'] : NULL; //nama murobbi
	$nm = isset($_SESSION['nm']) ? $_SESSION['nm'] : NULL; //nomor murobbi
	$pd = (isset($_SESSION['pd']) && (isset($_SESSION['pr'][0]))) ? (int) $_SESSION['pd'] : NULL; //pendaftar
	$ms = (isset($_SESSION['ms']) && (isset($_SESSION['pr'][0]))) ? $_SESSION['ms'] : NULL; //memenuhi syarat
	$am = (isset($_SESSION['am']) && (isset($_SESSION['pr'][0]))) ? $_SESSION['am'] : NULL; //alasan mendaftar
	$ik = (isset($_SESSION['ik']) && (isset($_SESSION['pr'][0]))) ? $_SESSION['ik'] : NULL; //ide untuk kbm
	$kt = (isset($_SESSION['kt']) && (isset($_SESSION['pr'][0]))) ? (int) $_SESSION['kt'] : NULL; //komitmen tahsin
	$et = (isset($_SESSION['et']) && (isset($_SESSION['pr'][1]))) ? $_SESSION['et'] : NULL; //enrollment key tahfizh
	$pm = (isset($_SESSION['pm']) && (isset($_SESSION['pr'][2]))) ? (float) $_SESSION['pm'] : NULL; //pengalaman mengajar
	$bk = (isset($_SESSION['bk']) && (isset($_SESSION['pr'][2]))) ? $_SESSION['bk'] : NULL; //buku yang pernah dipelajari
	$mm = (isset($_SESSION['mm']) && (isset($_SESSION['pr'][2]))) ? $_SESSION['mm'] : NULL; //motivasi mengajar
	$eb = (isset($_SESSION['eb']) && (isset($_SESSION['pr'][2]))) ? $_SESSION['eb'] : NULL; //enrollment key bahasa arab
	$kb = (isset($_SESSION['kb']) && (isset($_SESSION['pr'][2]))) ? (int) $_SESSION['kb'] : NULL; //komitmen bahasa arab
?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Pendaftaran Instruktur
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i>SIM LPQ</a></li>
          <li class="active">Pendaftaran Instruktur</li>
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
                <p><?php echo $p_i->isi; ?></p>
        </div>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Formulir Pendaftaran (halaman 2 dari 2)</h3>
          </div>
          <div class="box-body">
            <form action="daftar-instruktur2_proses.php" method="post" data-toggle="validator" role="form" enctype="multipart/form-data">
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
			  <?php if(isset($_SESSION['pr'][0])) { ?>
			  <div class="row">
			   <div class="col-md-3"><h4>Instruktur Tahsin</h4></div>
			  </div>
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
					  <input type="checkbox" name="ms[1]" value="on"<?php if(isset($ms[1])) " checked"; ?>>
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
				<input type="number" class="form-control" placeholder="" name="kt" value="<?php echo $kt; ?>" min="50" max="100" data-error="<?php echo $error[5]; ?>" required>
				<div class="help-block with-errors"></div>
			   </div>
			   <div class="col-md-2">(50-100)</div>
			  </div>
			  <?php } ?>
			  <?php if(isset($_SESSION['pr'][1])) { ?>
			  <div class="row">
			   <div class="col-md-3"><h4>Instruktur Tahfizh/Takhosus</h4></div>
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="et">Enrollment key</label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="text" class="form-control" placeholder="" name="et" value="<?php echo $et; ?>" required>
				<div class="help-block">Rekrutmen tertutup, khusus untuk pendaftar yang telah menerima enrollment key.</div>
			   </div>
			   <div class="col-md-2"></div><br />
			  </div>
			  <?php } ?>
			  <?php if(isset($_SESSION['pr'][2])) { ?>
			  <div class="row">
			   <div class="col-md-3"><h4>Mu'alim Bahasa Arab</h4></div>
			  </div>
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
			   <div class="col-md-2"><label for="eb">Enrollment key</label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="text" class="form-control" placeholder="" name="eb" value="<?php echo $eb; ?>" required>
				<div class="help-block">Rekrutmen tertutup, khusus untuk pendaftar yang telah menerima enrollment key.</div>
			   </div>
			   <div class="col-md-2"></div><br />
			  </div>
			  <div class="row">
			   <div class="col-md-2"><label for="kb">Komitmen (%)</label></div>
			   <div class="form-group has-feedback col-md-8">
				<input type="number" class="form-control" placeholder="" name="kb" value="<?php echo $kb; ?>" min="50" max="100" data-error="<?php echo $error[5]; ?>" required>
				<div class="help-block with-errors"></div>
			   </div>
			   <div class="col-md-2">(50-100)</div>
			  </div>
			  <?php } ?>
			  <div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-6">
				Sudah terdaftar? Silakan <a href="index.php" class="text-center">login</a>.
				</div>
				<!-- /.col -->
				<div class="col-md-2">
					<button type="submit" class="btn btn-success btn-block btn-flat">Daftar</button>
				</div>
				<div class="col-md-2"></div>
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