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
    $sheet->mergeCells('A1:E1');
    $sheet->setCellValue('A1', 'Laporan Barang Masuk ' . $namaBulan . ' ' . $tahun);
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

    // Koneksi ke database dan query data
    include '../connection.php'; // Sesuaikan dengan path koneksi Anda

    // Cek koneksi database
    if (!$con) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Membuat query untuk mengambil data berdasarkan bulan dan tahun
    $result = mysqli_query($con, "
        SELECT masuk.waktu, petugas.nama AS namaPetugas, barang.nama AS namaBarang,
            masuk.jumlah
        FROM masuk
        INNER JOIN petugas ON masuk.idPetugas = petugas.id
        INNER JOIN barang ON barang.id = masuk.idBarang
        WHERE MONTH(masuk.waktu) = '$bulan' AND YEAR(masuk.waktu) = '$tahun'
        ORDER BY masuk.waktu DESC");

    // Menambahkan data ke Excel
    $rowNum = 4; // Dimulai dari baris 4 (karena baris 3 adalah header)
    $no = 1; // Nomor urut
    if ($result) {
        while ($data = mysqli_fetch_array($result)) {
            $waktuFormatted = date('d-m-Y H:i:s', strtotime($data['waktu']));
            $sheet->setCellValue('A' . $rowNum, $no++)
                  ->setCellValue('B' . $rowNum, $waktuFormatted)
                  ->setCellValue('C' . $rowNum, $data['namaPetugas'])
                  ->setCellValue('D' . $rowNum, $data['namaBarang'])
                  ->setCellValue('E' . $rowNum, $data['jumlah']);
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
    $sheet->getStyle('A3:E' . ($rowNum - 1))->applyFromArray($styleArray);

    // Menyesuaikan lebar kolom sesuai dengan konten
    foreach (range('A', 'E') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Membuat file Excel dan mengirimnya ke browser
    $writer = new Xlsx($spreadsheet);

    // Menyusun header agar file bisa didownload dengan benar
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="laporan_barang_masuk_' . $namaBulan . '_' . $tahun . '.xlsx"');
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

<h1 align="center">Laporan Barang Masuk</h1>
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
            masuk.id, masuk.waktu, masuk.jumlah,
            petugas.nama as namaPetugas,
            barang.nama as namaBarang
            FROM masuk
            INNER JOIN petugas ON masuk.idPetugas = petugas.id
            INNER JOIN barang ON barang.id = masuk.idBarang";

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
