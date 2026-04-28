<div class="mb-5" style="border-bottom: 1px solid darkslateblue; padding :5px ;">
    <span style="color: red; font-size : 16px;">INFO MESIN STOP</span> <br>
    <span style="color: black; font-size : 12px ;">Mesin : <?= $dept_id['mach_no']; ?></span> <br>
    <span style="color: black; font-size : 12px ;">Spek Mesin : <?= $dept_id['mach_name']; ?></span>
</div>
<div class="nav-align-top ">
    <ul class="nav nav-pills mb-4 nav-fill" role="tablist">
        <li class="nav-item mb-1 mb-sm-0">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-profile" aria-controls="navs-pills-justified-profile" aria-selected="true">
                <span class="d-none d-sm-inline-flex align-items-center">
                    <i class="fa-solid fa-gear me-2"></i>Mesin Stop
                </span>
                <i class="icon-base bx bx-file icon-sm d-sm-none"></i>
            </button>
        </li>
        <li class="nav-item mb-1 mb-sm-0">
            <button type="button" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-timeline" aria-controls="navs-pills-justified-profile" aria-selected="false">
                <span class="d-none d-sm-inline-flex align-items-center">
                    <i class="fa-solid fa-clipboard-list me-2"></i>
                    Rekap Perbaikan
                </span>

            </button>
        </li>
    </ul>

    <div class=" tab-content">

        <div class="tab-pane fade show active" id="navs-pills-justified-profile" role="tabpanel">

            <form action="<?= base_url('dashboard/simpan'); ?>" method="post" enctype="multipart/form-data">

                <div class="row font-kecil ">


                    <div class="col-md-6 ">
                        <label>User</label>
                        <input type="text" class="form-control font-kecil has-feedback-left" name="user" id="user" value="<?= $this->session->userdata('name'); ?>" readonly>
                    </div>


                    <div class="col-md-6">
                        <label>Kode Departemen</label>
                        <input type="text" class=" form-control font-kecil" id="dept_id" name="dept_id" value="<?= $dept_id['dept_kode']; ?>" readonly>
                        <input type="hidden" name="mach_id" id="mach_id">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="font-kecil">Tanggal Sekarang : <span class="text-danger"><?= format_tanggal_indonesia($tgl_sekarang); ?> </span> </label><br>
                        <input type="date" name="tanggal" class="form-control font-kecil" id="tanggal" value="<?= $tgl_sekarang; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="font-kecil">Shift</label>
                        <select name="shift" id="shift" class="form-control font-kecil">
                            <option value="1" <?= $shift == 1  ? 'selected' : '' ?>>PAGI</option>
                            <option value="2" <?= $shift == 2  ? 'selected' : '' ?>>SIANG</option>
                            <option value="3" <?= $shift == 3  ? 'selected' : '' ?>>MALAM</option>
                        </select>
                    </div>
                </div>

                <!-- <div class="col-12">
                    <label class="font-kecil">Nomor Mesin</label>
                    <input type="text" class=" form-control font-kecil" value="<?= $dept_id['mach_no']; ?> - <?= $dept_id['mach_name'] ?>" readonly>
                </div> -->
                <?php if ($dept_id['preventif_kode'] == 'RR-RING') :  ?>
                    <div class="row mt-2" style="border: solid 1px grey; padding :7px; border-radius :10px ;">
                        <div class="col-md-6">
                            <label class="font-kecil text-danger">Mesin Kiri</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="left" value="1" id="left" required>
                                <label class="form-check-label" for="left">
                                    Left (L)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="font-kecil">Ket Mesin Stop -L </label>
                            <input type="text" name="ketof" id="ketof" class="form-control font-kecil" placeholder="Ketik Keterangan Mesin OF ..">
                            <input type="hidden" name="id" id="id">
                        </div>
                        <div class="col-md-6">
                            <label class="font-kecil">Spek Bahan -L</label>
                            <input type="text" name="ket_tb" id="ket_tb" class="form-control font-kecil" placeholder="Ketik Spek Benang ..">
                            <input type="hidden" name="id_benang" id="id_benang">
                        </div>
                        <div class="col-md-6">
                            <label class="font-kecil">Spek Bobin -L</label>
                            <input type="text" name="ket_bb" id="ket_bb" class="form-control font-kecil" placeholder="Ketik Spek Bobin ..">
                            <input type="hidden" name="id_bobin" id="id_bobin">
                        </div>
                    </div>
                    <div class="row mt-2" style="border: solid 1px grey; padding :7px; border-radius :10px ;">
                        <div class="col-md-6">
                            <label class="font-kecil text-danger">Mesin Kanan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="right" value="1" id="right">
                                <label class="form-check-label" for="right">
                                    Right (R)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="font-kecil">Ket Mesin Stop -R</label>
                            <input type="text" name="ketof_r" id="ketof_r" class="form-control font-kecil" placeholder="Ketik Ket Mesin OF ..">
                            <input type="hidden" name="id_r" id="id_r">
                        </div>
                        <div class="col-md-6">
                            <label class="font-kecil">Spek Bahan -R</label>
                            <input type="text" name="ket_tb_r" id="ket_tb_r" class="form-control font-kecil" placeholder="Ketik Spek Benang .." readonly>
                            <input type="hidden" name="id_benang_r" id="id_benang_r">
                        </div>
                        <div class="col-md-6">
                            <label class="font-kecil">Spek Bobin -R</label>
                            <input type="text" name="ket_bb_r" id="ket_bb_r" class="form-control font-kecil" placeholder="Ketik Spek Bobin ..">
                            <input type="hidden" name="id_bobin_r" id="id_bobin_r">
                        </div>
                    </div>
                <?php else : ?>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="font-kecil">Ket Mesin Stop</label>
                            <input type="text" name="ketof" id="ketof" class="form-control font-kecil" placeholder="Ketik KetMesin OF .." required>
                            <input type="hidden" name="id" id="id">
                        </div>
                        <?php if (
                            $dept_id['dept_kode'] == 'RR'
                            || $dept_id['dept_kode'] == 'SP'
                            || $dept_id['dept_kode'] == 'NT'
                            || $dept_id['dept_kode'] == 'AR'
                            // || $dept_id['dept_kode'] == 'FN'
                        ) : ?>
                            <div class="col-md-4">
                                <label class="font-kecil">Spek Bahan</label>
                                <input type="text" name="ket_tb" id="ket_tb" class="form-control font-kecil" placeholder="Ketik Spek Benang .." readonly>
                                <input type="hidden" name="id_benang" id="id_benang">
                            </div>
                        <?php endif; ?>
                        <?php if ($dept_id['dept_kode'] == 'RR' || $dept_id['dept_kode'] == 'SP') : ?>
                            <div class="col-md-4">
                                <label class="font-kecil">Spek Bobin </label>
                                <input type="text" name="ket_bb" id="ket_bb" class="form-control font-kecil" placeholder="Ketik Spek Bobin ..">
                                <input type="hidden" name="id_bobin" id="id_bobin">
                            </div>
                        <?php endif; ?>
                    </div>

                <?php endif; ?>

                <div class="col-12">
                    <label for="ket_tb" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                </div>





                <br>
                <div class="col-md-3">
                    <button class="btn btn-primary btn-block" type="submit">
                        Simpan
                    </button>
                </div>



            </form>
        </div>
        <div class="tab-pane fade" id="navs-pills-justified-timeline" role="tabpanel">
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
<!-- <script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script> -->
<script>
    $(document).ready(function() {

        function cekSpekBahan() {

            let id = $("#id").val();

            if (id == 24) {
                $("#ket_tb").prop("readonly", false);
            } else {
                $("#ket_tb").prop("readonly", true);
                $("#ket_tb").val("");
            }

        }


        setInterval(function() {
            cekSpekBahan();
        }, 300);

    });
    $(document).ready(function() {

        function cekSpekBahan() {

            let id = $("#id_r").val();

            if (id == 24) {
                $("#ket_tb_r").prop("readonly", false);
            } else {
                $("#ket_tb_r").prop("readonly", true);
                $("#ket_tb_r").val("");
            }

        }


        setInterval(function() {
            cekSpekBahan();
        }, 300);

    });

    $(document).ready(function() {

        function cekSpekBahan() {

            let id = $("#id").val();

            if (id == 32) {
                $("#ket_bb").prop("readonly", false);
            } else {
                $("#ket_bb").prop("readonly", true);
                $("#ket_bb").val("");
            }

        }


        setInterval(function() {
            cekSpekBahan();
        }, 300);

    });
    $(document).ready(function() {

        function cekSpekBahan() {

            let id = $("#id_r").val();

            if (id == 32) {
                $("#ket_bb_r").prop("readonly", false);
            } else {
                $("#ket_bb_r").prop("readonly", true);
                $("#ket_bb_r").val("");
            }

        }


        setInterval(function() {
            cekSpekBahan();
        }, 300);

    });
</script>
<script>
    $("form").on("submit", function(e) {
        const nomesin = $("#nomesin").val();
        const mach_id = $("#mach_id").val();
        const ketof = $("#ketof").val();
        const id = $("#id").val();
        const id_benang = $("#id_benang").val();
        const id_bobin = $("#id_bobin").val();
        const ket = $("#keterangan").val();

        function showError(message) {
            $("#error-message").text(message);
            $("#modal-error").modal("show");
        }

        if (!ketof || !id) {
            e.preventDefault();
            showError("Silakan Pilih Keterangan Mesin Stop Dari Data Yang Telah Di Sediakan.");
            return false;
        }

        if (id == 24 && !id_benang) {
            e.preventDefault();
            showError("Spek Benang wajib diisi jika memilih TB - Tunggu Bahan.");
            return false;
        }

        if (id == 32 && !id_bobin) {
            e.preventDefault();
            showError("Spek bobin wajib diisi jika memilih BB - Tunggu BOBIN.");
            return false;
        }
        if (id == 18 && !ket) {
            e.preventDefault();
            showError("Keterangan wajib diisi jika memilih ZZ - OTHER.");
            return false;
        }

    });
</script>