<?php
 $connect = mysqli_connect("localhost", "lpqipb_sim", "@LoN-@LoN_asal_kelakon", "lpqipb_sim");
 if (!$connect) {
    die("Gagal koneksi ke database.\n" . mysqli_connect_error());
}
?>