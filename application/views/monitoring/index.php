<style>
  @keyframes spin {
    from {
      transform: rotate(0deg);
    }

    to {
      transform: rotate(360deg);
    }
  }

  .rotating-gear {
    animation: spin 2s linear infinite;
    display: inline-block;
    color: #6c757d;
    font-size: 14px;

  }


  .mesin-card {
    min-height: auto !important;
    border-radius: 4px;

  }

  .mesin-no {
    font-size: 10;
    line-height: 1;
  }


  .header-container {
    background: #ffffff;
    border-bottom: 1px solid gray;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    padding: 15px 20px;
    border-radius: 5px;
  }

  .font-kecil {
    font-size: 0.85rem;
  }

  #jam {
    font-size: 1.1rem;
    color: black;
    font-variant-numeric: tabular-nums;
    font-weight: 700;

    letter-spacing: 1px;

  }

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

  .detail-progres {
    border-left: 1px solid #e3f2fd;
    border-bottom: 3px solid #e3f2fd;
  }

  .bg-waiting {
    background-color: #FEFD99;
    color: #777C6D;
  }

  .detail-waiting {
    border-left: 1px solid #FEFD99;
    border-bottom: 3px solid #FEFD99;
  }

  .bg-total {
    background-color: #D1D3D4;
    color: #EEEEEE;
  }

  .detail-total {
    border-left: 1px solid #D1D3D4;
    border-bottom: 3px solid #D1D3D4;
  }
