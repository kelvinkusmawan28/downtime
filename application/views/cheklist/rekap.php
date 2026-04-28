<style>
    .h-header-table,
    .h-table {
        min-width: 2000px;
    }

    .m-header-table,
    .m-table {
        min-width: 1200px;
    }

    .b-header-table,
    .b-table {
        min-width: 1200px;
    }

    .tigab-header-table,
    .tigab-table {
        min-width: 1200px;
    }

    .enamb-header-table,
    .enamb-table {
        min-width: 1000px;
    }

    .th-header-table,
    .th-table {
        min-width: 1000px;
    }
</style>
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row ">
        <div class="col-lg-12 ">

            <div class="card h-100">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="mb-1 me-2"><?= $title; ?></h5>
                        </div>
                        <div class="col-lg-6" style="text-align: right;">
                            <a href="<?= base_url('cheklist'); ?>" class="btn btn-sm btn-warning"> <i class="fa fa-arrow-left me-2"></i> Kembali</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body row g-0 align-items-center">

                                        <!-- Logo -->
                                        <div class="col-sm-2 text-center">
                                            <img src="<?= base_url(); ?>/assets/img/avatars/new.png" style="width: 110px;">
                                        </div>

                                        <!-- Info Mesin -->
                                        <div class="col-sm-3">
                                            <p class="mb-1 text-dark"><b>Mesin :</b> <?= $header['mach_no']; ?></p>
                                            <p class="mb-1 text-dark"><b>Spek :</b> <?= $header['mach_name']; ?></p>
                                            <p class="mb-0 text-dark"><b>Departemen :</b> <?= $header['departemen']; ?></p>
                                        </div>

                                        <!-- Progress Checklist -->
                                        <div class="col-sm-7 d-flex flex-column gap-2 ps-sm-4">

                                            <?php foreach ($rekap as $r) :
                                                $warna = 'bg-danger';
                                                if ($r['persen'] >= 100) $warna = 'bg-success';
                                                elseif ($r['persen'] >= 70) $warna = 'bg-primary';
                                                elseif ($r['persen'] >= 40) $warna = 'bg-warning';

                                                $badge = 'Waiting ..';
                                                if ($r['persen'] >= 100) $badge = 'Selesai';
                                                elseif ($r['persen'] >= 40) $badge = 'Proses';
                                            ?>

                                                <div class="d-flex align-items-center gap-3">
                                                    <small class="fw-bold w-25"><?= $r['kategori']; ?></small>

                                                    <div class="progress w-100" style="height:8px;">
                                                        <div class="progress-bar <?= $warna; ?>" role="progressbar" style="width: <?= $r['persen']; ?>%" aria-valuenow="<?= $r['persen']; ?>" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>

                                                    <small class="text-muted w-25 text-end">
                                                        <?= $r['terisi']; ?>/<?= $r['target']; ?>
                                                        (<?= $r['persen']; ?>%)
                                                    </small>

                                                    <span class="badge 
                                                                          <?= $badge == 'Selesai' ? 'bg-success' : ($badge == 'Proses' ? 'bg-warning' : 'bg-danger'); ?>">
                                                        <?= $badge; ?>
                                                    </span>
                                                </div>

                                            <?php endforeach; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="card-body font-kecil ">

                        <div class="row">

                            <div class="row mb-3 justify-content-end">

                                <div class="col-md-2">
                                    <label class="font-kecil font-bold text-azure text-danger">Bulan</label>
                                    <select name="filter_bulan" id="filter_bulan" class="form-select font-kecil mt-0">
                                        <?php foreach ($bulan_options as $bl) : ?>
                                            <?php if (!empty($bl['bulan']) && !empty($bl['nama_bulan'])) : ?>
                                                <option value="<?= $bl['bulan']; ?>" <?= ($bl['bulan'] == $bulan) ? 'selected' : '' ?>>
                                                    <?= $bl['nama_bulan']; ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-auto mt-4"> <a href="<?= base_url('cheklist/pdf/' . $header['mach_id'] . '/' . $bulan . '/' . $tahun) ?>" class="btn btn-danger btn-sm" target="_blank">
                                        <i class="fa fa-file-pdf me-2"></i><span class="ml-1" style="color: aliceblue;">Export To PDF</span>
                                    </a>
                                </div>
                                <!-- <div class="col-md-auto mt-4"> <a href="<?= base_url('cheklist/cetak/' . $header['mach_id'] . '/' . $bulan . '/' . $tahun) ?>" class="btn btn-danger btn-sm" target="_blank">
                                        <span class="ml-1" style="color: aliceblue;">Cetak</span>
                                    </a>
                                </div> -->
                            </div>


                            <hr>
                            <div class="col-xl-12 mt-3">
                                <div class="nav-align-top nav-tabs-shadow font-kecil">
                                    <ul class="nav nav-tabs nav-fill font-kecil" role="tablist">
                                        <li class="nav-item">
                                            <button type="button" class="nav-link active font-kecil" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="true">
                                                <span class="d-none d-sm-inline-flex align-items-center">
                                                    <i class=" icon-base bx bx-home icon-sm me-1_5"></i>HARIAN
                                                </span>
                                                <i class="icon-base bx bx-home icon-sm d-sm-none"></i>
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link font-kecil" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false">
                                                <span class="d-none d-sm-inline-flex align-items-center">MINGGUAN</span>
                                                <i class="icon-base bx bx-user icon-sm d-sm-none"></i>
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link font-kecil" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-messages" aria-controls="navs-justified-messages" aria-selected="false">
                                                <span class="d-none d-sm-inline-flex align-items-center">BULANAN</span>
                                                <i class="icon-base bx bx-message-square icon-sm d-sm-none"></i>
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link font-kecil" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-3bulan" aria-controls="navs-justified-messages" aria-selected="false">
                                                <span class="d-none d-sm-inline-flex align-items-center">PER 3 BULAN</span>
                                                <i class="icon-base bx bx-message-square icon-sm d-sm-none"></i>
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link font-kecil" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-6bulan" aria-controls="navs-justified-messages" aria-selected="false">
                                                <span class="d-none d-sm-inline-flex align-items-center">PER 6 BULAN</span>
                                                <i class="icon-base bx bx-message-square icon-sm d-sm-none"></i>
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link font-kecil" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-tahunan" aria-controls="navs-justified-messages" aria-selected="false">
                                                <span class="d-none d-sm-inline-flex align-items-center">TAHUNAN</span>
                                                <i class="icon-base bx bx-message-square icon-sm d-sm-none"></i>
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive">
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
                                                                            <strong>PERIODE : HARIAN</strong>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong> BULAN :<?= format_bulan_indonesia($bulan) ?></strong>
                                                                        </div>
                                                                    </div>

                                                                </td>
                                                                <td><strong>TAHUN : <?= $tahun; ?></strong></td>
                                                            </tr>

                                                        </table>


                                                        <table class="table table-bordered h-table">
                                                            <thead>
                                                                <tr>
                                                                    <th style="font-size:10px;">ITEM PEMERIKSAAN</th>
                                                                    <th style="font-size:10px;">STANDAR</th>
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

                                                                        <?php for ($d = 1; $d <= 31; $d++) :
                                                                            $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                                                                        ?>
                                                                            <td class="text-center">
                                                                                <?= $matrix[$it['id']][$day] ?? '-' ?>
                                                                            </td>
                                                                        <?php endfor; ?>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>



                                                    </div>
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

                                        </div>
                                        <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
                                            <h5>Rekap Mingguan</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered m-header-table">
                                                    <tr>
                                                        <td style="width:20%; text-align:center; ">
                                                            <img src=" <?= base_url(); ?>/assets/img/avatars/new.png" style="width:100px;"><br>
                                                            <strong>PT. Indoneptune Net Mfg.</strong>
                                                        </td>

                                                        <td style="width:55%; text-align:center;">
                                                            <h6 style="margin:5px 0;"><strong>PREVENTIVE MAINTENANCE CHECK LIST</strong></h6>
                                                        </td>

                                                        <td style="width:25%;">
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
                                                        <td><strong>NO. MESIN :<?= $header['mach_no']; ?> </strong> </td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <strong>PERIODE : MINGGUAN</strong>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong> BULAN :<?= format_bulan_indonesia($bulan) ?></strong>
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td><strong>TAHUN : <?= $tahun; ?></strong></td>
                                                    </tr>
                                                </table>
                                                <table class="table table-bordered m-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size:10px;">Item Pemeriksaan</th>
                                                            <th style="font-size:10px;">standar</th>
                                                            <?php foreach ($headerTanggal as $tgl) : ?>
                                                                <th style="text-align: center; font-size:10px;"><?= $tgl ?></th>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($items_mingguan as $key) : ?>
                                                            <tr>
                                                                <td style="font-size:10px;"><?= $key['nama_item'] ?></td>
                                                                <td style="font-size:10px;"><?= $key['standar'] ?></td>
                                                                <?php foreach ($headerTanggal as $tgl) : ?>
                                                                    <td class="text-center" style="font-size:10px;">
                                                                        <?= $matrix_mingguan[$key['id']][$tgl] ?? '-' ?>
                                                                    </td>
                                                                <?php endforeach; ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
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
                                        <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
                                            <h5>Rekap Bulanan</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered b-header-table">
                                                    <tr>
                                                        <td style="width:20%; text-align:center;">
                                                            <img src="<?= base_url(); ?>/assets/img/avatars/new.png" style="width:100px;"><br>
                                                            <strong>PT. Indoneptune Net Mfg.</strong>
                                                        </td>

                                                        <td style="width:55%; text-align:center;">
                                                            <h6 style="margin:5px 0;"><strong>PREVENTIVE MAINTENANCE CHECK LIST</strong></h6>
                                                        </td>

                                                        <td style="width:25%;">
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
                                                                    <strong>PERIODE : BULANAN</strong>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong> BULAN :<?= format_bulan_indonesia($bulan) ?></strong>
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td><strong>TAHUN : <?= $tahun; ?></strong></td>
                                                    </tr>
                                                </table>
                                                <table class="table table-bordered b-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: 10px;">Item Pemeriksaan</th>
                                                            <th style="font-size: 10px;">Standar</th>
                                                            <?php foreach ($headerBulanan as $bulan) : ?>
                                                                <th style="text-align: center; font-size:10px"><?= date('F', mktime(0, 0, 0, $bulan, 1)) ?></th>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($items_bulanan as $bulanan) : ?>
                                                            <tr>
                                                                <td style="font-size: 10px;"><?= $bulanan['nama_item'] ?></td>
                                                                <td style="font-size: 10px;"><?= $bulanan['standar'] ?></td>
                                                                <?php foreach ($headerBulanan as $bulan) : ?>
                                                                    <td class="text-center" style="font-size: 10px;">
                                                                        <?= $matrix_bulanan[$bulanan['id']][$bulan] ?? '-' ?>
                                                                    </td>
                                                                <?php endforeach; ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row" style="border: 1px solid grey; font-size:11px">
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
                                        <div class="tab-pane fade" id="navs-justified-3bulan" role="tabpanel">
                                            <h5>Rekap 3 Bulanan</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered tigab-header-table">
                                                    <tr>
                                                        <td style="width:20%; text-align:center;">
                                                            <img src="<?= base_url(); ?>/assets/img/avatars/new.png" style="width:100px;"><br>
                                                            <strong>PT. Indoneptune Net Mfg.</strong>
                                                        </td>

                                                        <td style="width:55%; text-align:center;">
                                                            <h6 style="margin:5px 0;"><strong>PREVENTIVE MAINTENANCE CHECK LIST</strong></h6>
                                                        </td>

                                                        <td style="width:25%;">
                                                            <table class="table table-bordered" style="font-size:11px; margin-bottom:0;">
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
                                                        <td><strong>NO. MESIN :<?= $header['mach_no']; ?> </strong> </td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <strong>PERIODE : 3 BULANAN</strong>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong> BULAN :<?= format_bulan_indonesia($bulan) ?></strong>
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td><strong>TAHUN :<?= $tahun; ?> </strong> </td>
                                                    </tr>
                                                </table>
                                                <table class="table table-bordered tigab-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: 10px;">Item Pemeriksaan</th>
                                                            <th style="font-size: 10px;">Standar</th>
                                                            <?php foreach ($header_tigabulan as $tigabulan) : ?>
                                                                <th style="text-align: center; font-size:10px;"><?= date('F', mktime(0, 0, 0, $tigabulan, 1)) ?></th>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($items_tigabulan as $tiga_bulan) : ?>
                                                            <tr>
                                                                <td style="font-size: 10px;"><?= $tiga_bulan['nama_item'] ?></td>
                                                                <td style="font-size: 10px;"><?= $tiga_bulan['standar'] ?></td>
                                                                <?php foreach ($header_tigabulan as $bulan) : ?>
                                                                    <td class="text-center" style="font-size: 10px;">
                                                                        <?= $matrix_tigabulan[$tiga_bulan['id']][$bulan] ?? '-' ?>
                                                                    </td>
                                                                <?php endforeach; ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
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
                                        <div class="tab-pane fade" id="navs-justified-6bulan" role="tabpanel">
                                            <h5>Rekap 6 Bulanan</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered enamb-header-table">
                                                    <tr>
                                                        <td style="width:20%; text-align:center;">
                                                            <img src="<?= base_url(); ?>/assets/img/avatars/new.png" style="width:100px;"><br>
                                                            <strong>PT. Indoneptune Net Mfg.</strong>
                                                        </td>

                                                        <td style="width:55%; text-align:center;">
                                                            <h6 style="margin:5px 0;"><strong>PREVENTIVE MAINTENANCE CHECK LIST</strong></h6>
                                                        </td>

                                                        <td style="width:25%;">
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
                                                                    <strong>PERIODE : 6 BULANAN</strong>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong> BULAN :<?= format_bulan_indonesia($bulan) ?></strong>
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td><strong>TAHUN : <?= $tahun; ?></strong></td>
                                                    </tr>
                                                </table>
                                                <table class="table table-bordered enamb-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: 10px;">Item Pemeriksaan</th>
                                                            <th style="font-size: 10px;">Standar</th>
                                                            <?php foreach ($header_enambulan as $enambulan) : ?>
                                                                <th style="text-align: center; font-sie:10px"><?= date('F', mktime(0, 0, 0, $enambulan, 1)) ?></th>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($items_enambulan as $enam_bulan) : ?>
                                                            <tr>
                                                                <td style="font-=size:10px"><?= $enam_bulan['nama_item'] ?></td>
                                                                <td style="font-size:10px"><?= $enam_bulan['standar'] ?></td>
                                                                <?php foreach ($header_enambulan as $bulan) : ?>
                                                                    <td class="text-center" style="font: size 10px;">
                                                                        <?= $matrix_enambulan[$enam_bulan['id']][$bulan] ?? '-' ?>
                                                                    </td>
                                                                <?php endforeach; ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                            <div class="row" style="border: 1px solid grey; font-size:11px">
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
                                        <div class="tab-pane fade" id="navs-justified-tahunan" role="tabpanel">
                                            <h5>Tahunan</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered th-header-table">
                                                    <tr>
                                                        <td style="width:20%; text-align:center;">
                                                            <img src="<?= base_url(); ?>/assets/img/avatars/new.png" style="width:100px;"><br>
                                                            <strong>PT. Indoneptune Net Mfg.</strong>
                                                        </td>

                                                        <td style="width:55%; text-align:center;">
                                                            <h6 style="margin:5px 0;"><strong>PREVENTIVE MAINTENANCE CHECK LIST</strong></h6>
                                                        </td>

                                                        <td style="width:25%;">
                                                            <table class="table table-bordered table-sm" style="font: size 11px; margin-bottom:0;">
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
                                                        <td><strong>NO. MESIN :</strong> <?= $header['mach_no']; ?></td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <strong>PERIODE : TAHUNAN</strong>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong> BULAN :<?= format_bulan_indonesia($bulan) ?></strong>
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td><strong>TAHUN : <?= $tahun; ?></strong></td>
                                                    </tr>
                                                </table>
                                                <table class="table table-bordered th-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: 10px;">Item Pemeriksaan</th>
                                                            <th style="font-size: 10px;">Standar</th>
                                                            <?php foreach ($header_tahunan as $tahunan) : ?>
                                                                <th style="text-align: center; font-size:10px"><?= date('F', mktime(0, 0, 0, $tahunan, 1)) ?></th>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($items_tahunan as $tahun) : ?>
                                                            <tr>
                                                                <td style="font-size:10px"><?= $tahun['nama_item'] ?></td>
                                                                <td style="font-size: 10px;"><?= $tahun['standar'] ?></td>
                                                                <?php foreach ($header_tahunan as $bulan) : ?>
                                                                    <td class="text-center" style="font-size: 10px;">
                                                                        <?= $matrix_tahunan[$tahun['id']][$bulan] ?? '-' ?>
                                                                    </td>
                                                                <?php endforeach; ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                            <div class="row" style="border: 1px solid grey; font-size:11px">
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
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->




    <script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery-ui.min.js"></script>

    <!-- modal -->

    <script>
        document.getElementById('filter_bulan').addEventListener('change', function() {
            const bulan = this.value;
            // path saat ini
            window.location.href = window.location.pathname + "?filter_bulan=" + bulan;
        });
    </script>