<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row ">
        <div class="col-lg-12 ">

            <div class="card h-100">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-lg-6">

                        </div>
                        <div class="col-lg-6" style="text-align: right;">
                            <a href="<?= base_url('cheklist'); ?>" class="btn btn-sm btn-warning"> <i class="fa fa-arrow-left me-2"></i> Kembali</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="mb-1 me-2"><?= $title; ?>
                                <hr>
                            </h4>
                        </div>
                        <div class="col-lg-6">
                            <span class="mb-1 me-2 text-dark">NO : MESIN <?= $header['mach_no']; ?></span> <br>
                            <span class="mb-1 me-2 text-dark">SPEK MESIN :<?= $header['mach_name']; ?></span>
                            <hr>
                        </div>
                    </div>

                </div>
                <div class="card-body font-kecil ">
                    <div class=" mt-2 row">
                        <div class="col-lg-5">
                            <?= $this->session->flashdata('message'); ?>
                        </div>
                    </div>
                    <form action="<?= base_url('cheklist/simpan_tigabulan') ?>" method="post">
                        <div class="row">
                            <div class="col-lg-9 mt-3">
                                <?php if (!$sudah_cek) : ?>
                                    <button type="button" id="btnAllOk" class="btn btn-sm btn-info text-dark mb-2">
                                        ✔ Semua OK
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-3 mb-2">
                                <label class="font-kecil font-bold text-azure">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control font-kecil" id="tanggal" value="<?= $tgl_sekarang; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <?php if ($ada_terlewat) : ?>
                                    <div class="alert alert-warning shadow-sm">
                                        <h6 class="font-weight-bold text-danger">
                                            ⚠️ PERINGATAN CHECKLIST PER 3 BULAN
                                        </h6>

                                        <p class="mb-2">Checklist berikut belum diisi:</p>

                                        <ul class="mb-0">
                                            <?php foreach ($triwulan_terlewat as $b) : ?>
                                                <li><?= $b ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>

                        <input type="hidden" name="mach_id" value="<?= $header['mach_id'] ?>">
                        <input type="hidden" name="kode" value="<?= $kode['kode'] ?>">

                        <div class="table-responsive">


                            <table class="table table-bordered">
                                <thead>
                                    <tr class="bg-info" style="color: black;">
                                        <th>No</th>
                                        <th>Item Pemeriksaan</th>
                                        <th>Standar</th>
                                        <th>OK</th>
                                        <th>NO</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($items as $row) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $row->nama_item ?></td>
                                            <td><?= $row->standar ?></td>
                                            <td class="text-center">
                                                <input type="radio" class="radio-ok" name="status[<?= $row->id_item ?>]" value="OK" <?= ($row->status == 'OK') ? 'checked' : '' ?> <?= ($sudah_cek) ? 'disabled' : '' ?>>
                                            </td>

                                            <td class="text-center">
                                                <input type="radio" class="radio-ng" name="status[<?= $row->id_item ?>]" value="NG" <?= ($row->status == 'NG') ? 'checked' : '' ?> <?= ($sudah_cek) ? 'disabled' : '' ?>>
                                            </td>


                                            <td>
                                                <input type="text" class="form-control" name="catatan[<?= $row->id_item ?>]" value="<?= $row->catatan ?>" <?= ($sudah_cek) ? 'disabled' : '' ?>>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-4">
                            <?php if (!$sudah_cek) : ?>
                                <div class="col-lg-3">
                                    <label>Petugas</label>
                                    <select name="petugas" class="form-control">
                                        <option><?= $this->session->userdata('name'); ?></option>
                                    </select>
                                </div>

                                <div class="col-lg-6 mt-5" style="text-align: center;">
                                    <button type="submit" class="btn btn-success text-dark">
                                        SIMPAN CHECKLIST
                                    </button>
                                </div>

                            <?php else : ?>
                                <?php if ($sudah_cek) : ?>
                                    <div class="text-danger text-center">
                                        ✔ <b>PREVENTIF PER 3 BULAN SUDAH DI CEK</b><br>
                                        Petugas : <b><?= $info_cek['petugas'] ?></b><br>
                                        Periode :
                                        <b>
                                            ke-<?= $periode['triwulan'] ?>
                                            (<?= format_tanggal_indonesia($periode['awal']) ?> –
                                            <?= format_tanggal_indonesia($periode['akhir']) ?>)
                                        </b><br>
                                        Tanggal cek : <?= format_tanggal_indonesia($info_cek['tanggal']) ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                        </div>



                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->
<!-- Modal Validasi -->
<div class="modal fade" id="modalValidasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Checklist Belum Lengkap</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ⚠️ Masih ada item pemeriksaan yang belum diisi <b>OK / NO</b>.<br>
                Silakan lengkapi terlebih dahulu.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>



<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>
<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery-ui.min.js"></script>

<script>
    $('#tanggal').change(function() {
        let tgl = $(this).val();
        let url = "<?= base_url('cheklist/tiga_bulan/' . $header['mach_id'] . '/' . $kode['kode']) ?>?tanggal=" + tgl;
        window.location.href = url;
    });
</script>

<script>
    document.getElementById('btnAllOk')?.addEventListener('click', function() {
        document.querySelectorAll('.radio-ok:not(:disabled)').forEach(function(el) {
            el.checked = true;
        });

    });
</script>

<script>
    document.querySelector('form')?.addEventListener('submit', function(e) {

        let valid = true;

        document.querySelectorAll('tbody tr').forEach(function(row) {

            let radios = row.querySelectorAll('input[type="radio"]');

            if (radios.length > 0) {
                let checked = row.querySelector('input[type="radio"]:checked');
                if (!checked) {
                    valid = false;
                    row.classList.add('table-danger');
                } else {
                    row.classList.remove('table-danger');
                }
            }
        });

        if (!valid) {
            e.preventDefault();

            let modal = new bootstrap.Modal(
                document.getElementById('modalValidasi')
            );
            modal.show();
        }
    });
</script>




<!-- modal -->
<script>
    $(document).ready(function() {

        var table = $('#cheklistTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "<?= base_url('cheklist/filter_harian') ?>",
                type: "POST",
                data: function(d) {
                    d.tanggal = $('#filter_tanggal').val();
                    d.kode = $('#kode').val();
                }
            },
            columns: [{
                    data: 'no',
                },
                {
                    data: 'item'
                },
                {
                    data: 'standar'
                },
                {
                    data: 'ok'
                },
                {
                    data: 'ng'
                },
                {
                    data: 'catatan'
                },
            ]

        });


        $('#filter_tanggal').change(function() {
            table.ajax.reload();
        });
    });
</script>