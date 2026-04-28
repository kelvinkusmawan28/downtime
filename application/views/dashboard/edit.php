<style>
    .timeline {
        position: relative;
        padding-left: 20px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        height: 100%;
        width: 2px;
        background: lavender;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
        background-color: ghostwhite;
        border-radius: 10px;
    }

    .font-kecil {
        font-size: 12px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -16px;
        top: 5px;
        width: 10px;
        height: 10px;
        background: mediumpurple;
        border-radius: 50%;
    }
</style>

<?php if ($mesinof['ket_id'] == '24') : ?>
    <div class="mb-5" style="border-bottom: 1px solid darkslateblue; padding :5px ;">
        <span style="color: red; font-size : 16px;">INFO MESIN STOP</span> <br>
        <span style="color: black; font-size : 14px ;">Mesin : <?= $title['mach_no']; ?></span> <br>
        <span style="color: black; font-size : 14px ;">Spek Mesin : <?= $title['mach_name']; ?></span> <br>
        <span style="color: black; font-size : 14px ;">Departemen : <?= $title['departemen']; ?></span>
    </div>
    <div class="nav-align-top">
        <ul class="nav nav-pills mb-4 nav-justified flex-wrap" role="tablist">
            <li class="nav-item p-1">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-profile" aria-controls="navs-pills-justified-profile" aria-selected="true">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-gear me-2"></i>Mesin Stop
                    </span>
                    <i class="icon-base bx bx-file icon-sm d-sm-none"></i>
                </button>
            </li>
            <li class="nav-item p-1">
                <button type="button" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-timeline" aria-controls="navs-pills-justified-timeline" aria-selected="false">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i>
                        History
                    </span>

                </button>
            </li>
            <li class="nav-item p-1">
                <button type="button" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-perbaikan" aria-controls="navs-pills-justified-perbaiikan" aria-selected="false">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-clipboard-list me-2"></i>
                        Rekap Perbaikan
                    </span>

                </button>
            </li>
            <?php if ($this->session->userdata('cekdowntime_master') == '1') : ?>
                <li class="nav-item p-1">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-messages" aria-controls="navs-pills-justified-messages" aria-selected="false">
                        <span class="d-none d-sm-inline-flex align-items-center">
                            <i class="fa-solid fa-pen me-2"></i>Ppic Notes
                        </span>
                    </button>
                </li>
            <?php endif; ?>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="navs-pills-justified-profile" role="tabpanel">
                <form action="<?= base_url('dashboard/update'); ?>" method="post">
                    <?php if ($total['left'] > 1) : ?>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="alert alert-light-warning d-flex align-items-center justify-content-center" style="border-left: 5px solid #ffc107; background-color: #fff9e6; color: black; padding: 3px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">

                                    <i class="fa fa-exclamation-triangle mr-3 me-3" style="font-size: 2rem; color: #ffc107;"></i>

                                    <div class="text-center">
                                        <strong style="font-size:16px;">Perhatian :</strong>
                                        <br>
                                        <span style="font-size: 12px;">
                                            Mesin ini tercatat berhenti (<?= $mesinof['code_left']; ?>) selama
                                            <span style="font-weight: bold; border-bottom: 2px solid; font-size: 14px;"><?= $total['left']; ?> Shift berturut-turut</span>.
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="font-kecil">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control font-kecil" value="<?= $mesinof['tanggal']; ?>">
                            <input type="hidden" name="id" value="<?= $mesinof['id']; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="font-kecil">Shift</label>
                            <select name="shift" class="form-control font-kecil">
                                <option value="1" <?= $mesinof['shift'] == 1 ? 'selected' : '' ?>>PAGI</option>
                                <option value="2" <?= $mesinof['shift'] == 2 ? 'selected' : '' ?>>SIANG</option>
                                <option value="3" <?= $mesinof['shift'] == 3 ? 'selected' : '' ?>>MALAM</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 ">
                            <label class="font-kecil">User</label>
                            <input type="text" class="form-control font-kecil has-feedback-left" value="<?= $mesinof['name']; ?>" readonly>
                        </div>
                        <div class="col-md-6 ">
                            <label class="font-kecil">Update On</label>
                            <input type="text" class="form-control font-kecil has-feedback-left" value="<?= format_tanggal_indonesia_waktu($mesinof['ket_on']); ?>" readonly>
                        </div>
                    </div>
                    <!-- <div class="col-md-8">
                        <label class="font-kecil">No Mesin</label>
                        <input type="text" class="form-control font-kecil" value="Mesin <?= $mesinof['mach_no']; ?> - <?= $mesinof['mach_name']; ?>" readonly>
                        <input type="hidden" name="mach_id" value="<?= $mesinof['nomesin_id']; ?>">
                    </div> -->
                    <?php if ($mesinof['preventif_kode'] == 'RR-RING') :  ?>
                        <div class="row mt-2" style="border: solid 1px grey; padding :7px; border-radius :10px ;">
                            <div class="col-md-6">
                                <label class="font-kecil text-danger">Mesin Kiri</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="left" value="1" id="left" <?= ($mesinof['left'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="left">
                                        Left (L)
                                    </label>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <label class="font-kecil">Ket Mesin Stop -L </label>
                                <input type="text" name="ketof" id="ketof" class="form-control font-kecil" value="<?= $mesinof['code_left']; ?> - <?= $mesinof['reason_left']; ?>">
                                <input type="hidden" name="ket_id" id="ket_id" value="<?= $mesinof['ket_id']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="font-kecil">Spek Bahan -L</label>
                                <input type="text" name="ket_tb" id="ket_tb" class="form-control font-kecil" value="<?= $mesinof['jenis_left']; ?>" readonly>
                                <input type="hidden" name="id_benang" id="id_benang" value="<?= $mesinof['ket_tb']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="font-kecil">Spek Bobin -L</label>
                                <input type="text" name="ket_bb" id="ket_bb" class="form-control font-kecil" value="<?= $mesinof['spek_kiri']; ?>">
                                <input type="hidden" name="id_bobin" id="id_bobin" value="<?= $mesinof['ket_bb']; ?>">
                            </div>
                        </div>
                        <div class="row mt-2" style="border: solid 1px grey; padding :7px; border-radius :10px ;">
                            <div class="col-md-6">
                                <label class="font-kecil text-danger">Mesin Kanan</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="right" value="1" id="right" <?= ($mesinof['right'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="right">
                                        Right (R)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="font-kecil">Ket Mesin Stop -R</label>
                                <input type="text" name="ketof_r" id="ketof_r" class="form-control font-kecil" value="<?= $mesinof['code_right']; ?> - <?= $mesinof['reason_right']; ?>">
                                <input type="hidden" name="ket_id_r" id="ket_id_r" value="<?= $mesinof['ket_idr']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="font-kecil">Spek Bahan -R</label>
                                <input type="text" name="ket_tb_r" id="ket_tb_r" class="form-control font-kecil" value="<?= $mesinof['jenis_right']; ?>" readonly>
                                <input type="hidden" name="id_benang_r" id="id_benang_r" value="<?= $mesinof['ket_tbr']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="font-kecil">Spek Bobin -R</label>
                                <input type="text" name="ket_bb_r" id="ket_bb_r" class="form-control font-kecil" value="<?= $mesinof['spek_kanan']; ?>">
                                <input type="hidden" name="id_bobin_r" id="id_bobin_r" value="<?= $mesinof['ket_bbr']; ?>">
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="font-kecil">Ket Mesin Stop</label>

                                <input type="text" name="ketof" id="ketof" class="form-control font-kecil" value="<?= $mesinof['code_left']; ?> - <?= $mesinof['reason_left']; ?>">

                                <input type="hidden" name="ket_id" id="ket_id" value="<?= $mesinof['ket_id']; ?>">
                            </div>

                            <?php if (
                                $mesinof['dept_id'] == 'RR'
                                || $mesinof['dept_id'] == 'SP'
                                || $mesinof['dept_id'] == 'NT'
                                || $mesinof['dept_id'] == 'AR'
                                || $mesinof['dept_id'] == 'FN'
                            ) : ?>

                                <div class="col-md-4">
                                    <label class="font-kecil">Spek Bahan</label>

                                    <input type="text" name="ket_tb" id="ket_tb" class="form-control font-kecil" value="<?= $mesinof['jenis_left']; ?>">

                                    <input type="hidden" name="id_benang" id="id_benang" value="<?= $mesinof['ket_tb']; ?>">
                                </div>
                            <?php endif; ?>
                            <?php if ($mesinof['dept_id'] == 'RR' || $mesinof['dept_id'] == 'SP') : ?>
                                <div class="col-md-4">
                                    <label class="font-kecil"> Spek Bobin </label>
                                    <input type="text" name="ket_bb" id="ket_bb" class="form-control font-kecil" value="<?= $mesinof['spek_kiri']; ?>">
                                    <input type="hidden" name="id_bobin" id="id_bobin" value="<?= $mesinof['ket_bb']; ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-12">
                        <label class="font-kecil">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan" rows="3"><?= $mesinof['keterangan']; ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="text-danger font-kecil">*Ppic Notes</label>
                        <textarea class="form-control text-danger" rows="3" readonly><?= $mesinof['keterangan_ppic']; ?></textarea>
                    </div>

                    <br>

                    <?php
                    $tanggal_sekarang = date('Y-m-d');
                    $tanggal_data = $mesinof['tanggal'];
                    $selisih = (strtotime($tanggal_sekarang) - strtotime($tanggal_data)) / (60 * 60 * 24);

                    if ($selisih <= 1 && $selisih >= 0) : ?>
                        <button class="btn btn-primary" type="submit">Update</button>
                    <?php else : ?>
                        <span class="text-dark" style="font-size:10px">Noted : Data Valid <i class="fa-solid fa-check text-dark"></i></span>
                    <?php endif; ?>

                </form>
            </div>
            <div class="tab-pane fade" id="navs-pills-justified-timeline" role="tabpanel">
                <div class="mb-3 font-kecil">
                    <strong class="text-danger">
                        <?= date('d-m-Y', strtotime($riwayat['range']['start'])) ?>
                        /
                        <?= date('d-m-Y', strtotime($riwayat['range']['end'])) ?>
                    </strong>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered">
                        <thead style="background-color:lavender;">
                            <tr>
                                <th class="font-kecil">Keterangan</th>
                                <th class="font-kecil">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="font-kecil">

                            <?php $total_kiri = 0;
                            foreach ($riwayat['kiri'] as $ket => $data) :
                                $total_kiri += $data['jumlah'];
                            ?>
                                <tr>
                                    <td>
                                        <span style="border-left: 15px solid rgb(<?= $data['warna'] ?>); padding-left:10px;"> <?= $ket ?></span>
                                    </td>
                                    <td><?= $data['jumlah'] ?>x Shift</td>
                                </tr>
                            <?php endforeach; ?>

                            <?php $total_kanan = 0;
                            foreach ($riwayat['kanan'] as $ket => $data) :
                                $total_kanan += $data['jumlah']; ?>
                                <tr>
                                    <td>
                                        <span style="border-left: 15px solid rgb(<?= $data['warna'] ?>); padding-left:10px;"> <?= $ket ?></span>
                                    </td>
                                    <td>
                                        <?= $data['jumlah'] ?>x Shift
                                        <span class="badge bg-success">Kanan</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <tr>
                                <td>Total</td>
                                <?php
                                $total_hariL = ($total_kiri == 0) ? 0 : $total_kiri / 3;
                                $total_hariR = ($total_kanan == 0) ? 0 : $total_kanan / 3;
                                $formatL = number_format($total_hariL, 2, ',', '.');
                                $formatR = number_format($total_hariR, 2, ',', '.');
                                ?>
                                <td>
                                    <div style="display: flex; justify-content: space-between;">
                                        <div style="flex: 1; border-right: 1px solid #ccc; ">
                                            <?= ($total_kiri == 0) ? '-' : $total_kiri . ' x Shift<br>Est: ' . $formatL . ' Hari' ?>
                                        </div>

                                        <div style="flex: 1; padding-left: 2px;">
                                            <?= ($total_kanan == 0) ? '' : $total_kanan . ' x Shift<br>Est: ' . $formatR . ' Hari' ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <ul class="timeline mb-0 ">

                    <?php foreach ($riwayat['timeline'] as $row) : ?>

                        <li class="timeline-item  ps-6 border-left-dashed">
                            <div class="timeline-event ps-1">

                                <div class="timeline-header">
                                    <span class="timeline-indicator-advanced timeline-indicator-danger border-0 shadow-none">
                                        <i class="icon-base bx bx-error-circle text-danger"></i>
                                    </span>
                                    <small class="text-primary text-uppercase font-kecil">
                                        <?php if ($row['shift'] == 1) : ?>
                                            <span class="text-primary">Shift Pagi</span>
                                        <?php elseif ($row['shift'] == 2) : ?>
                                            <span class="text-primary">Shift Siang</span>
                                        <?php else : ?>
                                            <span class="text-primay">Shift Malam</span>
                                        <?php endif; ?>
                                    </small>
                                </div>

                                <small class=" text-danger" style="font-size: 10px;">

                                    <?= format_tanggal_indonesia_waktu($row['tanggal']); ?>
                                </small>

                                <p class="text-dark mb-0">
                                    <span style="border-left: 15px solid rgb(<?= $row['clr_left'] ?>); padding-left:10px;"> <?= $row['reason_left'] ?? '' ?></span> |
                                    <span style="border-left: 15px solid rgb(<?= $row['clr_right'] ?>); padding-left:10px;"> <?= $row['reason_right'] ?? '' ?></span>
                                </p>

                            </div>
                        </li>

                    <?php endforeach; ?>

                </ul>
            </div>
            <div class="tab-pane fade" id="navs-pills-justified-perbaikan" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class=" text-primary">No</th>
                                <th class=" text-primary">Tanggal</th>
                                <th class=" text-primary">Kerusakan</th>
                                <th class=" text-primary">Downtime</th>
                                <th class=" text-primary">Petugas</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 12px;">
                            <?php $no = 0;
                            foreach ($perbaikan as $data) : $no++; ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td><?= format_tanggal_indonesia($data['tanggal']); ?></td>
                                    <td><?= $data['remark'] ?></td>
                                    <td><?= format_downtime($data['downtime_kerusakan']);  ?></td>
                                    <td><?= $data['user']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="tab-pane fade " id="navs-pills-justified-messages" role="tabpanel">
                <form action="<?= base_url('dashboard/update_ppic'); ?>" method="post">
                    <div class="col-12">
                        <label>Keterangan</label>
                        <textarea class="form-control" name="keterangan_ppic" rows="3"><?= $mesinof['keterangan_ppic']; ?></textarea>
                        <input type="hidden" name="id" value="<?= $mesinof['id']; ?>">
                    </div>
                    <br>
                    <button class="btn btn-primary" type="submit">
                        Simpan
                    </button>

                </form>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="mb-5" style="border-bottom: 1px solid darkslateblue; padding :5px ;">
        <span style="color: red; font-size : 16px;">INFO MESIN STOP</span> <br>
        <span style="color: black; font-size : 14px ;">Mesin : <?= $title['mach_no']; ?></span> <br>
        <span style="color: black; font-size : 14px ;">Spek Mesin : <?= $title['mach_name']; ?></span> <br>
        <span style="color: black; font-size : 14px ;">Departemen : <?= $title['departemen']; ?></span>
    </div>

    <div class="nav-align-top ">
        <ul class="nav nav-pills mb-4 nav-justified flex-wrap" role="tablist">
            <li class="nav-item p-1">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-profile" aria-controls="navs-pills-justified-profile" aria-selected="true">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-gear me-2"></i>Mesin Stop
                    </span>
                    <i class="icon-base bx bx-file icon-sm d-sm-none"></i>
                </button>
            </li>
            <li class="nav-item p-1">
                <button type="button" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-timeline" aria-controls="navs-pills-justified-profile" aria-selected="false">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i>
                        History
                    </span>
                </button>
            </li>
            <li class="nav-item p-1">
                <button type="button" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-perbaikan" aria-controls="navs-pills-justified-profile" aria-selected="false">
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <i class="fa-solid fa-clipboard-list me-2"></i>
                        Rekap Perbaikan
                    </span>

                </button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="navs-pills-justified-profile" role="tabpanel">
                <form action="<?= base_url('dashboard/update'); ?>" method="post">
                    <?php if ($total['left'] > 1) : ?>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="alert alert-light-warning d-flex align-items-center justify-content-center" style="border-left: 5px solid #ffc107; background-color: #fff9e6; color: black; padding: 3px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">

                                    <i class="fa fa-exclamation-triangle mr-3 me-3" style="font-size: 2rem; color: #ffc107;"></i>

                                    <div class="text-center">
                                        <strong style="font-size:16px;">Perhatian :</strong>
                                        <br>
                                        <span style="font-size: 12px;">
                                            Mesin ini tercatat berhenti (<?= $mesinof['code_left']; ?>) selama
                                            <span style="font-weight: bold; border-bottom: 2px solid; font-size: 14px;"><?= $total['left']; ?> Shift berturut-turut</span>.
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">

                        <div class="col-md-6">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control font-kecil" value="<?= $mesinof['tanggal']; ?>">
                            <input type="hidden" name="id" value="<?= $mesinof['id']; ?>">
                            <input type="hidden" name="dept_id" value="<?= $mesinof['dept_id']; ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Shift</label>
                            <select name="shift" class="form-control font-kecil">
                                <option value="1" <?= $mesinof['shift'] == 1 ? 'selected' : '' ?>>PAGI</option>
                                <option value="2" <?= $mesinof['shift'] == 2 ? 'selected' : '' ?>>SIANG</option>
                                <option value="3" <?= $mesinof['shift'] == 3 ? 'selected' : '' ?>>MALAM</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 ">
                            <label>User</label>
                            <input type="text" class="form-control font-kecil has-feedback-left" value="<?= $mesinof['name']; ?>" readonly>
                        </div>
                        <div class="col-md-6 ">
                            <label>Update On</label>
                            <input type="text" class="form-control font-kecil has-feedback-left" value="<?= format_tanggal_indonesia_waktu($mesinof['ket_on']); ?>" readonly>
                        </div>
                    </div>
                    <!-- <div class="col-md-12">
                        <label>No Mesin</label>
                        <input type="text" class="form-control font-kecil" value="Mesin <?= $mesinof['mach_no']; ?> - <?= $mesinof['mach_name']; ?>" readonly>
                        <input type="hidden" name="mach_id" value="<?= $mesinof['nomesin_id']; ?>">
                    </div> -->


                    <?php if ($mesinof['preventif_kode'] == 'RR-RING') :  ?>
                        <div class="row mt-2" style="border: solid 1px grey; padding :7px; border-radius :10px ;">
                            <div class="col-md-6">
                                <label class="font-kecil text-danger">Mesin Kiri</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="left" value="1" id="left" <?= ($mesinof['left'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="left">
                                        Left (L)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="font-kecil">Ket Mesin Stop -L </label>
                                <input type="text" name="ketof" id="ketof" class="form-control font-kecil" value="<?= $mesinof['code_left']; ?> - <?= $mesinof['reason_left']; ?>">
                                <input type="hidden" name="ket_id" id="ket_id" value="<?= $mesinof['ket_id']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="font-kecil">Spek Bahan -L</label>
                                <input type="text" name="ket_tb" id="ket_tb" class="form-control font-kecil" value="<?= $mesinof['jenis_left']; ?>" readonly>
                                <input type="hidden" name="id_benang" id="id_benang" value="<?= $mesinof['ket_tb']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="font-kecil">Spek Bobin -L</label>
                                <input type="text" name="ket_bb" id="ket_bb" class="form-control font-kecil" value="<?= $mesinof['spek_kiri']; ?>">
                                <input type="hidden" name="id_bobin" id="id_bobin" value="<?= $mesinof['ket_bb']; ?>">
                            </div>
                        </div>
                        <div class="row mt-2" style="border: solid 1px grey; padding :7px; border-radius :10px ;">
                            <div class="col-md-6">
                                <label class="font-kecil text-danger">Mesin Kanan</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="right" value="1" id="right" <?= ($mesinof['right'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="right">
                                        Right (R)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="font-kecil">Ket Mesin Stop -R</label>
                                <input type="text" name="ketof_r" id="ketof_r" class="form-control font-kecil" value="<?= $mesinof['code_right']; ?> - <?= $mesinof['reason_right']; ?>">
                                <input type="hidden" name="ket_id_r" id="ket_id_r" value="<?= $mesinof['ket_idr']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="font-kecil">Spek Bahan -R</label>
                                <input type="text" name="ket_tb_r" id="ket_tb_r" class="form-control font-kecil" value="<?= $mesinof['jenis_right']; ?>" readonly>
                                <input type="hidden" name="id_benang_r" id="id_benang_r" value="<?= $mesinof['ket_tbr']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="font-kecil">Spek Bobin -R</label>
                                <input type="text" name="ket_bb_r" id="ket_bb_r" class="form-control font-kecil" value="<?= $mesinof['spek_kanan']; ?>">
                                <input type="hidden" name="id_bobin_r" id="id_bobin_r" value="<?= $mesinof['ket_bbr']; ?>">
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Ket Mesin Stop</label>

                                <input type="text" name="ketof" id="ketof" class="form-control font-kecil" value="<?= $mesinof['code_left']; ?> - <?= $mesinof['reason_left']; ?>">
                                <input type="hidden" name="ket_id" id="ket_id" value="<?= $mesinof['ket_id']; ?>">
                                <input type="hidden" name="ket_id_old" id="ket_id_old" value="<?= $mesinof['ket_id']; ?>">
                                <input type="hidden" name="nomesin_id" id="nomesin_id" value="<?= $mesinof['nomesin_id']; ?>">

                            </div>



                            <?php if (
                                $mesinof['dept_id'] == 'RR'
                                || $mesinof['dept_id'] == 'SP'
                                || $mesinof['dept_id'] == 'NT'
                                || $mesinof['dept_id'] == 'AR'
                                || $mesinof['dept_id'] == 'FN'
                            ) : ?>

                                <div class="col-md-4">
                                    <label>Spek Bahan</label>

                                    <input type="text" name="ket_tb" id="ket_tb" class="form-control font-kecil" value="<?= $mesinof['jenis_left']; ?>">

                                    <input type="hidden" name="id_benang" id="id_benang" value="<?= $mesinof['ket_tb']; ?>">
                                </div>
                            <?php endif; ?>
                            <?php if ($mesinof['dept_id'] == 'RR' || $mesinof['dept_id'] == 'SP') : ?>
                                <div class="col-md-4">
                                    <label class="font-kecil">Spek Bobin </label>
                                    <input type="text" name="ket_bb" id="ket_bb" class="form-control font-kecil" value="<?= $mesinof['spek_kiri']; ?>">
                                    <input type="hidden" name="id_bobin" id="id_bobin" value="<?= $mesinof['ket_bb']; ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="col-12">
                        <label>Keterangan</label>

                        <textarea class="form-control text-danger" name="keterangan" id="keterangan" rows="3"><?= $mesinof['keterangan']; ?></textarea>

                    </div>

                    <br>


                    <?php
                    $tanggal_sekarang = date('Y-m-d');
                    $tanggal_data = $mesinof['tanggal'];
                    $selisih = (strtotime($tanggal_sekarang) - strtotime($tanggal_data)) / (60 * 60 * 24);

                    if ($selisih <= 1 && $selisih >= 0) : ?>
                        <button class="btn btn-primary" type="submit">Update</button>
                    <?php else : ?>
                        <span class="text-dark" style="font-size:10px">Noted : Data Valid <i class="fa-solid fa-check text-dark"></i></span>
                    <?php endif; ?>



                </form>
            </div>
            <div class="tab-pane fade" id="navs-pills-justified-timeline" role="tabpanel">
                <div class="mb-3 font-kecil">
                    <strong class="text-danger">
                        <?= date('d-m-Y', strtotime($riwayat['range']['start'])) ?>
                        /
                        <?= date('d-m-Y', strtotime($riwayat['range']['end'])) ?>
                    </strong>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered">
                        <thead style="background-color:lavender;">
                            <tr>
                                <th class="font-kecil">Keterangan</th>
                                <th class="font-kecil">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="font-kecil">

                            <?php $total_kiri = 0;
                            foreach ($riwayat['kiri'] as $ket => $data) :
                                $total_kiri += $data['jumlah'];
                            ?>
                                <tr>
                                    <td>
                                        <span style="border-left: 15px solid rgb(<?= $data['warna'] ?>); padding-left:10px;"> <?= $ket ?></span>
                                    </td>
                                    <td><?= $data['jumlah'] ?>x Shift</td>
                                </tr>
                            <?php endforeach; ?>

                            <?php $total_kanan = 0;
                            foreach ($riwayat['kanan'] as $ket => $data) :
                                $total_kanan += $data['jumlah']; ?>
                                <tr>
                                    <td>
                                        <span style="border-left: 15px solid rgb(<?= $data['warna'] ?>); padding-left:10px;"> <?= $ket ?></span>
                                    </td>
                                    <td>
                                        <?= $data['jumlah'] ?>x Shift
                                        <span class="badge bg-success">Kanan</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <tr>
                                <td>Total</td>
                                <?php
                                $total_hariL = ($total_kiri == 0) ? 0 : $total_kiri / 3;
                                $total_hariR = ($total_kanan == 0) ? 0 : $total_kanan / 3;
                                $formatL = number_format($total_hariL, 2, ',', '.');
                                $formatR = number_format($total_hariR, 2, ',', '.');
                                ?>
                                <td>
                                    <div style="display: flex; justify-content: space-between;">
                                        <div style="flex: 1; border-right: 1px solid #ccc; ">
                                            <?= ($total_kiri == 0) ? '-' : $total_kiri . ' x Shift<br>Est: ' . $formatL . ' Hari' ?>
                                        </div>

                                        <div style="flex: 1; padding-left: 2px;">
                                            <?= ($total_kanan == 0) ? '' : $total_kanan . ' x Shift<br>Est: ' . $formatR . ' Hari' ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>



                        </tbody>
                    </table>
                </div>

                <ul class="timeline mb-0 ">

                    <?php foreach ($riwayat['timeline'] as $row) : ?>

                        <li class="timeline-item  ps-6 border-left-dashed">
                            <div class="timeline-event ps-1">

                                <div class="timeline-header">
                                    <span class="timeline-indicator-advanced timeline-indicator-danger border-0 shadow-none">
                                        <i class="icon-base bx bx-error-circle text-danger"></i>
                                    </span>
                                    <small class="text-primary text-uppercase font-kecil">
                                        <?php if ($row['shift'] == 1) : ?>
                                            <span class="text-primary">Shift Pagi</span>
                                        <?php elseif ($row['shift'] == 2) : ?>
                                            <span class="text-primary">Shift Siang</span>
                                        <?php else : ?>
                                            <span class="text-primay">Shift Malam</span>
                                        <?php endif; ?>
                                    </small>
                                </div>

                                <small class="text-danger" style="font-size: 10px;">

                                    <?= format_tanggal_indonesia_waktu($row['tanggal']); ?>
                                </small>

                                <p class="text-dark mb-0">
                                    <span style="border-left: 15px solid rgb(<?= $row['clr_left'] ?>); padding-left:10px;"> <?= $row['reason_left'] ?? '' ?></span> |
                                    <span style="border-left: 15px solid rgb(<?= $row['clr_right'] ?>); padding-left:10px;"> <?= $row['reason_right'] ?? '' ?></span>
                                </p>

                            </div>
                        </li>

                    <?php endforeach; ?>

                </ul>


            </div>
            <div class="tab-pane fade" id="navs-pills-justified-perbaikan" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class=" text-primary">No</th>
                                <th class=" text-primary">Tanggal</th>
                                <th class=" text-primary">Kerusakan</th>
                                <th class=" text-primary">Downtime</th>
                                <th class=" text-primary">Petugas</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 12px;">
                            <?php $no = 0;
                            foreach ($perbaikan as $data) : $no++; ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td><?= format_tanggal_indonesia($data['tanggal']); ?></td>
                                    <td><?= $data['remark'] ?></td>
                                    <td><?= format_downtime($data['downtime_kerusakan']);  ?></td>
                                    <td><?= $data['user']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>


<?php endif; ?>




<div class="modal modal-blur fade" id="modal-error" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-warning"></div>
            <div class="modal-body text-center py-4">
                <svg class="icon mb-2 text-warning icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 9v2m0 4v.01" />
                    <path d="M12 5a7 7 0 1 0 0 14a7 7 0 0 0 0 -14z" />
                </svg>
                <h3>Mohon Maaf,</h3>
                <div class="text-secondary" id="error-message">Pesan error akan muncul di sini</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <a href="#" class="btn btn-warning w-100" data-bs-dismiss="modal">Tutup</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).on("submit", "form", function(e) {

        const id = $("#ket_id").val();
        const id_benang = $("#id_benang").val();
        const id_bobin = $("#id_bobin").val();
        const ket = $("#keterangan").val();

        console.log("ID:", id);
        console.log("Benang:", id_benang);
        console.log("Bobin:", id_bobin);

        function showError(message) {
            $("#error-message").text(message);

            const modal = new bootstrap.Modal(document.getElementById('modal-error'));
            modal.show();
        }

        if (id == 24 && !id_benang) {
            e.preventDefault();
            showError("Spek Benang wajib diisi jika memilih TB - Tunggu Bahan.");
            return false;
        }
        if (id != 24 && id_benang > 0) {
            e.preventDefault();
            showError("Kolom Spek bahan wajid di HAPUS Jika Alasan Bukan TB - Tunggu Bahan.");
            return false;
        }


        if (id == 32 && !id_bobin) {
            e.preventDefault();
            showError("Spek bobin wajib diisi jika memilih BB - Tunggu BOBIN.");
            return false;
        }

        if (id != 32 && id_bobin > 0) {
            e.preventDefault();
            showError("Kolom Spek bobin wajid di HAPUS Jika Alasan Bukan BB - Tunggu Bobin.");
            return false;
        }
        if (id == 18 && !ket) {
            e.preventDefault();
            showError("Keterangan wajib diisi jika memilih ZZ - OTHER.");
            return false;
        }
    });
</script>