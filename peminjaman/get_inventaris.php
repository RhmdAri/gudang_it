<?php
include '../connection.php';

$divisi_tujuan = $_GET['divisi'] ?? '';

if ($divisi_tujuan) {
    $query = "SELECT id, nama, stok FROM inventaris WHERE divisi = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 's', $divisi_tujuan);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $inventaris = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $inventaris[] = $row;
    }
    echo json_encode($inventaris);
} else {
    echo json_encode(['error' => 'Divisi tujuan tidak valid.']);
}
?>
