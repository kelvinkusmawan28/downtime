<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12" style="text-align: right;">
            <a href="<?= base_url('report'); ?>" class="btn btn-sm btn-warning"> <i class="fa fa-arrow-left me-2"></i> Kembali</a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card" style="border: 1px solid darkgray; ">
                <div class="row" style="margin: 5px;">
                    <div class="col-lg-2" style="text-align: center;">
                        <img src="<?= base_url(); ?>/assets/img/avatars/user.png" alt style="width: 80px; height: auto;" class="rounded-circle" />
                    </div>
                    <div class="col-lg-4" style="font-size: 14px;">
                        <input type="hidden" id="name" value="<?= $user['name']; ?>">
                        <span class="card-title text-dark" style="border-bottom: 1px solid black;">Nama : <?= $user['name']; ?></span> <br>
                        <span class="card-title text-dark" style="border-bottom: 1px solid black;">Departemen : <?= $user['departemen']; ?></span> <br>
                        <span class="card-title text-dark" style="border-bottom: 1px solid black;">Jabatan : <?= $user['jabatan']; ?></span>
                    </div>
                    <div class="col-lg-3">
                        <label class="font-kecil font-bold text-azure text-primary">Bulan</label>
                        <select name="filter_bulan" id="filter_bulan" class="form-select font-kecil mt-0">
                            <option value="all" <?= $filter_bulan == 'all' ? 'selected' : '' ?>>Semua Bulan</option>
                            <?php foreach ($bulan_options as $bl) : ?>
                                <?php if (!empty($bl['bulan']) && !empty($bl['nama_bulan'])) : ?>
                                    <option value="<?= $bl['bulan']; ?>" <?= ($filter_bulan == $bl['bulan'] || ($filter_bulan == 'all' && $bln_sekarang == $bl['bulan'])) ? 'selected' : '' ?>>
                                        <?= $bl['nama_bulan']; ?>
                                    </option>

                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>

                    </div>
                    <div class="col-lg-3">
                        <label class="font-kecil font-bold text-azure  text-primary">Tahun</label>
                        <select name="filter_tahun" id="filter_tahun" class="form-select font-kecil mt-0">
                            <option value="all" <?= $filter_tahun == 'all' ? 'selected' : '' ?>>Semua Tahun</option>
                            <?php foreach ($tahun_options as $th) : ?>
                                <option value="<?= $th['tahun']; ?>" <?= ($filter_tahun == $th['tahun'] || ($filter_tahun == 'all' && $thn_sekarang == $th['tahun'])) ? 'selected' : '' ?>>
                                    <?= $th['tahun']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 ">
            <div class="card h-100">
                <div class="card-header ">
                    <div class="card-title mb-0">
                        <h5 class="mb-1 me-2  text-primary"><?= $title; ?></h5>
                    </div>
                </div>
                <div class="card-body font-kecil ">
                    <div class="table-responsive">
                        <table id="riwayatTable" class="tabel datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Mesin</th>
                                    <th>Kerusakan</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Waktu Perbaikan</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#riwayatTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "<?= base_url('report/filter_riwayat') ?>",
                type: "POST",
                data: function(d) {
                    d.name = $('#name').val();
                    d.bulan = $('#filter_bulan').val();
                    d.tahun = $('#filter_tahun').val();
                }
            },
            columns: [{
                    data: 'no'
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'mesin'
                },
                {
                    data: 'kerusakan'
                },
                {
                    data: 'jam mulai'
                },
                {
                    data: 'jam selesai'
                },
                {
                    data: 'waktu perbaikan'
                },
                {
                    data: 'tindakan'
                }
            ],

        });

        $('#name,#filter_bulan,#filter_tahun').change(function() {
            table.ajax.reload();
        });

    });
</script>