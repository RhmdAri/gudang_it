<?php
require '../vendor/autoload.php'; // Include Composer autoload file

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

include('../connection.php');

// Ambil bulan dan tahun dari parameter GET
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

// Membuat objek spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Menambahkan informasi bulan dan tahun di atas tabel
$sheet->mergeCells('A1:E1');
$sheet->setCellValue('A1', 'Laporan Barang Keluar');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Menambahkan bulan dan tahun yang dipilih dalam format "November 2024"
$sheet->mergeCells('A2:E2');
$sheet->setCellValue('A2', $namaBulan . ' ' . $tahun);
$sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Menambahkan header kolom (termasuk kolom No)
$sheet->setCellValue('A3', 'No')
      ->setCellValue('B3', 'Waktu')
      ->setCellValue('C3', 'Nama Petugas')
      ->setCellValue('D3', 'Nama Barang')
      ->setCellValue('E3', 'Jumlah');

// Memberikan style pada header kolom
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

// Query untuk mengambil data barang keluar berdasarkan bulan dan tahun
$query = "
    SELECT keluar.waktu, petugas.nama AS namaPetugas, barang.nama AS namaBarang, keluar.jumlah
    FROM keluar
    INNER JOIN petugas ON keluar.idPetugas = petugas.id
    INNER JOIN barang ON keluar.idBarang = barang.id
    WHERE MONTH(keluar.waktu) = '$bulan' AND YEAR(keluar.waktu) = '$tahun'
    ORDER BY keluar.waktu DESC";
$result = mysqli_query($con, $query);

// Menambahkan data ke dalam sheet, termasuk nomor urut
$rowNumber = 4; // Mulai dari baris keempat setelah header
$no = 1; // Nomor urut
while ($row = mysqli_fetch_assoc($result)) {
    $waktuFormatted = date('d-m-Y H:i:s', strtotime($row['waktu']));
    $sheet->setCellValue('A' . $rowNumber, $no++);
    $sheet->setCellValue('B' . $rowNumber, $waktuFormatted);
    $sheet->setCellValue('C' . $rowNumber, $row['namaPetugas']);
    $sheet->setCellValue('D' . $rowNumber, $row['namaBarang']);
    $sheet->setCellValue('E' . $rowNumber, $row['jumlah']);
    $rowNumber++;
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
$sheet->getStyle('A3:E' . ($rowNumber - 1))->applyFromArray($styleArray);

// Menyesuaikan lebar kolom sesuai dengan konten
foreach (range('A', 'E') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Set headers untuk output file Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Laporan_Barang_Keluar_' . $namaBulan . '_' . $tahun . '.xls"');
header('Cache-Control: max-age=0');

// Menulis file Excel ke output
$writer = new Xls($spreadsheet);
$writer->save('php://output');
?>



<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cetak Data Petugas</title>
<style>
    * {
        font-family: Arial, sans-serif;
    }

    table {
        border-collapse: collapse;
        font-size: 12px;
    }
</style>

<h1 align="center">Laporan Barang Keluar</h1>
<p align="center"><?= date("Y/m/d") ?></p>

<table border="1" width="100%" cellpadding="5">
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
        include '../connection.php';
        $query = "SELECT 
                    keluar.id, keluar.waktu, keluar.jumlah,
                    petugas.nama AS namaPetugas,
                    barang.nama AS namaBarang
                  FROM keluar
                  INNER JOIN petugas ON keluar.idPetugas = petugas.id
                  INNER JOIN barang ON barang.id = keluar.idBarang";

        $result = mysqli_query($con, $query);
        $no = 1;

        while ($data = mysqli_fetch_array($result)) {
            $waktuFormatted = date('d-m-Y H:i:s', strtotime($data['waktu'])); // Format waktu
        ?>
            <tr>
                <td width="30" align="center"><?= $no++ ?></td>
                <td><?= $waktuFormatted ?></td>
                <td><?= htmlspecialchars($data['namaPetugas']) ?></td>
                <td><?= htmlspecialchars($data['namaBarang']) ?></td>
                <td><?= htmlspecialchars($data['jumlah']) ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<br><br>
<table width="100%">
    <tr>
        <td width="50%" align="center">
            <p>Pegawai</p>
            <br><br><br>
            <p><strong>Nama Pegawai</strong></p>
        </td>
    </tr>
</table>

<script>
    window.print();
</script>
