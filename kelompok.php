<?php
	include "inc/header.php";
	$e = FALSE;
	$jenjang = isset($user->jenjang) ? $user->jenjang : NULL;
	if($jenjang==2) $j='Tahsin 2'; else if($jenjang==1) $j='Tahsin 1'; else $j='Pra-Tahsin';
?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Kelompok
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i>SIM LPQ</a></li>
          <li class="active">Kelompok</li>
        </ol>
      </section>
<?php
	if($i) include "inc/kelompok-instruktur.php";
	else if($s) include "inc/kelompok-santri.php";
?>	  
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
<?php include "inc/footer.php"; ?>