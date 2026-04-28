<style>
    .m-table thead th {
        color: transparent;
        /* teks hilang */

        /* garis tetap */
    }
</style>


<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <!-- HEADER -->
            <table class="table table-bordered h-header-table">
                <tr>
                    <td style="width:15%; text-align:center;">
                        <img src="<?= base_url(); ?>/assets/img/avatars/new.png" style="width:100px;"><br>
                        <strong>PT. Indoneptune Net Mfg.</strong>
                    </td>

                    <td style="width:50%; text-align:center;">
                        <h6 style="margin:5px 0;"><strong>PREVENTIVE MAINTENANCE CHECK LIST</strong></h6>
                    </td>

                    <td style="width:20%;">
                        <table class="table table-bordered table-sm" style="font-size:11px; margin-bottom:0;">
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

                <tr style="font-size: 11px;">
                    <td><strong>NO. MESIN : <?= $header['mach_no']; ?></strong></td>
                    <td>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>NAMA MESIN : - </strong>
                            </div>
                            <div class="col-md-6">
                                <strong> BULAN :<?= format_bulan_indonesia($bulan) ?></strong>
                            </div>
                        </div>

                    </td>
                    <td><strong>TAHUN : <?= $tahun; ?></strong></td>
                </tr>

            </table>
            <!-- ISI -->
            <table class="table table-bordered h-table">
                <thead>
                    <tr>
                        <th style="width:180px; font-size:10px;">ITEM PEMERIKSAAN</th>
                        <th style="width:250px; font-size:10px;">STANDAR</th>
                        <th style="width:250px; font-size:10px;">PERIODE</th>

                        <?php for ($d = 1; $d <= 31; $d++) :
                            $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                        ?>
                            <th style="font-size:10px;"><?= $day ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($items as $it) : ?>
                        <tr style="font-size: 10px;">

                            <td><?= $it['nama_item'] ?></td>
                            <td><?= $it['standar'] ?></td>
                            <td>HARIAN</td>

                            <?php for ($d = 1; $d <= 31; $d++) :
                                $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                            ?>
                                <td class="text-center">
                                    <?= $matrix[$it['id']][$day] ?? '-' ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>

                    <?php foreach ($items_mingguan as $it) : ?>
                        <tr style="font-size:10px;">
                            <td><?= $it['nama_item'] ?></td>
                            <td><?= $it['standar'] ?></td>
                            <td>MINGUAN</td>
                            <?php for ($d = 1; $d <= 31; $d++) : ?>
                                <td class="text-center">
                                    <?= $matrix_mingguan[$it['id']][$day = str_pad($d, 2, '0', STR_PAD_LEFT)] ?? '-' ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>

                    <?php foreach ($items_bulanan as $it) : ?>
                        <tr style="font-size:10px;">
                            <td><?= $it['nama_item'] ?></td>
                            <td><?= $it['standar'] ?></td>
                            <td>BULANAN</td>
                            <?php for ($d = 1; $d <= 31; $d++) : ?>
                                <td class="text-center">
                                    <?= $matrix_bulanan[$it['id']][$day = str_pad($d, 2, '0', STR_PAD_LEFT)] ?? '-' ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                    <?php foreach ($items_tigabulan as $it) : ?>
                        <tr style="font-size:10px;">
                            <td><?= $it['nama_item'] ?></td>
                            <td><?= $it['standar'] ?></td>
                            <td>3 BULAN</td>
                            <?php for ($d = 1; $d <= 31; $d++) : ?>
                                <td class="text-center">
                                    <?= $matrix_tigabulan[$it['id']][$day = str_pad($d, 2, '0', STR_PAD_LEFT)] ?? '-' ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                    <?php foreach ($items_enambulan as $it) : ?>
                        <tr style="font-size:10px;">
                            <td><?= $it['nama_item'] ?></td>
                            <td><?= $it['standar'] ?></td>
                            <td>6 BULAN</td>
                            <?php for ($d = 1; $d <= 31; $d++) : ?>
                                <td class="text-center">
                                    <?= $matrix_enambulan[$it['id']][$day = str_pad($d, 2, '0', STR_PAD_LEFT)] ?? '-' ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                    <?php foreach ($items_tahunan as $it) : ?>
                        <tr style="font-size:10px;">
                            <td><?= $it['nama_item'] ?></td>
                            <td><?= $it['standar'] ?></td>
                            <td>TAHUNAN</td>
                            <?php for ($d = 1; $d <= 31; $d++) : ?>
                                <td class="text-center">
                                    <?= $matrix_tahunan[$it['id']][$day = str_pad($d, 2, '0', STR_PAD_LEFT)] ?? '-' ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- FOOTER -->
        <div class="row" style="border: 1px solid grey; font-size:11px;">
            <div class="col-md-6" style="border-right: 1px solid grey;">
                <span style="border-bottom: 1px solid grey;">JADWAL PEMERIKSAAN : </span>
                <div class="row">
                    <div class="col-md-6">
                        <span>Harian : 06:00 - 12:00</span> <br>
                        <span>Minguuan : tgl 1-7 (minggu ke-1) tgl 8-15 (minggu ke-2) dsty</span> <br>
                        <span>Bulanan : Minggu ke 1</span>
                    </div>
                    <div class="col-md-6">
                        <span> 3 Bulanan : Jan,Apr,Jul,Okt</span><br>
                        <span>6 Bulanan : Jan, Des</span> <br>
                        <span>1 Tahun : Des</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3" style="border-right: 1px solid grey;">
                <span style="border-bottom: 1px solid grey;">Cara Pengerjaan</span><br>
                <span>✔ : OK / tidak ada masalah</span> <br>
                <span>X : Ada masalah</span> <br>
                <span>- : Mesin off/Libur</span>
            </div>
            <div class="col-md-3">
                <span>JIKA ADA MASALAH SEGERA
                    DITINDAKLANJUTI & DICATAT PADA
                    KARTU MESIN
                </span>
            </div>
        </div>
    </div>
</div>