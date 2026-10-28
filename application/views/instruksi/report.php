<style>
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 15px 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid #eef2f6;
        transition: transform 0.2s;
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 15px;
    }

    .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        color: #6c757d;
        margin: 0;
    }

    .stat-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: #2d3436;
        display: block;
    }


    .bg-progres {
        background-color: #e3f2fd;
        color: #0d6efd;
    }

    .bg-selesai {
        background-color: #e8f5e9;
        color: #198754;
    }

    .bg-waiting {
        background-color: #fff3e0;
        color: #f39c12;
    }

    .bg-total {
        background-color: #f3e5f5;
        color: #6f42c1;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Order Statistics -->
        <div class="col-lg-12 ">
            <div class="card h-100">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="mb-1 me-2"><?= $title; ?></h5>
                        </div>
                        <div class="col-6" style="text-align: right;">
                            <a href="<?= base_url('dashboard'); ?>" class="btn btn-sm btn-warning"> <i class="fa fa-arrow-left me-2"></i> Kembali</a>
                        </div>
                    </div>

                </div>
                <div class="col-xl-12">
                    <div class="nav-align-top nav-tabs-shadow">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true">
                                    Dashboard
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="false">
                                    Laporan Kinerja Petugas
                                </button>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">
                                <div class="card-body font-kecil ">
                                    <div class="container mt-4">
                                        <div class="row">

                                            <div class="col-md-3 col-sm-6">
                                                <div class="stat-card detail-waiting" style="cursor: pointer;">
                                                    <div class="stat-icon bg-waiting "><i class="fas fa-clock"></i></div>
                                                    <div>
                                                        <p class="stat-label">Waiting</p>
                                                        <span class="stat-value" id="total_waiting_all">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                                <div class="stat-card detail-progres" style="cursor: pointer;">
                                                    <div class="stat-icon bg-progres"><i class="fas fa-spinner fa-spin"></i></div>
                                                    <div>
                                                        <p class="stat-label">Progres</p>
                                                        <span class="stat-value" id="total_progres_all">0</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-6">
                                                <div class="stat-card">
                                                    <div class="stat-icon bg-selesai"><i class="fas fa-check-circle"></i></div>
                                                    <div>
                                                        <p class="stat-label">Selesai</p>
                                                        <span class="stat-value" id="total_selesai_all">0</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-6">
                                                <div class="stat-card">
                                                    <div class="stat-icon bg-total"><i class="fas fa-list"></i></div>
                                                    <div>
                                                        <p class="stat-label">Total</p>
                                                        <span class="stat-value" id="total_all">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12" style=" padding:5px;">
                                        <div class="row" style="padding:10px;">
                                            <div class="col-lg-2 mb-2">
                                                <label class="font-kecil font-bold text-primary">Tanggal</label>
                                                <input type="date" id="filter_tanggal" name="filter_tanggal" class="form-control font-kecil" value="<?= $tgl_sekarang; ?>">
                                            </div>

                                            <div class="col-lg-3">
                                                <label class="font-kecil font-bold text-azure text-primary">Departemen</label>

                                                <?php
                                                $hakdowntime = $this->session->userdata('hakdowntime');
                                                $akses_dept_diberi = [];

                                                foreach ($downtime_dept_map as $index => $dept_code) {
                                                    $start = ($index * 2) - 2;
                                                    if (substr($hakdowntime, $start, 2) === '10') {
                                                        $akses_dept_diberi[] = $dept_code;
                                                    }
                                                }
                                                ?>

                                                <select name="filter" id="filter" class="form-select font-kecil mt-0">
                                                    <option value="all" <?= $filter_dept == '' ? 'selected' : '' ?>>Semua Departemen</option>
                                                    <?php foreach ($dept_options as $option) : ?>
                                                        <?php if (in_array($option['dept_id'],  $akses_dept_diberi)) : ?>
                                                            <option value="<?= $option['dept_id']; ?>" <?= $filter_dept == $option['dept_id'] ? 'selected' : ''  ?>>
                                                                <?= $option['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $option['departemen']; ?>
                                                            </option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>

                                                </select>
                                            </div>

                                            <div class="col-lg-2">
                                                <label class="font-kecil font-bold text-azure text-primary"> Status</label>
                                                <select name="filter_status" id="filter_status" class="form-select font-kecil mt-0">
                                                    <option value="all" <?= $filter_status == 'all' ? 'selected' : '' ?>>Semua Status</option>
                                                    <option class="text-dark" value="1" <?= $filter_status == 1  ? 'selected' : '' ?>>Selesai</option>
                                                    <option class="text-dark" value="0" <?= $filter_status == 0  ? 'selected' : '' ?>>Progres</option>
                                                    <option class="text-dark" value="2" <?= $filter_status == 2  ? 'selected' : '' ?>>Menunggu Antrian</option>

                                                </select>
                                            </div>
                                        </div>
                                        <span style="margin-left: 10px;"><?= format_tanggal_indonesia($tgl_sekarang) ?></span>
                                        <div class="row mt-3">
                                            <div class="col-lg-6" style="margin-left: 10px;">
                                                <span class="text-dark">WAITING: <b class="text-danger" id="total_waiting">0</b></span>||
                                                <span class="text-dark">PROGRES: <b class="text-danger" id="total_progres">0</b></span> ||
                                                <span class="text-dark">SELESAI: <b class="text-danger" id="total_selesai">0</b></span>

                                            </div>
                                            <div class="col-lg-5" style="text-align: right;">
                                                <span class="text-dark">Total Records: <b id="total">0</b></span>
                                            </div>
                                        </div>
                                    </div>

                                    <hr style="margin: 1px !important;">
                                    <div class="table-responsive">
                                        <table id="instruksiTable" class="tabel table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="background-color: #e3f2fd; color: black;">No</th>
                                                    <th style="background-color: #e3f2fd; color: black;">Mesin</th>
                                                    <th style="background-color: #e3f2fd; color: black;">Jenis Instruksi</th>
                                                    <th style="background-color: #e3f2fd; color: black;">Mulai</th>
                                                    <th style="background-color: #e3f2fd; color: black;">Selesai</th>
                                                    <th style="background-color: #e3f2fd; color: black;">Status</th>
                                                    <th style="background-color: #e3f2fd; color: black;">Petugas</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                                <div class="card-body font-kecil ">
                                    <div class="col-lg-12" style=" padding:5px;">
                                        <div class="row" style="padding:10px;">
                                            <div class="col-lg-3">
                                                <label class="font-kecil font-bold text-azure" st>Bulan</label>
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
                                                <label class="font-kecil font-bold text-azure">Tahun</label>
                                                <select name="filter_tahun" id="filter_tahun" class="form-select font-kecil mt-0">
                                                    <option value="all" <?= $filter_tahun == 'all' ? 'selected' : '' ?>>Semua Tahun</option>
                                                    <?php foreach ($tahun_options as $th) : ?>
                                                        <option value="<?= $th['tahun']; ?>" <?= ($filter_tahun == $th['tahun'] || ($filter_tahun == 'all' && $thn_sekarang == $th['tahun'])) ? 'selected' : '' ?>>
                                                            <?= $th['tahun']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-3">
                                                <label class="font-kecil font-bold text-azure">Departemen</label>

                                                <?php
                                                $hakdowntime = $this->session->userdata('hakdowntime');
                                                $akses_dept_diberi = [];

                                                foreach ($downtime_dept_map as $index => $dept_code) {
                                                    $start = ($index * 2) - 2;
                                                    if (substr($hakdowntime, $start, 2) === '10') {
                                                        $akses_dept_diberi[] = $dept_code;
                                                    }
                                                }
                                                ?>

                                                <select name="filter_departemen" id="filter_departemen" class="form-select font-kecil mt-0">
                                                    <option value="all" <?= $filter_departemen == '' ? 'selected' : '' ?>>Semua Departemen</option>
                                                    <?php foreach ($dept_options as $option) : ?>
                                                        <?php if (in_array($option['dept_id'],  $akses_dept_diberi)) : ?>
                                                            <option value="<?= $option['dept_id']; ?>" <?= $filter_departemen == $option['dept_id'] ? 'selected' : ''  ?>>
                                                                <?= $option['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $option['departemen']; ?>
                                                            </option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container mt-4">
                                        <div class="row">

                                            <div class="col-md-3 col-sm-6">
                                                <div class="stat-card detail-gi" style="cursor: pointer">
                                                    <div class=" stat-icon bg-progres"><i class="fas fa-list"></i></div>
                                                    <div>
                                                        <p class="stat-label text-dark">GI</p>
                                                        <span class="stat-value" id="total_gi">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                                <div class="stat-card detail-gm" style="cursor: pointer;">
                                                    <div class="stat-icon bg-progres"><i class="fas fa-list"></i></div>
                                                    <div>
                                                        <p class="stat-label text-dark">Ganti Kakesu</p>
                                                        <span class="stat-value" id="total_gm">0</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-6">
                                                <div class="stat-card detail-gb" style="cursor: pointer;">
                                                    <div class="stat-icon bg-progres"><i class="fas fa-list"></i></div>
                                                    <div>
                                                        <p class="stat-label text-dark">Ganti Bahan</p>
                                                        <span class="stat-value" id="total_gb">0</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-6">
                                                <div class="stat-card">
                                                    <div class="stat-icon bg-progres"><i class="fas fa-list"></i></div>
                                                    <div>
                                                        <p class="stat-label text-dark">Total</p>
                                                        <span class="stat-value" id="total_semua">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="table-responsive">
                                                <table id="PetugasTable" class="tabel table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="background-color: #e3f2fd; color: black;">No</th>
                                                            <th style="background-color: #e3f2fd; color: black;">Nama Petugas</th>
                                                            <th style="background-color: #e3f2fd; color: black;">GM</th>
                                                            <th style="background-color: #e3f2fd; color: black;">GB</th>
                                                            <th style="background-color: #e3f2fd; color: black;">GI</th>
                                                            <th style="background-color: #e3f2fd; color: black;">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div id="kelChart"></div>
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
<div class="modal fade" id="modalWarning" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-primary" style="font-size:12px;">Notes <i>IT Team</i> </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <svg class="icon mb-2 text-warning icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
                    <path d="M12 9v4" />
                    <path d="M12 17h.01" />
                </svg>
                <div class="text-danger" id="modalMessage"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="mesinof_waiting" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detail_waiting"></div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mesinof_progres" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detail_progres"></div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-status_cansel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-warning"></div>
            <div class="modal-body text-center py-4">
                <svg class="icon mb-2 text-warning icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
                    <path d="M12 9v4" />
                    <path d="M12 17h.01" />
                </svg>
                <h3>Anda Yakin,</h3>
                <div class="text-secondary" id="message">Data Ini Ingin Dibuka ?</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col"><a id="btn-okk" href="#" class="btn btn-warning w-100">
                                Ya
                            </a></div>
                        <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                Tidak
                            </a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFile" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>File / Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contentFile">

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalWarning" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-primary" style="font-size:12px;">Notes <i>IT Team</i> </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <svg class="icon mb-2 text-warning icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
                    <path d="M12 9v4" />
                    <path d="M12 17h.01" />
                </svg>
                <div class="text-danger" id="modalMessage"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="ganti_meai" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detail_instruksi"></div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ganti_kakesu" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detail_gm"></div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ganti_bahan" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detail_gb"></div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>
<!-- to filter form -->
<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery-ui.min.js"></script>
<script>
    const base_url = "<?= base_url() ?>"
    $(document).ready(function() {
        $(document).on('click', '.detail-waiting', function(e) {

            const dept_id = $('#filter').val();
            const filter_dept = $('#filter').val();

            if (filter_dept === 'all') {
                e.preventDefault();

                $('#modalMessage').text("Silakan pilih departemen terlebih dahulu.");

                var myModal = new bootstrap.Modal(document.getElementById('modalWarning'));
                myModal.show();

                return false;
            }

            $("#mesinof_waiting").modal("show");
            $("#detail_waiting").load("<?= base_url(); ?>instruksi/detail_waiting", {
                status: 2,
                dept_id: dept_id,
            });
        });
    });
    $(document).ready(function() {
        $(document).on('click', '.detail-progres', function(e) {

            const dept_id = $('#filter').val();
            const filter_dept = $('#filter').val();

            if (filter_dept === 'all') {
                e.preventDefault();

                $('#modalMessage').text("Silakan pilih departemen terlebih dahulu.");

                var myModal = new bootstrap.Modal(document.getElementById('modalWarning'));
                myModal.show();

                return false;
            }

            $("#mesinof_progres").modal("show");
            $("#detail_progres").load("<?= base_url(); ?>instruksi/detail_progres", {
                status: 0,
                dept_id: dept_id,
            });
        });
    });

    $(document).on('click', '.lihat-file', function() {

        let data = $(this).data('files');
        let html = '';

        if (data && data.paths && data.paths.length > 0) {

            data.paths.forEach(function(path, i) {
                let name = data.names && data.names[i] ? data.names[i] : 'File';

                html += `
            <div style="margin-bottom:10px">
                <p>${name}</p>
                <img src="${base_url + path}" 
                    style="max-width:100%; border:1px solid #ccc; padding:5px;">
            </div>
            `;
            });

        } else {
            html = '<span style="color:gray">Tidak ada file</span>';
        }

        $('#contentFile').html(html);
        $('#modalFile').modal('show');
    });
    $(document).on('click', '.status_cansel', function() {
        var url = $(this).data("url");
        $("#btn-okk").attr("href", url);
        $("#modal-status_cansel").modal("show");
    });
</script>


<script>
    $(document).ready(function() {
        var table = $('#instruksiTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "<?= base_url('instruksi/filter_report') ?>",
                type: "POST",
                data: function(d) {
                    d.dept_id = $('#filter').val();
                    d.tanggal = $('#filter_tanggal').val();
                    d.status = $('#filter_status').val();
                }
            },
            columns: [{
                    data: 'no'
                },
                {
                    data: 'nomesin'
                },
                {
                    data: 'instruksi'
                },
                {
                    data: 'mulai'
                },
                {
                    data: 'selesai'
                },
                {
                    data: 'status'
                },
                {
                    data: 'petugas'
                },

            ],
            drawCallback: function(settings) {

                simpanTimestamp();

                var json = settings.json;

                $('#total').text(json.recordsFiltered);
                $('#total_progres').text(json.total_progres);
                $('#total_selesai').text(json.total_selesai);
                $('#total_waiting').text(json.total_waiting);

                $('#total_all').text(json.total_all);
                $('#total_progres_all').text(json.total_progres_all);
                $('#total_selesai_all').text(json.total_selesai_all);
                $('#total_waiting_all').text(json.total_waiting_all);

                $('#tanggal_text').text(json.tanggal_aktif);
            }
        });

        $('#filter,#filter_tanggal,#filter_status').change(function() {
            table.ajax.reload();
        });


        let timerData = [];

        function simpanTimestamp() {
            timerData = [];
            $(".updateon").each(function() {
                const el = $(this);
                const startTimestamp = parseInt(el.data("start"));
                if (!isNaN(startTimestamp)) {
                    timerData.push({
                        el: el,
                        start: startTimestamp
                    });
                } else {
                    el.text("Waktu tidak valid");
                }
            });
        }


        setInterval(function() {
            const now = new Date().getTime();

            timerData.forEach(function(item) {
                const distance = now - item.start;

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                item.el.text(
                    days + " Hari, " +
                    hours + " Jam, " +
                    minutes + " Menit, " +
                    seconds + " Detik"
                );
            });
        }, 1000);
    });
</script>

<script>
    let growthChart;

    function loadGrowthChart() {

        const dept_id = $('#filter_departemen').val();
        const bulan = $('#filter_bulan').val();
        const tahun = $('#filter_tahun').val();

        $.ajax({
            url: "<?= base_url('instruksi/gi_terbanyak') ?>",
            type: "POST",
            dataType: "json",
            data: {
                dept_id: dept_id,
                bulan: bulan,
                tahun: tahun
            },


            success: function(res) {

                if (!res || res.length === 0) {
                    res = [{
                        user: '-',
                        total: 0
                    }];
                }

                let labels = res.map(item => item.user);
                let values = res.map(item => parseInt(item.total));

                const growthChartEl = document.querySelector('#kelChart');

                const growthChartOptions = {
                    series: [{
                        name: 'Jumlah GI',
                        data: values
                    }],

                    chart: {
                        height: 300,
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },

                    plotOptions: {
                        bar: {
                            borderRadius: 6,
                            columnWidth: '45%'
                        }
                    },

                    dataLabels: {
                        enabled: false
                    },

                    xaxis: {
                        categories: labels
                    },

                    yaxis: {
                        title: {
                            text: 'Total GI'
                        }
                    },

                    colors: ['#2196F3'],

                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " GI";
                            }
                        }
                    }
                };

                if (growthChart) {
                    growthChart.destroy();
                }

                growthChart = new ApexCharts(growthChartEl, growthChartOptions);
                growthChart.render();
            }
        });
    }


    $('#filter_departemen, #filter_bulan, #filter_tahun').on('change', function() {
        loadGrowthChart();
    });


    loadGrowthChart();
