<?php
    $con = mysqli_connect('localhost','root','','gudang_it');

    if (mysqli_connect_errno()){
        echo "<h1>Koneksi Database Error : " . mysqli_connect_errno() ."</h1>";
    }
?>