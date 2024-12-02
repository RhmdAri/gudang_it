<?php
include '../connection.php';

if (isset($_GET['bulan']) && isset($_GET['tahun'])) {
    $bulan = $_GET['bulan'];
    $tahun = $_GET['tahun'];

    $query = "
        SELECT 
            pinjam.id, pinjam.waktu, pinjam.nama, pinjam.tempat, pinjam.keperluan, pinjam.status,
            GROUP_CONCAT(CONCAT(inventaris.nama, ' (', pinjam.jumlah, ')') SEPARATOR ', ') AS inventaris,
            petugas.nama AS namaPetugas
        FROM pinjam
        INNER JOIN petugas ON pinjam.idPetugas = petugas.id
        INNER JOIN inventaris ON inventaris.id = pinjam.idInventaris
        WHERE MONTH(pinjam.waktu) = '$bulan' AND YEAR(pinjam.waktu) = '$tahun'
        GROUP BY pinjam.nama, pinjam.tempat, pinjam.keperluan, pinjam.status, petugas.nama
        ORDER BY MAX(pinjam.waktu) DESC
    ";

    $result = mysqli_query($con, $query);

    while ($data = mysqli_fetch_array($result)) {
        $waktuFormatted = date('d-m-Y H:i:s', strtotime($data['waktu']));
        echo "
            <tr>
                <td>{$waktuFormatted}</td>
                <td>" . htmlspecialchars($data['nama']) . "</td>
                <td>" . htmlspecialchars($data['namaPetugas']) . "</td>
                <td>" . htmlspecialchars($data['inventaris']) . "</td>
                <td>" . htmlspecialchars($data['tempat']) . "</td>
                <td>" . htmlspecialchars($data['keperluan']) . "</td>
                <td>
                    <a class='btn waves-effect waves-dark btn-info btn-outline-info btn-sm' 
                       href='javascript:void(0);' 
                       onclick=\"confirmFinish('" . htmlspecialchars($data['nama']) . "')\">Selesai</a>
                </td>
                <td>" . htmlspecialchars($data['status']) . "</td>
            </tr>
        ";
    }
}
?>
