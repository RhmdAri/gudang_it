<?php
include '../connection.php';

$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date('m');
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');

$query = "
    SELECT 
        masuk.id, masuk.waktu, masuk.jumlah,
        petugas.nama AS namaPetugas,
        barang.nama AS namaBarang
    FROM masuk
    INNER JOIN petugas ON masuk.idPetugas = petugas.id
    INNER JOIN barang ON masuk.idBarang = barang.id
    WHERE MONTH(masuk.waktu) = '$bulan' AND YEAR(masuk.waktu) = '$tahun'
    ORDER BY masuk.waktu DESC
";

$result = mysqli_query($con, $query);

if (!$result) {
    echo "Error: " . mysqli_error($con);
} else {
    while ($data = mysqli_fetch_array($result)) {
        $waktuFormatted = date('d-m-Y H:i:s', strtotime($data['waktu']));
        echo "
            <tr>
                <td>{$waktuFormatted}</td>
                <td>" . htmlspecialchars($data['namaPetugas']) . "</td>
                <td>" . htmlspecialchars($data['namaBarang']) . "</td>
                <td>" . htmlspecialchars($data['jumlah']) . "</td>
                <td>
                    <a class='btn btn-outline-success btn-sm' href='?page=masukEdit&id={$data['id']}'>
                        <i class='fa fa-pencil'></i>
                    </a>
                </td>
            </tr>
        ";
    }
}
?>
