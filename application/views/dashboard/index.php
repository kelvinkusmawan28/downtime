<style>
  .card.edit {
    cursor: pointer;
  }

  .card.edit:hover {
    transform: scale(1.05);
    transition: 0.2s;
  }
</style>
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-xxl-12 mb-6 order-0">
      <div class="d-flex align-items-start row">
        <div class="row g-4">
          <?php if ($this->session->userdata('cekdowntime_pi') == 1) : ?>
            <div class="col-sm-6 col-lg-3">
              <a href="<?= base_url('mesin'); ?>" class="text-decoration-none">
                <div class="card h-100 shadow-sm custom-card" style="border-radius: 20px; background: linear-gradient(145deg, #ffffff, #f0f4f8);">
                  <div class="card-body p-4 d-flex flex-column align-items-center text-center">

                    <div class="icon-wrapper mb-3 d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 70px; background: linear-gradient(45deg, #0d6efd, #0046af); border-radius: 18px;">
                      <i class="fa-solid fa-screwdriver-wrench fs-2 text-white"></i>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">Perbaikan Mesin</h5>
                    <p class="text-muted small mb-4">Akses Modul laporan kerusakan.</p>

                    <div class="mt-auto w-100">
                      <span class="btn btn-outline-primary w-100 btn-custom">
                        Buka Menu <i class="fa-solid fa-arrow-right ms-2"></i>
                      </span>
                    </div>

                  </div>
                </div>
              </a>
            </div>
          <?php endif; ?>
          <?php if ($this->session->userdata('cekdowntime_gi') == 1) : ?>
            <div class="col-sm-6 col-lg-3">
              <a href="<?= base_url('instruksi'); ?>" class="text-decoration-none">
                <div class="card h-100 shadow-sm custom-card" style="border-radius: 20px; background: linear-gradient(145deg, #ffffff, #eefcfe);">
                  <div class="card-body p-4 d-flex flex-column align-items-center text-center">

                    <div class="icon-wrapper mb-3 d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 70px; background: linear-gradient(45deg, #0dcaf0, #0aa2c0); border-radius: 18px;">
                      <i class="fa-solid fa-clipboard-list fs-2 text-white"></i>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">Ganti Instruksi</h5>
                    <p class="text-muted small mb-4">Akses Modul instruksi Mesin.</p>

                    <div class="mt-auto w-100">
                      <span class="btn btn-outline-info w-100 btn-custom">
                        Buka Menu <i class="fa-solid fa-arrow-right ms-2"></i>
                      </span>
                    </div>

                  </div>
                </div>
              </a>
            </div>
          <?php endif ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-12 ">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title text-info mb-3"> 📌 <?= $title; ?></h5>


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
            <ul id="list-mesin-terbanyak" class="p-0 m-0"></ul>
            </ul>
          </div>
        </div>


      </div>
      <div class="col-lg-12 ">
        <div class="card h-100">
          <div class="card-body">
            <div class="row mb-5">
              <div class="row">
                <div class="col-lg-6">
                  <h5 class="text-danger">MESIN STOP</h5>
                </div>
                <?php if ($this->session->userdata('cekdowntime') == 1) : ?>
                  <div class="col-lg-6 " style="text-align: right;">
                    <a href="<?= base_url('dashboard/grafik'); ?>" class="btn btn-sm btn-primary text-white"><i class="fa fa-box-archive me-2"></i>Rekapulasi Data</a>
                  </div>
                <?php endif ?>
              </div>

              <div class="col-lg-2 mb-2">
                <label class="font-kecil font-bold text-primary">Tanggal</label>
                <input type="date" id="filter_tanggal" name="filter_tanggal" class="form-control font-kecil" value="<?= $tgl_sekarang; ?>">
              </div>
              <div class="col-lg-2 mb-2">
                <label class="font-kecil font-bold text-primary">Shift</label>
                <select name="shift" id="shift" class="form-control font-kecil">
                  <option value="1" <?= $shift == 1  ? 'selected' : '' ?>>PAGI</option>
                  <option value="2" <?= $shift == 2  ? 'selected' : '' ?>>SIANG</option>
                  <option value="3" <?= $shift == 3  ? 'selected' : '' ?>>MALAM</option>
                </select>
              </div>
              <div class="col-lg-2 mb-2">
                <label class="font-kecil font-bold text-primary">Reason</label>
                <select name="reason" id="reason" class="form-control font-kecil">
                  <option value="all">Semua</option>
                  <?php foreach ($reason as $data) : ?>
                    <option value="<?= $data['id']; ?>"><?= $data['reason']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-lg-2 mb-2" id="filter_rr_box">
                <label class="font-kecil font-bold text-primary">Type Mesin RR</label>
                <select name="filter_rr" id="filter_rr" class="form-control font-kecil">
                  <option value="all">Semua</option>
                  <?php foreach ($rr_type as $data) : ?>
                    <option value="<?= $data['preventif_kode']; ?>">
                      <?= $data['preventif_kode']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-lg-2 mb-2" id="filter_sp_box">
                <label class="font-kecil font-bold text-primary">Type Mesin SP</label>
                <select name="filter_sp" id="filter_sp" class="form-control font-kecil">
                  <option value="all">Semua</option>
                  <?php foreach ($sp_type as $key) : ?>
                    <option value="<?= $key['preventif_kode']; ?>">
                      <?= $key['preventif_kode']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-lg-2 mb-2" id="filter_nt_box">
                <label class="font-kecil font-bold text-primary">Type Mesin NT</label>
                <select name="filter_nt" id="filter_nt" class="form-control font-kecil">
                  <option value="all">Semua</option>
                  <?php foreach ($nt_type as $key) : ?>
                    <option value="<?= $key['preventif_kode']; ?>">
                      <?= $key['preventif_kode']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-lg-2 mb-2" id="filter_fn_box">
                <label class="font-kecil font-bold text-primary">Type Mesin FN</label>
                <select name="filter_fn" id="filter_fn" class="form-control font-kecil">
                  <option value="all">Semua</option>
                  <?php foreach ($fn_type as $key) : ?>
                    <option value="<?= $key['preventif_kode']; ?>">
                      <?= $key['preventif_kode']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-lg-2 mb-2" id="filter_rc_box">
                <label class="font-kecil font-bold text-primary">Type Mesin Aroza</label>
                <select name="filter_rc" id="filter_rc" class="form-control font-kecil">
                  <option value="all">Semua</option>
                  <option value="0">Netting</option>
                  <option value="1">Raschel</option>
                </select>
              </div>
            </div>
            <div class=" row mt-3">
              <div class="col-lg-11" style="border-right: 1px solid grey;">
                <div class="row" id="list_mesin"> </div>
              </div>
              <div class="col-lg-1">
                <p class="text-danger" style="font-size: 11px;">Total Mesin: <span id="total_mesin">0</span></p>
                <div id="summary_clr"></div>
              </div>
            </div>
            <div id="last_update" style="font-size:12px; margin-top:10px;">
              Last Update: -
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- / Content -->