</style>
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="col-lg-12 ">
    <div class="card h-100 ">
      <div class="card-body">
        <div class="row header-container align-items-center">
          <div class="col-lg-8">
            <h5 class="card-title text-dark mb-0">
              <span class="me-2">📌</span><?= $title; ?> <br>
              <i class="fa fa-calendar me-1"></i> <?= format_tanggal_indonesia($tgl_sekarang); ?>
            </h5>
          </div>
          <div class="col-lg-3 text-end">
            <div class="d-inline-flex align-items-center px-3 py-1 " style="border-bottom: 1px solid gray;">
              <i class="fa fa-clock me-2 text-dark"></i>
              <div id="jam"></div>
            </div>
          </div>
        </div>
        <div class="container mt-4">
          <div class="row">

            <div class="col-md-4 col-sm-6 ">
              <div class="stat-card detail-waiting">
                <div class="stat-icon bg-waiting"><i class="fas fa-gear"></i></div>
                <div>
                  <p class="stat-label">Perbaikan IN & OUT</p>
                  <span class="stat-value" id="summary_pi">0</span>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-6">
              <div class="stat-card detail-progres">
                <div class="stat-icon bg-progres"><i class="fas fa-spinner fa-spin"></i></div>
                <div>
                  <p class="stat-label">GI/GB/GM</p>
                  <span class="stat-value" id="summary_gm">0</span>
                </div>
              </div>
            </div>

            <div class="col-md-4 col-sm-6">
              <div class="stat-card detail-total">
                <div class="stat-icon bg-total"><i class="fas fa-list"></i></div>
                <div>
                  <p class="stat-label">Total Mesin</p>
                  <span class="stat-value" id="total_mesin">0</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="d-flex align-items-center">
            <label class="font-kecil text-muted me-2 mb-0">Departemen:</label>
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
            <select name="filter" id="filter" class="form-select form-select-sm font-kecil">
              <option value="all" <?= $filter_dept == '' ? 'selected' : '' ?>>Semua Departemen</option>
              <?php foreach ($dept_options as $option) : ?>
                <?php if (in_array($option['dept_id'], $akses_dept_diberi)) : ?>
                  <option value="<?= $option['dept_id']; ?>" <?= $filter_dept == $option['dept_id'] ? 'selected' : '' ?>>
                    <?= $option['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $option['departemen']; ?>
                  </option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="row mt-2 ">
          <div class="col-lg-1">
            <div id="summary_clr"></div>
          </div>
          <div class="col-lg-11" style="border-left: 1px solid grey;">
            <div class="row" id="list_mesin" style="padding: 10px;"> </div>
          </div>

        </div>

      </div>
    </div>

  </div>
</div>

<!-- / Content -->

<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Keterangan Mesin STOP </h5>
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

<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>
<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery-ui.min.js"></script>



<script>
  $(document).ready(function() {

    function loadMesin() {

      let dept = $("#filter").val();
      let tanggal = $("#filter_tanggal").val();


      $.ajax({
        url: "<?= base_url('monitoring/getMesinByDept') ?>",
        type: "POST",
        data: {
          dept: dept,
          tanggal: tanggal,

        },
        dataType: "json",


        success: function(response) {
          let html = '';
          let mesin_perbaikan = 0;

          $.each(response.mesin, function(i, mesin) {
            let warna = "#eeeeee";
            let gearHtml = '';

            if (mesin.clr != null) {
              warna = "rgb(" + mesin.clr + ")";
              mesin_perbaikan++;
              //  tampilkan icon gear tapi DIAM (tidak putar)
              gearHtml = `<i class="fas fa-cog text-muted" style="font-size: 12px;"></i>`;
            } else {
              // Jika jalan, gear BERPUTAR
              gearHtml = `<i class="fas fa-cog rotating-gear"></i>`;
            }
            let status = mesin.downtime_id ? 1 : 0;
            html += `
             <div class="col-3 col-sm-2 col-md-1 mb-2 px-1"> <div class="card text-center mesin-card" style="background:${warna}">
                <div class="card-body p-2"> <div class="mesin-no fw-bold text-dark viewdata" data-mach="${mesin.downtime_id}" data-status="${status}" >                    
                      <span>${mesin.mach_no} </span>  <br>
                      <span style ="font-size : 8px ; "> ${mesin.name} </span>  
                 </div>
                    <div style="height: 15px; margin-top: 2px;"> ${gearHtml}
                    </div>
                </div>
              </div>
           </div>`;
          });

          $("#list_mesin").html(html);
          $("#total_mesin").text(response.mesin.length);
          $("#mesin_perbaikan").text(mesin_perbaikan);


          let summaryHtml = '';

          $.each(response.summary, function(i, row) {
            let warna = "#eeeeee";

            if (row.clr != null) {
              warna = "rgb(" + row.clr + ")";
            }
            summaryHtml += `
            <div style="display:flex;align-items:center;margin-bottom:5px">
              
              <div style="
                width:40px;
                height:20px;
                background:${warna};
                margin-right:5px;
                color:#000;
                font-size:12px;
                display:flex;
                align-items:center;
                justify-content:center;
              ">
                ${row.ins_kode ?? 'OK'}
              </div>

              ${row.total}

            </div>
            `;

          });

          $("#summary_clr").html(summaryHtml);

          let summary_pi = '';
          $.each(response.summary_pi, function(i, row) {

            summary_pi += `
            <div>
            

              ${row.total_pi}

            </div>
            `;

          });
          $("#summary_pi").html(summary_pi);


          let summary_gm = '';
          $.each(response.summary_gm, function(i, row) {
            summary_gm += `
            <div>
            

              ${row.total_gm}

            </div>
            `;

          });
          $("#summary_gm").html(summary_gm);
        }

      });

    }


    $("#filter,#filter_tanggal").change(function() {
      loadMesin();
    });

    loadMesin();
    // AUTO REFRESH
    setInterval(function() {
      loadMesin();
    }, 5000);
  });
</script>

<script>
  $(document).on("click", ".viewdata", function() {

    var mach_id = $(this).data("mach");
    var status = $(this).data("status");


    let tanggal = $("#filter_tanggal").val();

    if (status == 1) {

      const url = "<?= base_url('monitoring/view/'); ?>" + mach_id + "/" + tanggal;

      $("#basicModal").modal("show");

      $("#loadforminput").load(url, function() {
        simpanTimestamp();
      });

    } else {

      alert("MESIN SEDANG BERPRODUKSI");

    }

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
</script>
<script>
  function tampilJam() {
    const sekarang = new Date();

    let jam = sekarang.getHours();
    let menit = sekarang.getMinutes();
    let detik = sekarang.getSeconds();

    // Tambah angka 0 di depan jika < 10
    jam = jam < 10 ? '0' + jam : jam;
    menit = menit < 10 ? '0' + menit : menit;
    detik = detik < 10 ? '0' + detik : detik;

    document.getElementById('jam').innerHTML =
      jam + ':' + menit + ':' + detik;
  }

  // Jalankan setiap 1 detik
  setInterval(tampilJam, 1000);

  // Tampilkan langsung saat pertama load
  tampilJam();
</script>