</script>
<script>
    $(document).ready(function() {

        var table = $('#PetugasTable').DataTable({

            processing: true,
            serverSide: true,
            stateSave: true,

            ajax: {
                url: "<?= base_url('instruksi/filter_petugas') ?>",
                type: "POST",

                data: function(d) {
                    d.dept_id = $('#filter_departemen').val();
                    d.bulan = $('#filter_bulan').val();
                    d.tahun = $('#filter_tahun').val();
                },

                dataSrc: function(json) {

                    $('#total').text(json.recordsFiltered);

                    $('#total_gm').text(json.total_gm);
                    $('#total_gb').text(json.total_gb);
                    $('#total_gi').text(json.total_gi);
                    $('#total_semua').text(json.total_semua);

                    return json.data;
                }
            },

            columns: [{
                    data: 'no',
                    className: 'text-center'
                },
                {
                    data: 'nama',
                    className: 'text-left'
                },
                {
                    data: 'gm',
                    className: 'text-center'
                },
                {
                    data: 'gb',
                    className: 'text-center'
                },
                {
                    data: 'gi',
                    className: 'text-center'
                },
                {
                    data: 'total',
                    className: 'text-center'
                }
            ]
        });

        $('#filter_departemen,#filter_bulan,#filter_tahun').change(function() {
            table.ajax.reload();
        });

    });

    $(document).ready(function() {
        $(document).on('click', '.detail-gi', function(e) {

            const dept_id = $('#filter_departemen').val();
            const bulan = $('#filter_bulan').val();
            const tahun = $('#filter_tahun').val();

            if (dept_id === 'all') {
                e.preventDefault();

                $('#modalMessage').text("Silakan pilih departemen terlebih dahulu.");

                var myModal = new bootstrap.Modal(document.getElementById('modalWarning'));
                myModal.show();

                return false;
            }

            $("#ganti_meai").modal("show");
            $("#detail_instruksi").load("<?= base_url(); ?>instruksi/detail_gi", {
                dept_id: dept_id,
                bulan: bulan,
                tahun: tahun,
            });
        });
    });
    $(document).ready(function() {
        $(document).on('click', '.detail-gb', function(e) {

            const dept_id = $('#filter_departemen').val();
            const bulan = $('#filter_bulan').val();
            const tahun = $('#filter_tahun').val();

            if (dept_id === 'all') {
                e.preventDefault();

                $('#modalMessage').text("Silakan pilih departemen terlebih dahulu.");

                var myModal = new bootstrap.Modal(document.getElementById('modalWarning'));
                myModal.show();

                return false;
            }

            $("#ganti_bahan").modal("show");
            $("#detail_gb").load("<?= base_url(); ?>instruksi/detail_gb", {
                dept_id: dept_id,
                bulan: bulan,
                tahun: tahun,
            });
        });
    });
    $(document).ready(function() {
        $(document).on('click', '.detail-gm', function(e) {

            const dept_id = $('#filter_departemen').val();
            const bulan = $('#filter_bulan').val();
            const tahun = $('#filter_tahun').val();

            if (dept_id === 'all') {
                e.preventDefault();

                $('#modalMessage').text("Silakan pilih departemen terlebih dahulu.");

                var myModal = new bootstrap.Modal(document.getElementById('modalWarning'));
                myModal.show();

                return false;
            }

            $("#ganti_kakesu").modal("show");
            $("#detail_gm").load("<?= base_url(); ?>instruksi/detail_gm", {
                dept_id: dept_id,
                bulan: bulan,
                tahun: tahun,
            });
        });
    });
</script>