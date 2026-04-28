<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row ">
        <div class="col-lg-12 ">
            <div class="card h-100">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-title mb-0">
                                <h5 class="mb-1 me-2"><?= $title; ?></h5>
                            </div>
                        </div>
                        <div class="col-lg-6" style="text-align: right;">
                            <a href="<?= base_url('dashboard'); ?>" class="btn btn-sm btn-warning"> <i class="fa fa-arrow-left me-2"></i> Kembali</a>
                        </div>
                    </div>
                </div>


                <div class="card-body font-kecil ">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label class="font-kecil font-bold text-azure text-primary" st>Bulan</label>
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
                                    <label class="font-kecil font-bold text-azure text-primary">Tahun</label>
                                    <select name="filter_tahun" id="filter_tahun" class="form-select font-kecil mt-0">
                                        <option value="all" <?= $filter_tahun == 'all' ? 'selected' : '' ?>>Semua Tahun</option>
                                        <?php foreach ($tahun_options as $th) : ?>
                                            <option value="<?= $th['tahun']; ?>" <?= ($filter_tahun == $th['tahun'] || ($filter_tahun == 'all' && $thn_sekarang == $th['tahun'])) ? 'selected' : '' ?>>
                                                <?= $th['tahun']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-5">
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
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-6">
                                <div id="myCustomChart"></div>

                            </div>
                        </div>
                        <div class="col-lg-5">
                            <ul id="list-perbaikan-terbanyak" class="p-0 m-0"></ul>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="nav-align-top">
                            <ul class="nav nav-pills mb-4 nav-fill" role="tablist">
                                <li class="nav-item mb-1 mb-sm-0">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-profile" aria-controls="navs-pills-justified-profile" aria-selected="true">
                                        <span class="d-none d-sm-inline-flex align-items-center">
                                            <i class="icon-base bx bx-user icon-sm me-1_5"></i>Data Perbaikan Teknisi
                                        </span>
                                        <i class="icon-base bx bx-user icon-sm d-sm-none"></i>
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-messages" aria-controls="navs-pills-justified-messages" aria-selected="false">
                                        <span class="d-none d-sm-inline-flex align-items-center">
                                            <i class="fa-solid fa-gear me-2"></i>Data Riwayat Kerusakan Mesin
                                        </span>
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="navs-pills-justified-profile" role="tabpanel">
                                    <div class="col-lg-8">
                                        <div class="table-responsive">
                                            <table id="reportTable" class="tabel datatable">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Teknisi</th>
                                                        <th>Departemen</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="navs-pills-justified-messages" role="tabpanel">
                                    <div class="col-10">
                                        <div class="table-responsive">
                                            <table id="mesinTable" class="tabel datatable">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Mesin</th>
                                                        <th>Spek Mesin</th>
                                                        <th>Departemen</th>
                                                        <th>Akasi</th>
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

                </div>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas offcanvas-start" tabindex="-1" id="modal-view" aria-labelledby=" offcanvasExampleLabel" style="width: 50%;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Downtime Mesin</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div id="loadformview-sub"></div>
    </div>
</div>
<!-- / Content -->
<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>

<!-- ajax -->
<script>
    function renderChart(data) {
        const chartElement = document.querySelector('#myCustomChart');

        if (window.statisticsChart) {
            window.statisticsChart.destroy();
        }

        const labels = data.map(item => "Teknisi " + item.user);
        const series = data.map(item => parseInt(item.total_perbaikan));

        const orderChartConfig = {
            chart: {
                height: 200,
                type: 'donut',
                offsetX: 15
            },
            labels: labels,
            series: series,
            colors: ['#28C76F', '#00cfe8', '#FF9F43', '#7367F0', '#EA5455'],
            stroke: {
                width: 5,
                colors: ['#fff']
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: {
                                fontSize: '14px'
                            },
                            value: {
                                fontSize: '18px',
                                formatter: function(val) {
                                    return parseInt(val);
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function(w) {
                                    let total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return total + '';
                                }
                            }
                        }
                    }
                }
            }
        };

        window.statisticsChart = new ApexCharts(chartElement, orderChartConfig);
        window.statisticsChart.render();
    }
    $(document).ready(function() {
        function loadPerbaikanTerbanyak() {
            const dept_id = $('#filter').val();
            const bulan = $('#filter_bulan').val();
            const tahun = $('#filter_tahun').val();

            $.ajax({
                url: "<?= base_url('report/getPerbaikan_Terbanyak') ?>",
                type: "POST",
                data: {
                    dept_id,
                    bulan,
                    tahun
                },
                success: function(response) {
                    const data = JSON.parse(response);

                    // Perbarui daftar
                    let html = '';
                    data.forEach(row => {
                        html += `
            <li class="d-flex align-items-center mb-4">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-primary"><i class="fa-solid fa-user"></i></i></span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0"> ${row.user}</h6>
                </div>
                <div class="user-progress">
                  <h6 class="mb-0 " style=" color:red ;">${row.total_perbaikan}x Memperbaiki</h6>
                </div>
              </div>
            </li>`;
                    });
                    $('#list-perbaikan-terbanyak').html(html);


                    // Update Chart
                    renderChart(data);
                }
            });
        }

        $('#filter, #filter_bulan, #filter_tahun').on('change', function() {
            loadPerbaikanTerbanyak();
        });

        loadPerbaikanTerbanyak(); // Load awal
    });

    $(document).ready(function() {

        var table = $('#reportTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "<?= base_url('report/filter_report') ?>",
                type: "POST",
                data: function(d) {
                    d.dept_id = $('#filter').val();
                }
            },
            columns: [{
                    data: 'no',
                    className: 'text-start'
                },
                {
                    data: 'nama',
                    className: 'text-start'
                },
                {
                    data: 'departemen',
                    className: 'text-start'
                }
            ]

        });


        $('#filter').change(function() {
            table.ajax.reload();
        });
    });

    $(document).ready(function() {

        var table = $('#mesinTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "<?= base_url('report/filter_mesin') ?>",
                type: "POST",
                data: function(d) {
                    d.dept_id = $('#filter').val();
                }
            },
            columns: [{
                    data: 'no',
                    className: 'text-start'
                },
                {
                    data: 'mesin',
                    className: 'text-start'
                },
                {
                    data: 'spek mesin',
                    className: 'text-start'
                },
                {
                    data: 'departemen',
                    className: 'text-start'
                },
                {
                    data: 'aksi',
                    className: 'text-start'
                }
            ]

        });


        $('#filter').change(function() {
            table.ajax.reload();
        });
    });

    $(document).on("click", ".view", function() {
        var data = $(this).data("id");
        if (data) {
            $("#modal-view").offcanvas("show");
            $("#loadformview-sub").load("<?= base_url(); ?>report/view_kerusakan/" + encodeURIComponent(data));
        } else {
            alert("Data tanggal tidak valid!");
        }
    });
</script>