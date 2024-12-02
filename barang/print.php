<?php
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include '../connection.php';

if (!$con) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_GET['export_excel'])) {
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
            width: 100%;
            font-size: 12px;
        }

        table th, table td {
            padding: 5px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 50px;
        }

        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <h1 align="center">Laporan Data Barang</h1>
    <p align="center"><?= date("Y/m/d") ?></p>

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
        include '../connection.php';
        $result = mysqli_query($con, "SELECT 
                                        barang.id, barang.kode, barang.nama, barang.stok,
                                        kategori.nama as namaKategori
                                        FROM barang
                                        INNER JOIN kategori ON barang.idKategori = kategori.id");

        $no = 1;
        while ($data = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <td align="center"><?= $no++ ?></td> 
                <td><?= $data['kode'] ?></td>
                <td><?= $data['nama'] ?></td>
                <td><?= $data['namaKategori'] ?></td>
                <td><?= $data['stok'] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div class="footer">
        <table width="100%">
            <tr>
                <td width="50%" align="center">
                    <p>Pegawai</p>
                    <br><br><br>
                    <p><strong>Nama Pegawai</strong></p>
                </td>
            </tr>
        </table>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
