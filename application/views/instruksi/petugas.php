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
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="table-responsive">
                                <table id="PetugasTable" class="tabel table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #81912F; color: black;">No</th>
                                            <th style="background-color: #81912F; color: black;">Nama Petugas</th>
                                            <th style="background-color: #81912F; color: black;">GM</th>
                                            <th style="background-color: #81912F; color: black;">GB</th>
                                            <th style="background-color: #81912F; color: black;">GI</th>
                                            <th style="background-color: #81912F; color: black;">Total</th>
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
<!-- / Content -->

<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>
<!-- to filter form -->
<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery-ui.min.js"></script>

<script>
    let growthChart;

    function loadGrowthChart() {

        const dept_id = $('#filter').val();
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

            // success: function(res) {

            //     if (!res || res.length === 0) {
            //         res = [{
            //             user: '-',
            //             total: 0
            //         }];
            //     }

            //     // =========================
            //     // GROUP BY NILAI TOTAL
            //     // =========================
            //     let grouped = {};

            //     res.forEach(item => {

            //         let key = item.total;

            //         if (!grouped[key]) {
            //             grouped[key] = {
            //                 users: [],
            //                 total: parseInt(item.total)
            //             };
            //         }

            //         grouped[key].users.push(item.user);
            //     });

            //     let labels = [];
            //     let values = [];

            //     Object.values(grouped).forEach(group => {
            //         labels.push(group.users.join(' & ')); // gabung nama
            //         values.push(group.total);
            //     });

            //     const growthChartEl = document.querySelector('#kelChart');

            //     const growthChartOptions = {
            //         series: [{
            //             name: 'Jumlah GI',
            //             data: values
            //         }],

            //         chart: {
            //             height: 300,
            //             type: 'bar',
            //             toolbar: {
            //                 show: false
            //             }
            //         },

            //         plotOptions: {
            //             bar: {
            //                 borderRadius: 6,
            //                 columnWidth: '45%'
            //             }
            //         },

            //         dataLabels: {
            //             enabled: false
            //         },

            //         xaxis: {
            //             categories: labels
            //         },

            //         yaxis: {
            //             title: {
            //                 text: 'Total GI'
            //             }
            //         },

            //         colors: ['#696cff'],

            //         tooltip: {
            //             y: {
            //                 formatter: function(val) {
            //                     return val + " GI";
            //                 }
            //             }
            //         }
            //     };

            //     if (growthChart) {
            //         growthChart.destroy();
            //     }

            //     growthChart = new ApexCharts(growthChartEl, growthChartOptions);
            //     growthChart.render();
            // }
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

                    colors: ['#81912F'],

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


    $('#filter, #filter_bulan, #filter_tahun').on('change', function() {
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
                    d.dept_id = $('#filter').val();
                    d.bulan = $('#filter_bulan').val();
                    d.tahun = $('#filter_tahun').val();
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
                },
            ]

        });

        $('#filter,#filter_bulan,#filter_tahun').change(function() {
            table.ajax.reload();
        });

    });
</script>