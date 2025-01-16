<?php
include '../connection.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['level'])) {
    die('User not logged in');
}

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$loggedInUserId = $_SESSION['user_id'];
$userLevel = $_SESSION['level'];

$queryCondition = "WHERE MONTH(pinjam.waktu) = '$bulan' AND YEAR(pinjam.waktu) = '$tahun'";
if ($userLevel !== 'admin') {
    $queryCondition .= " AND pinjam.idUser = '$loggedInUserId'";
}

$query = "
    SELECT 
        pinjam.id, pinjam.waktu, pinjam.nama, pinjam.tempat, pinjam.keperluan, pinjam.status,
        GROUP_CONCAT(CONCAT(inventaris.nama, ' (', pinjam.jumlah, ')') SEPARATOR ', ') AS inventaris,
        petugas.nama AS namaPetugas
    FROM pinjam
    INNER JOIN petugas ON pinjam.idPetugas = petugas.id
    INNER JOIN inventaris ON inventaris.id = pinjam.idInventaris
    $queryCondition
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
?>
