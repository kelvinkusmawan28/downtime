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
                                <div class="card-header ">
                                    <div class="row">
                                        <div class="col-6">
                                            <a id="btn-export-pdf" class="btn btn-light btn-sm" target="_blank">
                                                <span class=" ml-1 text-dark">Export To Pdf <i class="fa fa-file-pdf text-danger"></i></span>
                                            </a>
                                            <a id="btn-export-excel" class="btn btn-light btn-sm">
                                                <span class="ml-1 text-dark">Export To Excel <i class="fa fa-file-excel text-success"></i></span>
                                            </a>
                                        </div>
                                        <div class="col-6" style="text-align: right;">
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
                                                <div id="Chart-kelvin"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <ul id="list-mesin-terbanyak" class="p-0 m-0"></ul>
                                            </ul>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="table-responsive">
                                        <table id="downtimeTable" class="tabel table-bordered">
                                            <thead>
                                                <tr class="bg-warning text-dark">
                                                    <th>No</th>
                                                    <th>Tanggal</th>
                                                    <th>Departemen</th>
                                                    <th>Mesin</th>
                                                    <th>Kerusakan</th>
                                                    <th>Status</th>

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
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <label class="font-kecil font-bold text-azure text-primary" st>Bulan</label>
                                                    <select name="bulan" id="bulan" class="form-select font-kecil mt-0">
                                                        <option value="all" <?= $bulan == 'all' ? 'selected' : '' ?>>Semua Bulan</option>
                                                        <?php foreach ($bulan_option as $bl) : ?>
                                                            <?php if (!empty($bl['bulan']) && !empty($bl['nama_bulan'])) : ?>
                                                                <option value="<?= $bl['bulan']; ?>" <?= ($bulan == $bl['bulan'] || ($bulan == 'all' && $bln_sekarang == $bl['bulan'])) ? 'selected' : '' ?>>
                                                                    <?= $bl['nama_bulan']; ?>
                                                                </option>

                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </select>

                                                </div>
                                                <div class="col-lg-3">
                                                    <label class="font-kecil font-bold text-azure text-primary">Tahun</label>
                                                    <select name="tahun" id="tahun" class="form-select font-kecil mt-0">
                                                        <option value="all" <?= $tahun == 'all' ? 'selected' : '' ?>>Semua Tahun</option>
                                                        <?php foreach ($tahun_option as $th) : ?>
                                                            <option value="<?= $th['tahun']; ?>" <?= ($tahun == $th['tahun'] || ($tahun == 'all' && $thn_sekarang == $th['tahun'])) ? 'selected' : '' ?>>
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

                                                    <select name="dept" id="dept" class="form-select font-kecil mt-0">
                                                        <option value="all" <?= $dept == '' ? 'selected' : '' ?>>Semua Departemen</option>
                                                        <?php foreach ($dept_options as $option) : ?>
                                                            <?php if (in_array($option['dept_id'],  $akses_dept_diberi)) : ?>
                                                                <option value="<?= $option['dept_id']; ?>" <?= $dept == $option['dept_id'] ? 'selected' : ''  ?>>
                                                                    <?= $option['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $option['departemen']; ?>
                                                                </option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>

                                                    </select>
                                                </div>
                                            </div>

                                            <div id="chartkel"></div>


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
                                                            <table id="reportTable" class="tabel table-bordered">
                                                                <thead>
                                                                    <tr style="background-color: #e3f2fd; color: black;">
                                                                        <th>No</th>
                                                                        <th>Nama Teknisi</th>
                                                                        <th>Departemen</th>
                                                                        <th>Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="navs-pills-justified-messages" role="tabpanel">
                                                    <div class="col-12" style="text-align: right;">
                                                        <a id="btn-export-excel" class="btn btn-success btn-sm">
                                                            <i class="fa fa-file-excel me-2 text-dark"></i><span class="ml-1" style="color: black;">Export All To Excel</span>
                                                        </a>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="table-responsive">
                                                            <table id="mesinTable" class="tabel table-bordered">
                                                                <thead>
                                                                    <tr style="background-color: #e3f2fd; color: black;">
                                                                        <th>No</th>
                                                                        <th>Mesin</th>
                                                                        <th>Spek Mesin</th>
                                                                        <th>Departemen</th>
                                                                        <th>Aksi</th>
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
    function Chart(data) {
        const Element = document.querySelector('#chartkel');

        if (window.statisticsChart) {
            window.statisticsChart.destroy();
        }

        const labels = data.map(item => item.user);
        const series = data.map(item => parseInt(item.total_perbaikan) || 0);

        const kelvin = {
            chart: {
                height: 300,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },

            series: [{
                name: 'Total Perbaikan',
                data: series
            }],

            xaxis: {
                categories: labels,
                title: {
                    text: 'User'
                }
            },

            yaxis: {
                title: {
                    text: 'Total Perbaikan'
                },
                min: 0
            },

            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    borderRadius: 4,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },

            dataLabels: {
                enabled: true,
                offsetY: -20
            },

            colors: ['#7692FF'],

            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + ' perbaikan';
                    }
                }
            }
        };

        window.statistic = new ApexCharts(
            Element,
            kelvin
        );

        window.statistic.render();
    }
    $(document).ready(function() {
        function loadPerbaikanTerbanyak() {
            const dept_id = $('#dept').val();
            const bulan = $('#bulan').val();
            const tahun = $('#tahun').val();

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
                    Chart(data);
                }
            });
        }

        $('#dept, #bulan, #tahun').on('change', function() {
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
                    d.dept_id = $('#dept').val();
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
                },
                {
                    data: 'aksi',
                    className: 'text-start'
                },
            ]

        });


        $('#dept').change(function() {
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
                    d.dept_id = $('#dept').val();
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


        $('#dept').change(function() {
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

<script>
    function updateExportLinks() {
        const dept_id = $('#dept').val();
        const bulan = $('#bulan').val();
        const tahun = $('#tahun').val();

        $('#btn-export-excel').attr('href', `<?= base_url('report/export_excel_all') ?>?dept_id=${dept_id}&bulan=${bulan}&tahun=${tahun}`);

    }

    $('#dept, #bulan, #tahun').change(updateExportLinks);
    $(document).ready(updateExportLinks);
</script>

<!-- perbaikan -->
<script>
    $(document).ready(function() {
        $(document).on('click', '.detail', function() {
            const mesin = $(this).data('mesin');
            const kerusakan = $(this).data('kerusakan');
            const dept_id = $('#filter').val();
            const bulan = $('#filter_bulan').val();
            const tahun = $('#filter_tahun').val();

            $("#basicModal-detail").modal("show");
            $("#loadforminput-detail").load("<?= base_url(); ?>dashboard/detail", {
                mesin: mesin,
                kerusakan: kerusakan,
                dept_id: dept_id,
                bulan: bulan,
                tahun: tahun
            });
        });
    });
</script>


<script>
    function renderChart(data) {
        const chartElement = document.querySelector('#Chart-kelvin');

        if (window.statisticsChart) {
            window.statisticsChart.destroy();
        }

        const labels = data.map(item => "Mesin " + item.mach_no);
        const series = data.map(item => parseInt(item.total_downtime));

        const orderChartConfig = {
            chart: {
                height: 300,
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
                enabled: true
            },
            legend: {
                show: true
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '50%',
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
        function loadKerusakanTerbanyak() {
            const dept_id = $('#filter').val();
            const bulan = $('#filter_bulan').val();
            const tahun = $('#filter_tahun').val();

            $.ajax({
                url: "<?= base_url('dashboard/getKerusakan_Terbanyak') ?>",
                type: "POST",
                data: {
                    dept_id,
                    bulan,
                    tahun
                },
                success: function(response) {
                    const data = JSON.parse(response);


                    let html = '';
                    data.forEach(row => {
                        html += `
            <li class="d-flex align-items-center mb-4">
              <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-primary 
                detail"
                data-mesin="${row.nomesin_id}"
                data-kerusakan="${row.kerusakan_id}"
                style="cursor:pointer">
                <i class="icon-base bx bx-cog"></i>
              </span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">Mesin ${row.mach_no} (${row.mach_name}) </h6>
                  <small>${row.kerusakan} (${row.departemen})</small>
                </div>
                <div class="user-progress">
                  <h6 class="mb-0">${row.total_downtime}x</h6>
                </div>
              </div>
            </li>`;
                    });
                    $('#list-mesin-terbanyak').html(html);



                    renderChart(data);
                }
            });
        }

        $('#filter, #filter_bulan, #filter_tahun').on('change', function() {
            loadKerusakanTerbanyak();
        });

        loadKerusakanTerbanyak();
    });
</script>

<script>
    $(document).ready(function() {
        var table = $('#downtimeTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "<?= base_url('mesin/filter_perbaikan') ?>",
                type: "POST",
                data: function(d) {
                    d.dept_id = $('#filter').val();
                    d.status = $('#filter_status').val();
                    d.bulan = $('#filter_bulan').val();
                    d.tahun = $('#filter_tahun').val();
                    console.log('Status:', d.status);
                    console.log('bulan:', d.bulan);
                    console.log('tahun:', d.tahun);
                }
            },
            columns: [{
                    data: 'no'
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'departemen'
                },
                {
                    data: 'mesin'
                },
                {
                    data: 'kerusakan'
                },
                {
                    data: 'status'
                },

            ],
            drawCallback: function(settings) {

                simpanTimestamp();
            }
        });

        $('#filter,#filter_status,#filter_bulan,#filter_tahun').change(function() {
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
    function updateExportLinks() {
        const bulan = $('#filter_bulan').val();
        const tahun = $('#filter_tahun').val();

        $('#btn-export-excel').attr('href', `<?= base_url('mesin/export_excel') ?>?bulan=${bulan}&tahun=${tahun}`);
        $('#btn-export-pdf').attr('href', `<?= base_url('mesin/export_pdf') ?>?bulan=${bulan}&tahun=${tahun}`);
    }

    $('#filter_bulan, #filter_tahun').change(updateExportLinks);
    $(document).ready(updateExportLinks);
</script>