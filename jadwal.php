<?php
	include "inc/header.php";
?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Jadwal
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i>SIM LPQ</a></li>
          <li class="active">Jadwal</li>
        </ol>
      </section>

      <!-- Main content -->
	  <?php if($s) include "inc/jadwal-santri.php"; ?>
	  <?php if($i) include "inc/jadwal-instruktur.php"; ?>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
<?php include "inc/footer.php"; ?>