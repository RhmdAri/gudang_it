<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_GET['export_excel'])) {

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->mergeCells('A1:B1');
    $sheet->setCellValue('A1', 'Laporan Inventaris');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $currentDate = date('d F Y');
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

    include '../connection.php';

    if (!$con) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    $result = mysqli_query($con, "SELECT * FROM inventaris");

    $rowNum = 4;
    if ($result) {
        while ($data = mysqli_fetch_array($result)) {
            $sheet->setCellValue('A' . $rowNum, $data['nama'])
                  ->setCellValue('B' . $rowNum, $data['stok']);
            $rowNum++;
        }
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Petugas</title>
    <style>
        * {
            font-family: arial;
        }
        
        table {
            border-collapse: collapse;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1 align="center">Laporan Data Petugas</h1>
    <p align="center"><?= date("Y/m/d") ?></p>
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
        include '../connection.php';
        $result = mysqli_query($con, "SELECT * FROM inventaris");

        $no = 1;
        while ($data = mysqli_fetch_array($result)) {
        ?>
        <tr>
            <td width="30" align="center"><?= $no++ ?></td> 
            <td><?php echo $data['nama']; ?></td>
            <td><?php echo $data['stok']; ?></td>
        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    <br><br>
    <table width="100%">
        <tbody>
        <tr>
            <td width="50%" align="center">
                <p>Pegawai</p>
                <br><br><br>
                <p><strong>Nama Pegawai</strong></p>
            </td>
        </tr>
        </tbody>
    </table>
    
    <script>
        window.print();
    </script>
</body>
</html>