<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div id="loadforminput"></div>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="basicModal-edit" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="loadforminput-edit"></div>

      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="mesinof_detail" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="detail_mesin"></div>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="basicModal-detail" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Riwayat Kerusakan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="loadforminput-detail"></div>
      </div>
      <div class="modal-footer">

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
<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery-ui.min.js"></script>

<script>
  function renderChart(data) {
    const chartElement = document.querySelector('#myCustomChart');

    if (window.statisticsChart) {
      window.statisticsChart.destroy();
    }

    const labels = data.map(item => "Mesin " + item.mach_no);
    const series = data.map(item => parseInt(item.total_downtime));

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
    function toggleFilterBox() {
      let dept = $("#filter").val();

      $("#filter_rr_box").toggle(dept == "RR");
      $("#filter_sp_box").toggle(dept == "SP");
      $("#filter_nt_box").toggle(dept == "NT");
      $("#filter_fn_box").toggle(dept == "FN");
      $("#filter_rc_box").toggle(dept == "AR");
    }

    $("#filter").change(toggleFilterBox);
    toggleFilterBox()

    function loadMesin() {

      let dept = $("#filter").val();
      let tanggal = $("#filter_tanggal").val();
      let shift = $("#shift").val();
      let reason = $("#reason").val();
      let filter_rr = $("#filter_rr").val();
      let filter_nt = $("#filter_nt").val();
      let filter_sp = $("#filter_sp").val();
      let filter_fn = $("#filter_fn").val();
      let filter_rc = $("#filter_rc").val();

      if (dept != "AR") {
        filter_rc = "all";
        $("#filter_rc").val("all");
      }
      if (dept != "RR") {
        filter_rr = "all";
        $("#filter_rr").val("all");
      }
      if (dept != "NT") {
        filter_nt = "all";
        $("#filter_nt").val("NT-NET");
      }
      if (dept != "SP") {
        filter_sp = "all";
        $("#filter_sp").val("all");
      }
      if (dept != "FN") {
        filter_fn = "all";
        $("#filter_fn").val("all");
      }
      console.log("DATA YANG DIKIRIM:");
      console.log("dept:", dept);
      console.log("tanggal:", tanggal);
      console.log("shift:", shift);
      console.log("reason:", reason);
      console.log("filter_rr:", filter_rr);
      console.log("filter_nt:", filter_nt);
      console.log("filter_sp:", filter_sp);
      console.log("filter_rc:", filter_rc);
      console.log("filter_fn:", filter_fn);

      $.ajax({
        url: "<?= base_url('dashboard/getMesinByDept') ?>",
        type: "POST",
        data: {
          dept: dept,
          tanggal: tanggal,
          shift: shift,
          reason: reason,
          filter_rr: filter_rr,
          filter_nt: filter_nt,
          filter_sp: filter_sp,
          filter_fn: filter_fn,
          filter_rc: filter_rc
        },
        dataType: "json",
        success: function(response) {

          let html = '';

          // loop mesin
          $.each(response.mesin, function(i, mesin) {
            let warna = "#eeeeee";

            if (mesin.clr != null) {
              warna = "rgb(" + mesin.clr + ")";
            }

            if (mesin.preventif_kode == "RR-RING") {

              html += `
              <div class="col-3 col-sm-2 col-md-2 col-lg-1 mb-2">
                <div class="card text-center p-1">

                  <div style="font-weight:bold;font-size:12px">
                    Ms. ${mesin.mach_no} <br>
                    <span style="font-size: 7px; color: black; display: block; background-color: #eeeeee; padding: 1px 1px; ">
                      ${mesin.name}
                  </span>
                  </div>

                  <div style="display:flex;margin-top:3px">

                    <div class="sisi-kiri edit"
                      data-mach="${mesin.mach_id}"
                      data-id="${mesin.id}"
                      data-posisi="L"
                      title="Mesin ${mesin.mach_no} (${mesin.mach_name})\n${mesin.reason_left ?? ''}${mesin.jenis_left ? ' - ' + mesin.jenis_left : ''}"
                      style="
                      flex:1;
                      padding:4px;
                      background:${mesin.left_color};
                      font-size:11px;
                      cursor:pointer;">
                      L
                    </div>

                    <div class="sisi-kanan edit"
                      data-mach="${mesin.mach_id}"
                      data-id="${mesin.id}"
                      data-posisi="R"
                      title="Mesin ${mesin.mach_no} (${mesin.mach_name})\n${mesin.reason_right ?? ''}${mesin.jenis_right ? ' - ' + mesin.jenis_right : ''}"
                      style="
                      flex:1;
                      padding:4px;
                      background:${mesin.right_color};
                      font-size:11px;
                      cursor:pointer;">
                      R
                    </div>

                  </div>
                </div>
              </div>
              `;

            } else {

              html += `
              <div class="col-3 col-sm-2 col-md-2 col-lg-1 mb-2">
               

                <div class="card text-center edit d-flex flex-column justify-content-center" 
                  style="background:${warna}; min-height: 50px; height: auto;" 
                  data-mach="${mesin.mach_id}"
                  data-id="${mesin.id}"
                  title="Mesin ${mesin.mach_no} (${mesin.mach_name})\n${mesin.reason_left ?? ''}${mesin.jenis_left ? ' - ' + mesin.jenis_left : ''}">
                  
                  <div class="card-body p-1"> <b class='text-dark' style="line-height: 2; display: block; font-size: 14px;">
                      ${mesin.mach_no}
                    </b>
                    <span style="font-size: 7px; color: black; display: block; background-color: #eeeeee; padding: 1px 1px; ">
                        ${mesin.name ? mesin.name : '' }
                    </span>
              
                  </div>
               </div>
              </div>
                `;
            }

          });

          $("#list_mesin").html(html);

          $("#total_mesin").text(response.mesin.length);



          // SUMMARY CLR


          let summaryHtml = '';

          $.each(response.summary, function(i, row) {
            let warna = "#eeeeee";

            if (row.clr != null) {
              warna = "rgb(" + row.clr + ")";
            }

            let total = row.total;

            if (total % 1 === 0) {
              total = parseInt(total);
            }
            summaryHtml += `
            <div style="display:flex;align-items:center;margin-bottom:5px">
              
              <div class="detail_mesinof"data-code="${row.code}"
              style="
                width:40px;
                height:20px;
                background:${warna};
                margin-right:5px;
                color:#000;
                font-size:12px;
                display:flex;
                align-items:center;
                justify-content:center;
                cursor:pointer;
              ">
              ${row.code ? row.code : 'OK'}
              </div>

              ${total}

            </div>
            `;

          });

          $("#summary_clr").html(summaryHtml);


          //  LAST UPDATE
          let last = response.lastupdate;

          if (last && last.ket_on) {

            let tanggal = new Date(last.ket_on);

            let format = tanggal.toLocaleString('id-ID', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
              hour: '2-digit',
              minute: '2-digit'
            });

            $("#last_update").text("Last Update: " + format);

          } else {
            $("#last_update").text("Last Update: -");
          }

        }

      });

    }

    $("#filter,#filter_tanggal,#shift,#reason,#filter_rr,#filter_nt,#filter_rc,#filter_sp,#filter_fn").change(function() {
      loadMesin();
    });

    loadMesin();

  });
