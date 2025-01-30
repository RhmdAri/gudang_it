<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include('../connection.php');

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

if (isset($_GET['export_excel']) && $_GET['export_excel'] === 'true') {
    $bulanArray = [
        '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
        '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
        '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
    ];
    $namaBulan = $bulanArray[$bulan];

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->mergeCells('A1:E1');
    $sheet->setCellValue('A1', 'Laporan Barang Keluar');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->mergeCells('A2:E2');
    $sheet->setCellValue('A2', "$namaBulan $tahun");
    $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A3', 'No')
          ->setCellValue('B3', 'Waktu')
          ->setCellValue('C3', 'Nama Petugas')
          ->setCellValue('D3', 'Nama Barang')
          ->setCellValue('E3', 'Jumlah');
    
    $sheet->getStyle('A3:E3')->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FFFF00'],
        ],
        'font' => [
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ]);

    $query = "
        SELECT keluar.waktu, petugas.nama AS namaPetugas, barang.nama AS namaBarang, keluar.jumlah
        FROM keluar
        INNER JOIN petugas ON keluar.idPetugas = petugas.id
        INNER JOIN barang ON keluar.idBarang = barang.id
        WHERE MONTH(keluar.waktu) = '$bulan' AND YEAR(keluar.waktu) = '$tahun'
        ORDER BY keluar.waktu DESC";

    $result = mysqli_query($con, $query);
    $rowNumber = 4;
    $no = 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $waktuFormatted = date('d-m-Y H:i:s', strtotime($row['waktu']));
        $sheet->setCellValue('A' . $rowNumber, $no++);
        $sheet->setCellValue('B' . $rowNumber, $waktuFormatted);
        $sheet->setCellValue('C' . $rowNumber, $row['namaPetugas']);
        $sheet->setCellValue('D' . $rowNumber, $row['namaBarang']);
        $sheet->setCellValue('E' . $rowNumber, $row['jumlah']);
        $rowNumber++;
    }

    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ];

    $sheet->getStyle('A3:E' . ($rowNumber - 1))->applyFromArray($styleArray);
    foreach (range('A', 'E') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=\"Laporan_Barang_Keluar_{$namaBulan}_{$tahun}.xlsx\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

$bulanNama = date('F', mktime(0, 0, 0, $bulan, 10));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Barang Masuk</title>
    <style>
        * {
            font-family: Arial, sans-serif;
        }
        body {
            margin: 0;
            padding: 20px;
        }
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat img {
            width: 100px;
        }
        .kop-surat .title {
            text-align: center;
            flex: 1;
            margin-left: 10px;
        }
        .kop-surat .title h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .kop-surat .title p {
            margin: 0;
            font-size: 14px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }
        table th, table td {
            padding: 5px;
            text-align: center;
        }
        thead {
            background-color: #28a745;
            color: white;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
        }
        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    include '../connection.php';
    $divisi = isset($_SESSION['divisi']) ? $_SESSION['divisi'] : null;
    $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
    $bulanNama = date('F', mktime(0, 0, 0, $bulan, 10));
    $currentDate = date("d F Y");
    $level = isset($_SESSION['level']) ? $_SESSION['level'] : 'user';
    $nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Anonim';
    include '../connection.php';
    ?>
    <div class="kop-surat">
        <img src="../assets/images/rsisa.png" alt="Logo RSI Sultan Agung Banjarbaru">
        <div class="title">
            <h1>RSI Sultan Agung Banjarbaru</h1>
            <p>Laporan Barang Keluar</p>
            <p><?= $bulanNama ?> <?= $tahun ?></p>
        </div>
    </div>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>Nama Petugas</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "
                SELECT keluar.waktu, petugas.nama AS namaPetugas, barang.nama AS namaBarang, keluar.jumlah
                FROM keluar
                INNER JOIN petugas ON keluar.idPetugas = petugas.id
                INNER JOIN barang ON keluar.idBarang = barang.id
                WHERE MONTH(keluar.waktu) = '$bulan' AND YEAR(keluar.waktu) = '$tahun'
                ORDER BY keluar.waktu DESC
            ";
            $result = mysqli_query($con, $query);
            $no = 1;
            while ($data = mysqli_fetch_array($result)) {
                $waktuFormatted = date('d-m-Y H:i:s', strtotime($data['waktu']));
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $waktuFormatted ?></td>
                <td><?= htmlspecialchars($data['namaPetugas']) ?></td>
                <td><?= htmlspecialchars($data['namaBarang']) ?></td>
                <td><?= htmlspecialchars($data['jumlah']) ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="footer">
        <table width="100%">
            <tr>
                <td align="right">
                    <p style="font-size: 14px;"><strong>Banjarbaru, <?= $currentDate ?></strong></p> 
                    <?php if ($level === 'kepala') { ?>
                    <div class="barcode" style="margin: 10px 0;">
                        <img src="../assets/images/pengesahan.png" alt="Barcode" style="width: 100px;">
                    </div>
                    <?php } ?>
                    <p style="font-size: 14px;"><strong><?= $nama ?></strong></p> 
            </tr>
        </table>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>