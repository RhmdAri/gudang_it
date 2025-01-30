<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();
include '../connection.php';

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Nama Pegawai Tidak Dikenal';
$divisi = isset($_SESSION['divisi']) ? $_SESSION['divisi'] : null;

// Inisialisasi variabel tambahan
$currentDate = date('d F Y'); // Tanggal saat ini
$level = isset($_SESSION['level']) ? $_SESSION['level'] : 'user'; // Default level
$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Nama Tidak Diketahui'; // Default nama

if (!$divisi) {
    echo "<script>
        alert('Akses ditolak! Divisi tidak ditemukan.');
        window.location.href = 'login.php';
    </script>";
    exit();
}
if (isset($_GET['export_excel'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->mergeCells('A1:B1');
    $sheet->setCellValue('A1', 'Laporan Inventaris');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->mergeCells('A2:B2');
    $sheet->setCellValue('A2', $currentDate);
    $sheet->getStyle('A2')->getFont()->setBold(true);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A3', 'Nama Barang')
          ->setCellValue('B3', 'Stok');

    $sheet->getStyle('A3:B3')->applyFromArray([
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

    $query = "SELECT * FROM inventaris WHERE divisi = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $divisi);
    $stmt->execute();
    $result = $stmt->get_result();

    $rowNum = 4;
    while ($data = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNum, $data['nama'])
              ->setCellValue('B' . $rowNum, $data['stok']);
        $rowNum++;
    }

    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ];
    $sheet->getStyle('A3:B' . ($rowNum - 1))->applyFromArray($styleArray);

    foreach (range('A', 'B') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $writer = new Xlsx($spreadsheet);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="laporan_inventaris.xlsx"');
    header('Cache-Control: max-age=0');
    header('Pragma: public');
    header('Expires: 0');

    ob_end_clean();
    $writer->save('php://output');
    exit();
}

// Cetak Data
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$bulanNama = date('F', mktime(0, 0, 0, $bulan, 10));
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Inventaris</title>
    <style>
        * { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; font-size: 12px; }
        table th, table td { text-align: center; padding: 5px; }
        thead { background-color: #28a745; color: white; }
        .kop-surat { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat img { width: 100px; }
        .kop-surat .title { text-align: center; flex: 1; margin-left: 10px; }
        .kop-surat .title h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .kop-surat .title p { margin: 0; font-size: 14px; }
        .table-container { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <img src="../assets/images/rsisa.png" alt="Logo RSI Sultan Agung Banjarbaru">
        <div class="title">
            <h1>RSI Sultan Agung Banjarbaru</h1>
            <p>Data Inventaris</p>
            <p><?= $bulanNama ?> <?= $tahun ?></p>
        </div>
    </div>

    <div class="table-container">
        <table border="1" width="100%" cellpadding="5">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT * FROM inventaris WHERE divisi = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("s", $divisi);
            $stmt->execute();
            $result = $stmt->get_result();
            $no = 1;

            while ($data = $result->fetch_assoc()) {
            ?>
            <tr>
                <td width="30" align="center"><?= $no++; ?></td> 
                <td><?= htmlspecialchars($data['nama']); ?></td>
                <td align="center"><?= htmlspecialchars($data['stok']); ?></td>
            </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>

    <br><br>
    <div class="footer">
        <table width="100%">
            <tr>
                <td align="right">
                    <p style="margin: 0; font-size: 1.2em; font-weight: bold;">Banjarbaru, <?= $currentDate ?></p> 
                    <?php if ($level === 'kepala') { ?>
                    <div class="barcode" style="margin: 10px 0;">
                        <img src="../assets/images/pengesahan.png" alt="Barcode" style="width: 100px;">
                    </div>
                    <?php } ?>
                    <p style="margin: 0; font-size: 1.2em; font-weight: bold;"><strong><?= $nama ?></strong></p> 
                </td>
            </tr>
        </table>
    </div>
    <script>
        window.print();
    </script>
</body>
</html>
