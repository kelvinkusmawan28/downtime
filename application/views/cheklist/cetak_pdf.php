<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .no-border td {
            border: none;
        }

        .header-table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>

    <!-- ================= HEADER ================= -->
    <table class="header-table">
        <tr>
            <td width="15%" class="text-center">
                <img src="<?= FCPATH . 'assets/img/avatars/new.png'; ?>" width="70"><br>
                <strong>PT. Indoneptune Net Mfg.</strong>
            </td>

            <td width="55%" class="text-center">
                <strong>PREVENTIVE MAINTENANCE CHECK LIST</strong>
            </td>

            <td width="30%">
                <table>
                    <tr>
                        <td>No. Dok</td>
                        <td>: FM-UT-02 U</td>
                    </tr>
                    <tr>
                        <td>Revisi</td>
                        <td>: 1</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>: 12-12-2013</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td><strong>No. Mesin :</strong> <?= $header['mach_no']; ?></td>
            <td>
                <strong>Bulan :</strong> <?= format_bulan_indonesia($bulan); ?>
            </td>
            <td>
                <strong>Tahun :</strong> <?= $tahun; ?>
            </td>
        </tr>
    </table>

    <br>

    <!-- ================= ISI ================= -->
    <table>
        <thead>
            <tr>
                <th width="180">ITEM PEMERIKSAAN</th>
                <th width="200">STANDAR</th>
                <th width="80">PERIODE</th>

                <?php for ($d = 1; $d <= 31; $d++) :
                    $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                ?>
                    <th><?= $day ?></th>
                <?php endfor; ?>
            </tr>
        </thead>

        <tbody>
            <!-- ================= HARIAN ================= -->
            <?php foreach ($items as $it) : ?>
                <tr>
                    <td><?= $it['nama_item']; ?></td>
                    <td><?= $it['standar']; ?></td>
                    <td class="text-center">HARIAN</td>

                    <?php for ($d = 1; $d <= 31; $d++) :
                        $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                    ?>
                        <td class="text-center">
                            <?= $matrix[$it['id']][$day] ?? '-' ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>

            <!-- ================= MINGGUAN ================= -->
            <?php foreach ($items_mingguan as $it) : ?>
                <tr>
                    <td><?= $it['nama_item']; ?></td>
                    <td><?= $it['standar']; ?></td>
                    <td class="text-center">MINGGUAN</td>

                    <?php for ($d = 1; $d <= 31; $d++) :
                        $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                    ?>
                        <td class="text-center">
                            <?= $matrix_mingguan[$it['id']][$day] ?? '-' ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>

            <!-- ================= BULANAN ================= -->
            <?php foreach ($items_bulanan as $it) : ?>
                <tr>
                    <td><?= $it['nama_item']; ?></td>
                    <td><?= $it['standar']; ?></td>
                    <td class="text-center">BULANAN</td>

                    <?php for ($d = 1; $d <= 31; $d++) :
                        $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                    ?>
                        <td class="text-center">
                            <?= $matrix_bulanan[$it['id']][$day] ?? '-' ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>

            <!-- ================= 3 BULAN ================= -->
            <?php foreach ($items_tigabulan as $it) : ?>
                <tr>
                    <td><?= $it['nama_item']; ?></td>
                    <td><?= $it['standar']; ?></td>
                    <td class="text-center">3 BULAN</td>

                    <?php for ($d = 1; $d <= 31; $d++) :
                        $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                    ?>
                        <td class="text-center">
                            <?= $matrix_tigabulan[$it['id']][$day] ?? '-' ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>

            <!-- ================= 6 BULAN ================= -->
            <?php foreach ($items_enambulan as $it) : ?>
                <tr>
                    <td><?= $it['nama_item']; ?></td>
                    <td><?= $it['standar']; ?></td>
                    <td class="text-center">6 BULAN</td>

                    <?php for ($d = 1; $d <= 31; $d++) :
                        $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                    ?>
                        <td class="text-center">
                            <?= $matrix_enambulan[$it['id']][$day] ?? '-' ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>

            <!-- ================= TAHUNAN ================= -->
            <?php foreach ($items_tahunan as $it) : ?>
                <tr>
                    <td><?= $it['nama_item']; ?></td>
                    <td><?= $it['standar']; ?></td>
                    <td class="text-center">TAHUNAN</td>

                    <?php for ($d = 1; $d <= 31; $d++) :
                        $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                    ?>
                        <td class="text-center">
                            <?= $matrix_tahunan[$it['id']][$day] ?? '-' ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>

    <!-- ================= FOOTER ================= -->
    <table class="no-border">
        <tr>
            <td width="50%">
                <strong>Jadwal Pemeriksaan:</strong><br>
                Harian : 06:00 - 12:00<br>
                Mingguan : Minggu ke 1 s/d 4<br>
                Bulanan : Minggu ke 1<br>
                3 Bulanan : Jan, Apr, Jul, Okt<br>
                6 Bulanan : Jan, Des<br>
                Tahunan : Des
            </td>

            <td width="25%">
                <strong>Keterangan:</strong><br>
                ✔ : OK<br>
                ❌ : Ada masalah<br>
                - : Mesin off / libur
            </td>

            <td width="25%">
                <strong>Catatan:</strong><br>
                Jika ada masalah segera ditindaklanjuti
            </td>
        </tr>
    </table>

</body>

</html>