<?php
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include '../connection.php';

if (!$con) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

session_start(); // Mulai sesi
$divisi = isset($_SESSION['divisi']) ? $_SESSION['divisi'] : null; // Ambil divisi dari sesi

if (isset($_GET['export_excel']) && $divisi) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->mergeCells('A1:D1');
    $sheet->setCellValue('A1', 'Laporan Barang');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->mergeCells('A2:D2');
    $sheet->setCellValue('A2', 'Barang Tersedia');
    $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A3', 'Kode Barang')
          ->setCellValue('B3', 'Nama Barang')
          ->setCellValue('C3', 'Kategori')
          ->setCellValue('D3', 'Stok');

    $sheet->getStyle('A3:D3')->applyFromArray([
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
    $result = mysqli_query($con, "
        SELECT 
            barang.kode, barang.nama, kategori.nama AS namaKategori, barang.stok
        FROM barang
        INNER JOIN kategori ON barang.idKategori = kategori.id
        WHERE barang.devisi = '$divisi'
        ORDER BY kategori.nama ASC
    ");

    $rowNum = 4;
    while ($data = mysqli_fetch_array($result)) {
        $sheet->setCellValue('A' . $rowNum, $data['kode'])
              ->setCellValue('B' . $rowNum, $data['nama'])
              ->setCellValue('C' . $rowNum, $data['namaKategori'])
              ->setCellValue('D' . $rowNum, $data['stok']);
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
    $sheet->getStyle('A3:D' . ($rowNum - 1))->applyFromArray($styleArray);

    foreach (range('A', 'D') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="laporan_barang.xlsx"');
    header('Cache-Control: max-age=0');
    ob_end_clean();
    $writer->save('php://output');
    exit();
}
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$bulanNama = date('F', mktime(0, 0, 0, $bulan, 10)); // Menampilkan nama bulan
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Barang</title>
    <style>
        * {
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            font-size: 12px;
            width: 100%;
        }
        table th, table td {
            padding: 5px;
            text-align: center;
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
        thead {
            background-color: #28a745;
            color: white;
        }
        .table-container {
            margin-top: 20px;
        }
        .footer {
            margin-top: 50px;
        }
        .footer p {
            margin: 0;
        }
        .barcode {
            text-align: center;
            margin: 10px 0;
        }
        .barcode img {
            width: 150px;
        }
    </style>
</head>
<body>
    <?php
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Nama Pegawai Tidak Dikenal';
    $nama = isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Guest';
    $level = isset($_SESSION['level']) ? $_SESSION['level'] : 'user';
    $currentDate = date("d F Y");
    ?>

    <div class="kop-surat">
        <img src="../assets/images/rsisa.png" alt="Logo RSI Sultan Agung Banjarbaru">
        <div class="title">
            <h1>RSI Sultan Agung Banjarbaru</h1>
            <p>Data Barang</p>
            <p><?= $bulanNama ?> <?= $tahun ?></p>
        </div>
    </div>
    <div class="table-container">
        <table border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $result = mysqli_query($con, "SELECT 
                                            barang.kode, barang.nama, barang.stok,
                                            kategori.nama as namaKategori
                                            FROM barang
                                            INNER JOIN kategori ON barang.idKategori = kategori.id
                                            WHERE barang.devisi = '$divisi'
            ");
            $no = 1;
            while ($data = mysqli_fetch_array($result)) {
            ?>
                <tr>
                    <td><?= $no++ ?></td> 
                    <td><?= htmlspecialchars($data['kode']) ?></td>
                    <td><?= htmlspecialchars($data['nama']) ?></td>
                    <td><?= htmlspecialchars($data['namaKategori']) ?></td>
                    <td><?= htmlspecialchars($data['stok']) ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
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
