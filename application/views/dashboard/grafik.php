<div class="container-xxl flex-grow-1 container-p-y">
  <div class="col-lg-12 ">

    <div class="card h-100" style="background-image: url('<?= base_url('assets/img/backgrounds/bg-7.png') ?>');
     background-repeat: no-repeat;
       background-size: cover;
       background-position: center;">


      <div class="card-body">
        <div class="row">
          <div class="col-lg-6">
            <h5 class="card-title text-dark"> 📌 <?= $title; ?></h5>
          </div>
          <div class="col-lg-6" style="text-align:right;">
            <a href="<?= base_url('dashboard'); ?>" class="btn btn-sm btn-warning text-dark"> <i class="fa fa-arrow-left me-2"></i> Kembali</a>
          </div>
        </div>
      </div>
      <div class="row mt-3" style="padding: 10px;">
        <div class="col-lg-3">
          <label class="font-kecil font-bold text-dark" st>Bulan</label>
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
          <label class="font-kecil font-bold text-dark">Tahun</label>
          <select name="filter_tahun" id="filter_tahun" class="form-select font-kecil mt-0">
            <option value="all" <?= $filter_tahun == 'all' ? 'selected' : '' ?>>Semua Tahun</option>
            <?php foreach ($tahun_options as $th) : ?>
              <option value="<?= $th['tahun']; ?>" <?= ($filter_tahun == $th['tahun'] || ($filter_tahun == 'all' && $thn_sekarang == $th['tahun'])) ? 'selected' : '' ?>>
                <?= $th['tahun']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-lg-3">

          <label class="font-kecil font-bold text-dark">Departemen</label>

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

          <select name="filter_dept" id="filter_dept" class="form-select font-kecil mt-0">
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
        <div class="col-lg-3">
          <label class="font-kecil font-bold text-dark">Reason</label>
          <select name="reason" id="reason" class="form-control font-kecil">
            <option value="all">Semua</option>
            <?php foreach ($reason as $data) : ?>
              <option value="<?= $data['id']; ?>"><?= $data['reason']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <hr>



      <div class="row">
        <div id="Chart-kelvin"></div>
        <!-- <div id="incomeChart"></div> -->
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
        <h3>PERINGATAN</h3>
        <div class="text-danger" id="modalMessage"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>

    </div>
  </div>
</div>

<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>
<script>
  $(document).ready(function() {
    function loadGrafik() {
      const bulan = $('#filter_bulan').val();
      const tahun = $('#filter_tahun').val();
      const dept = $('#filter_dept').val();
      const reason = $('#reason').val();

      $.ajax({
        url: "<?= base_url('dashboard/line') ?>",
        type: "POST",
        data: {
          bulan,
          tahun,
          dept,
          reason
        },
        success: function(response) {
          const data = JSON.parse(response);
          renderChart(data);
        }
      });
    }

    let chart = null;

    // function renderChart(data) {
    //   const el = document.querySelector('#Chart-kelvin');

    //   // 🔥 destroy chart lama
    //   if (chart !== null) {
    //     chart.destroy();
    //   }

    //   const options = {
    //     series: [{
    //       name: 'jumlah ',
    //       data: data.jumlah
    //     }],
    //     chart: {
    //       type: 'area',
    //       height: 250
    //     },
    //     stroke: {
    //       curve: 'smooth',
    //       width: 3
    //     },
    //     dataLabels: {
    //       enabled: false
    //     },
    //     xaxis: {
    //       categories: data.tanggal,
    //       title: {
    //         text: 'Tanggal'
    //       }
    //     },
    //     colors: ['#696cff'],
    //     yaxis: {
    //       title: {
    //         text: 'Jumlah '
    //       }
    //     }
    //   };

    //   chart = new ApexCharts(el, options);
    //   chart.render();
    // }

    // pertanggal

    // function renderChart(data) {
    //   const el = document.querySelector('#Chart-kelvin');


    //   if (chart !== null) {
    //     chart.destroy();
    //   }

    //   const options = {
    //     series: data.series,

    //     chart: {
    //       type: 'area',
    //       height: 250,
    //       toolbar: {
    //         show: true,
    //         tools: {
    //           download: true
    //         }
    //       }
    //     },

    //     stroke: {
    //       curve: 'smooth',
    //       width: 3
    //     },

    //     dataLabels: {
    //       enabled: false
    //     },


    //     colors: data.colors,

    //     xaxis: {
    //       categories: data.tanggal,
    //       title: {
    //         text: 'Tanggal'
    //       }
    //     },

    //     yaxis: {
    //       title: {
    //         text: 'Jumlah'
    //       }
    //     },

    //     legend: {
    //       show: true
    //     },

    //     fill: {
    //       type: 'gradient',
    //       gradient: {
    //         shadeIntensity: 1,
    //         opacityFrom: 0.4,
    //         opacityTo: 0.1,
    //         stops: [0, 100]
    //       }
    //     },

    //     tooltip: {
    //       y: {
    //         formatter: function(val) {
    //           return val + " x Stop";
    //         }
    //       }
    //     }
    //   };

    //   chart = new ApexCharts(el, options);
    //   chart.render();
    // }



    function renderChart(data) {
      const el = document.querySelector('#Chart-kelvin');

      if (chart !== null) {
        chart.destroy();
      }

      const options = {
        series: data.series,

        chart: {
          type: 'area',
          height: 300,
          toolbar: {
            show: true
          }
        },

        stroke: {
          curve: 'smooth',
          width: 2
        },

        dataLabels: {
          enabled: false
        },

        colors: data.colors,

        xaxis: {
          categories: data.tanggal,
          title: {
            text: 'Tanggal / Shift',
            text: 'Rata-rata: ' + data.rata_rata
          },
          labels: {
            rotate: -45,
            style: {
              fontSize: '10px'
            }
          }
        },

        yaxis: {
          title: {
            text: 'Jumlah Stop',
          }
        },

        legend: {
          position: 'top'
        },

        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.4,
            opacityTo: 0.1,
            stops: [0, 100]
          }
        },

        tooltip: {
          x: {
            formatter: function(val) {
              return data.labelMap[val] || val;
            }
          },
          y: {
            formatter: function(val) {
              return val + " x Stop";
            }
          }
        }
      };

      chart = new ApexCharts(el, options);
      chart.render();
    }

    // Event filter
    $('#filter_bulan, #filter_tahun, #filter_dept, #reason').on('change', function() {
      loadGrafik();
    });

    loadGrafik(); // load pertama kali
  });
</script>