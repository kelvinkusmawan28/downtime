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
                    <div class="card-title mb-0">
                        <h5 class="mb-1 me-2"><?= $title; ?></h5>
                    </div>

                </div>
                <div class="card-body font-kecil ">
                    <div class="container mt-4">
                        <div class="row">

                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card detail-waiting" style="cursor: pointer;">
                                    <div class="stat-icon bg-waiting"><i class="fas fa-clock"></i></div>
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

                    <br>
                    <div class="col-lg-12" style=" padding:5px;">
                        <div class="row" style="padding:10px;">
                            <div class="col-lg-2 mb-2">
                                <label class="font-kecil font-bold text-primary">Tanggal</label>
                                <input type="date" id="filter_tanggal" name="filter_tanggal" class="form-control font-kecil" value="<?= !empty($filter_tanggal) ? $filter_tanggal : $tgl_sekarang; ?>">
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
                        <span id="tanggal_text" style="margin-left: 10px;"></span>
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

                    <hr>
                    <div class="table-responsive">
                        <table id="instruksiTable" class="tabel table-bordered">
                            <thead>
                                <tr>
                                    <th style="background-color: #D2F4F2; color: black;">No</th>
                                    <th style="background-color: #D2F4F2; color: black;">Mesin</th>
                                    <th style="background-color: #D2F4F2; color: black;">Jenis Instruksi</th>
                                    <th style="background-color: #D2F4F2; color: black;">Mulai</th>
                                    <th style="background-color: #D2F4F2; color: black;">Selesai</th>
                                    <th style="background-color: #D2F4F2; color: black;">Status</th>
                                    <th style="background-color: #D2F4F2; color: black;">Petugas</th>

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
        <div class="modal-content ">
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

<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>
<!-- to filter form -->
<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery-ui.min.js"></script>
<script>
    const base_url = "<?= base_url() ?>";
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