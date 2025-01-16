<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_GET['export_excel'])) {
    $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
    $bulanArray = [
        '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
        '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
        '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
    ];
    $namaBulan = $bulanArray[$bulan];
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->mergeCells('A1:G1');
    $sheet->setCellValue('A1', 'Laporan Peminjaman ' . $namaBulan . ' ' . $tahun);
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A2:G2');
    $sheet->setCellValue('A2', $namaBulan . ' ' . $tahun);
    $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('A3', 'No')
          ->setCellValue('B3', 'Waktu')
          ->setCellValue('C3', 'Nama Peminjam')
          ->setCellValue('D3', 'Petugas')
          ->setCellValue('E3', 'Inventaris')
          ->setCellValue('F3', 'Tempat')
          ->setCellValue('G3', 'Keperluan');
    $sheet->getStyle('A3:G3')->applyFromArray([
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

    session_start();
    $divisi = isset($_SESSION['divisi']) ? $_SESSION['divisi'] : null;
    include '../connection.php';
    if (!$con) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }
    $result = mysqli_query($con, "
        SELECT pinjam.waktu, pinjam.nama, pinjam.tempat, pinjam.keperluan,
            GROUP_CONCAT(CONCAT(inventaris.nama, ' (', pinjam.jumlah, ')') SEPARATOR ', ') AS inventaris,
            petugas.nama AS namaPetugas
        FROM pinjam
        INNER JOIN petugas ON pinjam.idPetugas = petugas.id
        INNER JOIN inventaris ON inventaris.id = pinjam.idInventaris
        WHERE MONTH(pinjam.waktu) = '$bulan' AND YEAR(pinjam.waktu) = '$tahun'
        AND pinjam.devisi = '$divisi'  -- Filter berdasarkan divisi
        GROUP BY pinjam.nama, pinjam.tempat, pinjam.keperluan, petugas.nama
        ORDER BY pinjam.waktu DESC");

    $rowNum = 4;
    $no = 1;
    if ($result) {
        while ($data = mysqli_fetch_array($result)) {
            $waktuFormatted = date('d-m-Y H:i:s', strtotime($data['waktu']));
            $sheet->setCellValue('A' . $rowNum, $no++)
                  ->setCellValue('B' . $rowNum, $waktuFormatted)
                  ->setCellValue('C' . $rowNum, $data['nama'])
                  ->setCellValue('D' . $rowNum, $data['namaPetugas'])
                  ->setCellValue('E' . $rowNum, $data['inventaris'])
                  ->setCellValue('F' . $rowNum, $data['tempat'])
                  ->setCellValue('G' . $rowNum, $data['keperluan']);
            $rowNum++;
        }
    } else {
        echo "Query gagal: " . mysqli_error($con);
    }
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ];
    $sheet->getStyle('A3:G' . ($rowNum - 1))->applyFromArray($styleArray);

    foreach (range('A', 'G') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="laporan_peminjaman_' . $namaBulan . '_' . $tahun . '.xlsx"');
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
    <title>Cetak Data Peminjaman</title>
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
    ?>

    <div class="kop-surat">
        <img src="../assets/images/rsisa.png" alt="Logo RSI Sultan Agung Banjarbaru">
        <div class="title">
            <h1>RSI Sultan Agung Banjarbaru</h1>
            <p>Data Peminjaman</p>
            <p><?= $bulanNama ?> <?= $tahun ?></p>
        </div>
    </div>

    <div class="table-container">
        <table border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>Nama Peminjam</th>
                    <th>Petugas</th>
                    <th>Inventaris</th>
                    <th>Tempat</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $result = mysqli_query($con, "SELECT 
                                            pinjam.id, pinjam.waktu, pinjam.nama, pinjam.tempat, pinjam.keperluan, pinjam.status,
                                            GROUP_CONCAT(CONCAT(inventaris.nama, ' (', pinjam.jumlah, ')') SEPARATOR ', ') AS inventaris,
                                            petugas.nama AS namaPetugas
                                        FROM pinjam
                                        INNER JOIN petugas ON pinjam.idPetugas = petugas.id
                                        INNER JOIN inventaris ON inventaris.id = pinjam.idInventaris
                                        WHERE MONTH(pinjam.waktu) = '$bulan' AND YEAR(pinjam.waktu) = '$tahun'
                                        AND pinjam.devisi = '$divisi'
                                        GROUP BY pinjam.nama, pinjam.tempat, pinjam.keperluan, pinjam.status, petugas.nama
                                        ORDER BY MAX(pinjam.waktu) DESC");

            $no = 1;
            while ($data = mysqli_fetch_array($result)) {
                $waktuFormatted = date('d-m-Y H:i:s', strtotime($data['waktu']));
            ?>
                <tr>
                    <td width="30" align="center"><?= $no++ ?></td> 
                    <td><?= $waktuFormatted ?></td>
                    <td><?= htmlspecialchars($data['nama']) ?></td>
                    <td><?= htmlspecialchars($data['namaPetugas']) ?></td>
                    <td><?= htmlspecialchars($data['inventaris']) ?></td>
                    <td><?= htmlspecialchars($data['tempat']) ?></td>
                    <td><?= htmlspecialchars($data['keperluan']) ?></td>
                    <td><?= htmlspecialchars($data['status']) ?></td>
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
