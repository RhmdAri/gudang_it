<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include('../connection.php');
session_start();
$divisi = isset($_SESSION['divisi']) ? $_SESSION['divisi'] : null;
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$bulanArray = [
    '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
    '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
    '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
];
$namaBulan = $bulanArray[$bulan];
if (isset($_GET['export_excel']) && $_GET['export_excel'] == 'true') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->mergeCells('A1:E1');
    $sheet->setCellValue('A1', 'Laporan Data Kegiatan');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->mergeCells('A2:E2');
    $sheet->setCellValue('A2', $namaBulan . ' ' . $tahun);
    $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A3', 'No')
          ->setCellValue('B3', 'Waktu')
          ->setCellValue('C3', 'Nama Petugas')
          ->setCellValue('D3', 'Tempat')
          ->setCellValue('E3', 'Kegiatan');

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

    $query = "SELECT * FROM kegiatan WHERE devisi = '$divisi' AND MONTH(waktu) = '$bulan' AND YEAR(waktu) = '$tahun'";
    $result = mysqli_query($con, $query);

    $rowNumber = 4;
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $sheet->setCellValue('A' . $rowNumber, $no++);
        $sheet->setCellValue('B' . $rowNumber, $row['waktu']);
        $sheet->setCellValue('C' . $rowNumber, $row['petugas']);
        $sheet->setCellValue('D' . $rowNumber, $row['tempat']);
        $sheet->setCellValue('E' . $rowNumber, $row['kegiatan']);
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
    header('Content-Disposition: attachment;filename="Data_Kegiatan_' . $namaBulan . '_' . $tahun . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Nama Pegawai Tidak Dikenal';
$bulanNama = date('F', mktime(0, 0, 0, $bulan, 10));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Kegiatan</title>
    <style>
        * { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; font-size: 12px; width: 100%; }
        table th, table td { padding: 5px; text-align: center; }
        .kop-surat { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat img { width: 100px; }
        .kop-surat .title { text-align: center; flex: 1; margin-left: 10px; }
        .kop-surat .title h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .kop-surat .title p { margin: 0; font-size: 14px; }
        thead { background-color: #28a745; color: white; }
        .table-container { margin-top: 20px; }
        .footer { margin-top: 50px; }
        .footer p { margin: 0; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <img src="../assets/images/rsisa.png" alt="Logo RSI Sultan Agung Banjarbaru">
        <div class="title">
            <h1>RSI Sultan Agung Banjarbaru</h1>
            <p>Laporan Kegiatan</p>
            <p><?= $bulanNama ?> <?= $tahun ?></p>
        </div>
    </div>
    <div class="table-container">
        <table border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>Petugas</th>
                    <th>Tempat</th>
                    <th>Kegiatan</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT * FROM kegiatan WHERE devisi = '$divisi' AND MONTH(waktu) = '$bulan' AND YEAR(waktu) = '$tahun' ORDER BY waktu DESC";
            $result = mysqli_query($con, $query) or die(mysqli_error($con));            
            $no = 1;
            while ($data = mysqli_fetch_array($result)) {
                $waktuFormatted = date('d-m-Y H:i:s', strtotime($data['waktu']));
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $waktuFormatted ?></td>
                    <td><?= htmlspecialchars($data['petugas']) ?></td>
                    <td><?= htmlspecialchars($data['tempat']) ?></td>
                    <td><?= htmlspecialchars($data['kegiatan']) ?></td>
                    <td>
                        <?php if ($data['foto']) { ?>
                            <img src="../assets/foto/<?= htmlspecialchars($data['foto']) ?>" width="100" alt="Foto Kegiatan">
                        <?php } else { ?>
                            Tidak ada foto
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="footer">
        <table width="100%">
            <tr>
                <td width="50%" align="center">
                    <p>Pegawai</p>
                    <br><br><br>
                    <p><strong><?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Guest'; ?></strong></p>
                </td>
            </tr>
        </table>
    </div>
    <script>
        window.print();
    </script>
</body>
</html>
