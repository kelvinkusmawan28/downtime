<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12" style="text-align: right;">
            <a href="<?= base_url('report'); ?>" class="btn btn-sm btn-warning"> <i class="fa fa-arrow-left me-2"></i> Kembali</a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card" style="border: 1px solid darkgray;">
                <div class="row" style="margin: 10px;">
                    <div class="col-lg-3" style="text-align: left; font: size 14px;">
                        <input type="hidden" id="mesin" value="<?= $user['mach_no']; ?>">
                        <input type="hidden" name="dept" id="dept" value="<?= $dept; ?>">
                        <span class="card-title text-danger">Departemen : <?= $header_dept['departemen']; ?></span> <br>
                        <span class="card-title text-danger">Mesin : <?= $user['mach_no']; ?></span> <br>
                        <span class="card-title text-danger">Spekmesin : <?= $user['mach_name']; ?></span> <br>

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

                    <div class=" mt-3 col-lg-3" style="text-align: right;">
                        <a id="btn-export-excel" class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel-o"></i><span class="ml-1" style="color: aliceblue;">Export To Excel</span>
                        </a>

                        <!-- <a id="btn-export-pdf" class="btn btn-danger btn-sm" target="_blank">
                            <i class="fa fa-file-pdf-o"></i><span class="ml-1" style="color: aliceblue;">Export To PDF</span>
                        </a> -->
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
                        <h5 class="mb-1 me-2  text-danger"><?= $title; ?></h5>
                    </div>
                </div>
                <div class="card-body font-kecil ">
                    <div class="table-responsive">
                        <table id="kerusakanTable" class="tabel datatable">
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
                                    <th>Teknisi</th>
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
        var table = $('#kerusakanTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "<?= base_url('report/kerusakan_riwayat') ?>",
                type: "POST",
                data: function(d) {
                    d.mesin = $('#mesin').val();
                    d.dept = $('#dept').val();
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
                },
                {
                    data: 'teknisi'
                }
            ],

        });

        $('#mesin,#dept,#filter_bulan,#filter_tahun').change(function() {
            table.ajax.reload();
        });

    });
</script>

<script>
    function updateExportLinks() {
        const mesin = $('#mesin').val();
        const bulan = $('#filter_bulan').val();
        const tahun = $('#filter_tahun').val();

        $('#btn-export-excel').attr('href', `<?= base_url('report/export_excel') ?>?mesin=${mesin}&bulan=${bulan}&tahun=${tahun}`);

    }

    $('#mesin, #filter_bulan, #filter_tahun').change(updateExportLinks);
    $(document).ready(updateExportLinks); // set awal
</script>