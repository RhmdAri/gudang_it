<?php
// Include autoloader PhpSpreadsheet
require '../vendor/autoload.php'; // Sesuaikan path jika Anda menggunakan Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Mengecek jika ada permintaan ekspor ke Excel
if (isset($_GET['export_excel'])) {
    // Ambil bulan dan tahun dari URL atau POST
    $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

    // Array bulan dalam format teks
    $bulanArray = [
        '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
        '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
        '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
    ];

    // Mendapatkan nama bulan sesuai pilihan
    $namaBulan = $bulanArray[$bulan];

    // Membuat objek Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menambahkan informasi bulan dan tahun di atas tabel
    $sheet->mergeCells('A1:G1');
    $sheet->setCellValue('A1', 'Laporan Peminjaman ' . $namaBulan . ' ' . $tahun);
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Menambahkan bulan dan tahun yang dipilih dalam format "November 2024"
    $sheet->mergeCells('A2:G2');
    $sheet->setCellValue('A2', $namaBulan . ' ' . $tahun);
    $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Menambahkan header kolom (termasuk kolom No)
    $sheet->setCellValue('A3', 'No')
          ->setCellValue('B3', 'Waktu')
          ->setCellValue('C3', 'Nama Peminjam')
          ->setCellValue('D3', 'Petugas')
          ->setCellValue('E3', 'Inventaris')
          ->setCellValue('F3', 'Tempat')
          ->setCellValue('G3', 'Keperluan');

    // Memberikan style pada header kolom
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

    // Koneksi ke database dan query data
    include '../connection.php'; // Sesuaikan dengan path koneksi Anda

    // Cek koneksi database
    if (!$con) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Membuat query untuk mengambil data berdasarkan bulan dan tahun
    $result = mysqli_query($con, "
        SELECT pinjam.waktu, pinjam.nama, pinjam.tempat, pinjam.keperluan,
            GROUP_CONCAT(CONCAT(inventaris.nama, ' (', pinjam.jumlah, ')') SEPARATOR ', ') AS inventaris,
            petugas.nama AS namaPetugas
        FROM pinjam
        INNER JOIN petugas ON pinjam.idPetugas = petugas.id
        INNER JOIN inventaris ON inventaris.id = pinjam.idInventaris
        WHERE MONTH(pinjam.waktu) = '$bulan' AND YEAR(pinjam.waktu) = '$tahun'
        GROUP BY pinjam.nama, pinjam.tempat, pinjam.keperluan, petugas.nama
        ORDER BY pinjam.waktu DESC");

    // Menambahkan data ke Excel
    $rowNum = 4; // Dimulai dari baris 4 (karena baris 3 adalah header)
    $no = 1; // Nomor urut
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

    // Menambahkan border pada tabel
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ];
    $sheet->getStyle('A3:G' . ($rowNum - 1))->applyFromArray($styleArray);

    // Menyesuaikan lebar kolom sesuai dengan konten
    foreach (range('A', 'G') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Membuat file Excel dan mengirimnya ke browser
    $writer = new Xlsx($spreadsheet);

    // Menyusun header agar file bisa didownload dengan benar
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="laporan_peminjaman_' . $namaBulan . '_' . $tahun . '.xlsx"');
    header('Cache-Control: max-age=0');
    header('Pragma: public'); // Untuk kompatibilitas browser
    header('Expires: 0');

    // Pastikan tidak ada output lain sebelum pengiriman file
    ob_end_clean(); // Bersihkan buffer output jika ada

    // Mengirim file ke output
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
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
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
    <h1 align="center">Laporan Data Peminjaman</h1>
    
    <?php
    include '../connection.php';

    // Ambil bulan dan tahun dari URL
    $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

    // Menampilkan bulan dan tahun yang dipilih
    $bulanNama = date('F', mktime(0, 0, 0, $bulan, 10)); // Menampilkan nama bulan
    ?>

    <p align="center"><?= $bulanNama ?> <?= $tahun ?></p>

    <table cellpadding="5">
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
        // Query untuk mengambil data berdasarkan bulan dan tahun
        $result = mysqli_query($con, "SELECT 
                                            pinjam.id, pinjam.waktu, pinjam.nama, pinjam.tempat, pinjam.keperluan, pinjam.status,
                                            GROUP_CONCAT(CONCAT(inventaris.nama, ' (', pinjam.jumlah, ')') SEPARATOR ', ') AS inventaris,
                                            petugas.nama AS namaPetugas
                                        FROM pinjam
                                        INNER JOIN petugas ON pinjam.idPetugas = petugas.id
                                        INNER JOIN inventaris ON inventaris.id = pinjam.idInventaris
                                        WHERE MONTH(pinjam.waktu) = '$bulan' AND YEAR(pinjam.waktu) = '$tahun'
                                        GROUP BY pinjam.nama, pinjam.tempat, pinjam.keperluan, pinjam.status, petugas.nama
                                        ORDER BY MAX(pinjam.waktu) DESC");

        $no = 1;
        while ($data = mysqli_fetch_array($result)) {
            $waktuFormatted = date('d-m-Y H:i:s', strtotime($data['waktu'])); // Format waktu
        ?>
            <tr>   
                <td align="center"><?= $no++ ?></td> 
                <td><?= $waktuFormatted ?></td>
                <td><?= htmlspecialchars($data['nama']) ?></td>
                <td><?= htmlspecialchars($data['namaPetugas']) ?></td>
                <td><?= htmlspecialchars($data['inventaris']) ?></td>
                <td><?= htmlspecialchars($data['tempat']) ?></td>
                <td><?= htmlspecialchars($data['keperluan']) ?></td>
                <td><?= htmlspecialchars($data['status']) ?></td>
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
