<?php
include('../connection.php'); 

$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date('m');
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');

// Query untuk menampilkan data berdasarkan bulan dan tahun
$query = "
    SELECT 
        keluar.id, keluar.waktu, keluar.jumlah,
        petugas.nama AS namaPetugas,
        barang.nama AS namaBarang
    FROM keluar
    INNER JOIN petugas ON keluar.idPetugas = petugas.id
    INNER JOIN barang ON keluar.idBarang = barang.id
    WHERE MONTH(keluar.waktu) = '$bulan' AND YEAR(keluar.waktu) = '$tahun'
    ORDER BY keluar.waktu DESC
";

$result = mysqli_query($con, $query);

// Cek apakah query berhasil dijalankan
if (!$result) {
    die("Query Error: " . mysqli_error($con));
} else {
    // Loop untuk menampilkan data
    while ($data = mysqli_fetch_array($result)) {
        $waktuFormatted = date('d-m-Y H:i:s', strtotime($data['waktu']));
        echo "<tr>";
        echo "<td>" . $waktuFormatted . "</td>";
        echo "<td>" . htmlspecialchars($data['namaPetugas']) . "</td>";
        echo "<td>" . htmlspecialchars($data['namaBarang']) . "</td>";
        echo "<td>" . htmlspecialchars($data['jumlah']) . "</td>";
        echo "<td>
                <a class='btn btn-outline-success btn-sm' href='?page=keluarEdit&id=" . $data['id'] . "'><i class='fa fa-pencil'></i></a>
              </td>";
        echo "</tr>";
    }
}
?>