</script>

<script>
  $(document).ready(function() {
    $('#basicModal').on('hidden.bs.modal', function() {
      $('#loadforminput').html("");
    });

    $('#basicModal-edit').on('hidden.bs.modal', function() {
      $('#loadforminput-edit').html("");
    });


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

    $(document).on('click', '.detail_mesinof', function(e) {
      const code = $(this).data('code');
      const dept_id = $('#filter').val();
      const tanggal = $("#filter_tanggal").val();

      const filter_dept = $('#filter').val();

      if (filter_dept === 'all') {
        e.preventDefault();

        $('#modalMessage').text("Silakan pilih departemen terlebih dahulu.");

        var myModal = new bootstrap.Modal(document.getElementById('modalWarning'));
        myModal.show();

        return false;
      }

      $("#mesinof_detail").modal("show");
      $("#detail_mesin").load("<?= base_url(); ?>dashboard/detail_mesinof", {
        code: code,
        dept_id: dept_id,
        tanggal: tanggal
      });
    });

    $(document).on('click', '.edit', function(e) {
      e.preventDefault();

      var data = $(this).data("id");

      const filter_dept = $('#filter').val();

      if (filter_dept === 'all') {
        e.preventDefault();

        $('#modalMessage').text("Silakan pilih departemen terlebih dahulu.");

        var myModal = new bootstrap.Modal(document.getElementById('modalWarning'));
        myModal.show();

        return false;
      }

      if (data) {
        $("#loadforminput-edit").html("");
        $("#basicModal-edit").modal("show");
        $("#loadforminput-edit").load("<?= base_url(); ?>dashboard/edit/" + encodeURIComponent(data), function() {
          $("#ketof").autocomplete({

            source: function(request, response) {

              $.ajax({
                url: "<?= base_url('dashboard/ket_mesinof'); ?>",
                dataType: "json",
                data: {
                  term: request.term,
                  filter: $('#filter').val()
                },

                success: function(data) {

                  response($.map(data, function(item) {

                    return {
                      label: item.code + " - " + item.reason,
                      value: item.code + " - " + item.reason,
                      id: item.id
                    };

                  }));

                }
              });

            },

            select: function(event, ui) {

              $("#ket_id").val(ui.item.id);

            },

            change: function(event, ui) {

              if (!ui.item) {
                $("#ket_id").val("");
              }

            }

          });

          $("#ketof").on("input", function() {

            if ($(this).val() == "") {
              $("#ket_id").val("");
            }

          });

          $("#ketof_r").autocomplete({

            source: function(request, response) {

              $.ajax({
                url: "<?= base_url('dashboard/ket_mesinof'); ?>",
                dataType: "json",
                data: {
                  term: request.term,
                  filter: $('#filter').val()
                },

                success: function(data) {

                  response($.map(data, function(item) {

                    return {
                      label: item.code + " - " + item.reason,
                      value: item.code + " - " + item.reason,
                      id: item.id
                    };

                  }));

                }
              });

            },

            select: function(event, ui) {

              $("#ket_id_r").val(ui.item.id);

            },

            change: function(event, ui) {

              if (!ui.item) {
                $("#ket_id_r").val("");
              }

            }

          });

          $("#ketof_r").on("input", function() {

            if ($(this).val() == "") {
              $("#ket_id_r").val("");
            }

          });

          $("#ket_tb").autocomplete({

            source: function(request, response) {

              $.ajax({
                url: "<?= base_url('dashboard/benang'); ?>",
                dataType: "json",
                data: {
                  term: request.term
                },

                success: function(data) {

                  response($.map(data, function(item) {

                    return {
                      label: item.jenis,
                      value: item.jenis,
                      id: item.id
                    };

                  }));

                }

              });

            },

            select: function(event, ui) {

              $("#id_benang").val(ui.item.id);

            },

            change: function(event, ui) {

              if (!ui.item) {
                $("#id_benang").val("");
              }

            },

            minLength: 1

          });

          $("#ket_tb").on("input", function() {

            if ($(this).val() == "") {
              $("#id_benang").val("");
            }

          });

          $("#ket_tb_r").autocomplete({

            source: function(request, response) {

              $.ajax({
                url: "<?= base_url('dashboard/benang'); ?>",
                dataType: "json",
                data: {
                  term: request.term
                },

                success: function(data) {

                  response($.map(data, function(item) {

                    return {
                      label: item.jenis,
                      value: item.jenis,
                      id: item.id
                    };

                  }));

                }

              });

            },

            select: function(event, ui) {

              $("#id_benang_r").val(ui.item.id);

            },

            change: function(event, ui) {

              if (!ui.item) {
                $("#id_benang_r").val("");
              }

            },

            minLength: 1

          });

          $("#ket_tb_r").on("input", function() {

            if ($(this).val() == "") {
              $("#id_benang_r").val("");
            }

          });


          $("#ket_bb").autocomplete({

            source: function(request, response) {

              $.ajax({
                url: "<?= base_url('dashboard/ket_bobin'); ?>",
                dataType: "json",
                data: {
                  term: request.term
                },

                success: function(data) {

                  response($.map(data, function(item) {

                    return {
                      label: item.spesifikasi,
                      value: item.spesifikasi,
                      id: item.id
                    };

                  }));

                }

              });

            },

            select: function(event, ui) {

              $("#id_bobin").val(ui.item.id);

            },

            change: function(event, ui) {

              if (!ui.item) {
                $("#id_bobin").val("");
              }

            },

            minLength: 1

          });

          $("#ket_bb").on("input", function() {

            if ($(this).val() == "") {
              $("#id_bobin").val("");
            }

          });

          $("#ket_bb_r").autocomplete({

            source: function(request, response) {

              $.ajax({
                url: "<?= base_url('dashboard/ket_bobin'); ?>",
                dataType: "json",
                data: {
                  term: request.term
                },

                success: function(data) {

                  response($.map(data, function(item) {

                    return {
                      label: item.spesifikasi,
                      value: item.spesifikasi,
                      id: item.id
                    };

                  }));

                }

              });

            },

            select: function(event, ui) {

              $("#id_bobin_r").val(ui.item.id);

            },

            change: function(event, ui) {

              if (!ui.item) {
                $("#id_bobin_r").val("");
              }

            },

            minLength: 1

          });

          $("#ket_bb_r").on("input", function() {

            if ($(this).val() == "") {
              $("#id_bobin_r").val("");
            }

          });

        });

      } else {


        const mach_id = $(this).data("mach");


        const url = "<?= base_url('dashboard/tambahdata/'); ?>" + mach_id;
        $("#loadforminput").html("");
        $("#basicModal").modal("show");
        $("#loadforminput").load(url, function() {

          $("#mach_id").val(mach_id);

          $("#ketof").autocomplete({
            source: function(request, response) {
              $.ajax({
                url: "<?= base_url('dashboard/ket_mesinof'); ?>",
                dataType: "json",
                data: {
                  term: request.term,
                  filter: $('#filter').val(),
                },
                success: function(data) {
                  response($.map(data, function(item) {
                    return {
                      label: item.code + " - " + item.reason,
                      value: item.code + " - " + item.reason,
                      id: item.id
                    };
                  }));
                }
              });
            },
            select: function(event, ui) {
              $("#id").val(ui.item.id);
            },
            minLength: 1
          });

          $("#ketof_r").autocomplete({
            source: function(request, response) {
              $.ajax({
                url: "<?= base_url('dashboard/ket_mesinof'); ?>",
                dataType: "json",
                data: {
                  term: request.term,
                  filter: $('#filter').val()
                },
                success: function(data) {
                  response($.map(data, function(item) {
                    return {
                      label: item.code + " - " + item.reason,
                      value: item.code + " - " + item.reason,
                      id: item.id
                    };
                  }));
                }
              });
            },
            select: function(event, ui) {
              $("#id_r").val(ui.item.id);
            },
            minLength: 1
          });

          $("#ket_tb").autocomplete({
            source: function(request, response) {
              $.ajax({
                url: "<?= base_url('dashboard/benang'); ?>",
                dataType: "json",
                data: {
                  term: request.term,
                },
                success: function(data) {
                  response($.map(data, function(item) {
                    return {
                      label: item.jenis,
                      value: item.jenis,
                      id: item.id
                    };
                  }));
                }
              });
            },
            select: function(event, ui) {
              $("#id_benang").val(ui.item.id);
            },
            minLength: 1
          });

          $("#ket_tb_r").autocomplete({
            source: function(request, response) {
              $.ajax({
                url: "<?= base_url('dashboard/benang'); ?>",
                dataType: "json",
                data: {
                  term: request.term,
                },
                success: function(data) {
                  response($.map(data, function(item) {
                    return {
                      label: item.jenis,
                      value: item.jenis,
                      id: item.id
                    };
                  }));
                }
              });
            },
            select: function(event, ui) {
              $("#id_benang_r").val(ui.item.id);
            },
            minLength: 1
          });
          $("#ket_bb").autocomplete({
            source: function(request, response) {
              $.ajax({
                url: "<?= base_url('dashboard/ket_bobin'); ?>",
                dataType: "json",
                data: {
                  term: request.term,
                  filter: $('#filter').val(),
                },
                success: function(data) {
                  response($.map(data, function(item) {
                    return {
                      label: item.spesifikasi,
                      value: item.spesifikasi,
                      id: item.id
                    };
                  }));
                }
              });
            },
            select: function(event, ui) {
              $("#id_bobin").val(ui.item.id);
            },
            minLength: 1
          });
          $("#ket_bb_r").autocomplete({
            source: function(request, response) {
              $.ajax({
                url: "<?= base_url('dashboard/ket_bobin'); ?>",
                dataType: "json",
                data: {
                  term: request.term,
                  filter: $('#filter').val(),
                },
                success: function(data) {
                  response($.map(data, function(item) {
                    return {
                      label: item.spesifikasi,
                      value: item.spesifikasi,
                      id: item.id
                    };
                  }));
                }
              });
            },
            select: function(event, ui) {
              $("#id_bobin_r").val(ui.item.id);
            },
            minLength: 1
          });

        });

      }

    });



  });
</script>