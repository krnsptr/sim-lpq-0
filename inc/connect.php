<?php
 $connect = mysqli_connect("localhost", "sim-lpq", "sim-lpq", "sim-lpq-0");
 if (!$connect) {
    die("Gagal koneksi ke database.\n" . mysqli_connect_error());
}
?>
