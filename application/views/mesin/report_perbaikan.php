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
        <div class="card-body font-kecil ">
          <div class=" mt-2 row">
            <div class="col-lg-8">
              <?= $this->session->flashdata('message'); ?>
            </div>

          </div>
          <br>

          <div class="col-lg-12">
            <div class="row" style="padding:10px;">
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
                <label class="font-kecil font-bold text-azure text-primary">Cek status</label>
                <select name="filter_status" id="filter_status" class="form-select font-kecil mt-0">
                  <option value="all" <?= $filter_status == 'all' ? 'selected' : '' ?>>Semua Status</option>
                  <option class="text-dark" value="2" <?= $filter_status == 2  ? 'selected' : '' ?>>Antrian</option>
                  <option class="text-dark" value="0" <?= $filter_status == 0  ? 'selected' : '' ?>>Progres</option>
                  <option class="text-dark" value="1" <?= $filter_status == 1  ? 'selected' : '' ?>>Close</option>
                </select>
              </div>
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
    </div>

  </div>
</div>
<!-- / Content -->

<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>
<!-- to filter form -->
<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery-ui.min.js"></script>

<!-- modal -->


<!-- ajax -->

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
        // Panggil fungsi ini setiap tabel selesai reload
        simpanTimestamp(); // Simpan semua data-start terbaru
      }
    });

    $('#filter,#filter_status,#filter_bulan,#filter_tahun').change(function() {
      table.ajax.reload();
    });

    // Simpan semua elemen dan start time-nya
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

    // Interval global
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