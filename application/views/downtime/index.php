<!-- Content -->
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
          <div class=" mt-2 row">
            <div class="col-lg-5">
              <?= $this->session->flashdata('message'); ?>
            </div>
            <div class="col-lg-12">
              <div class="row">
                <div class="col-lg-12" style="text-align: right;">
                  <a id="btn-export-excel" class="btn btn-success btn-sm">
                    <i class="fa fa-file-excel-o"></i><span class="ml-1" style="color: aliceblue;">Export To Excel</span>
                  </a>

                  <a id="btn-export-pdf" class="btn btn-danger btn-sm" target="_blank">
                    <i class="fa fa-file-pdf-o"></i><span class="ml-1" style="color: aliceblue;">Export To PDF</span>
                  </a>



                </div>
              </div>
            </div>
          </div>
          <br>
          <div style="border: 1px solid #0d6efd;  padding: 5px; border-radius: 5px;">
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
              </div>
              <div class="row">
                <div class="col-lg-12" style="text-align: right;">
                  <span>Downtime Perbaikan:
                    <span id="total_downtime"></span>
                  </span>
                </div>
              </div>

            </div>
          </div>



          <div class="table-responsive">
            <table id="downtimeTable" class="table table-bordered">
              <thead>
                <tr class="bg-primary text-dark">
                  <th>No</th>
                  <th>Departemen</th>
                  <th>Mesin</th>
                  <th>Spek Mesin</th>
                  <th>Hitungan Menit</th>
                  <th>Hitungan Hari</th>
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


<!-- ajax -->
<script>
  $(document).ready(function() {
    var table = $('#downtimeTable').DataTable({
      processing: true,
      serverSide: true,
      stateSave: true,
      ajax: {
        url: "<?= base_url('downtime_mesin/filter') ?>",
        type: "POST",
        data: function(d) {
          d.dept_id = $('#filter').val();
          d.bulan = $('#filter_bulan').val();
          d.tahun = $('#filter_tahun').val();
          console.log('bulan:', d.bulan);
          console.log('tahun:', d.tahun);
        },
        dataSrc: function(json) {

          // tampilkan total downtime
          $('#total_downtime').html(json.total_downtime);

          // wajib return data
          return json.data;
        }
      },
      columns: [{
          data: 'no'
        },
        {
          data: 'departemen'
        },
        {
          data: 'mesin'
        },
        {
          data: 'spek'
        },
        {
          data: 'downtime/menit'
        },
        {
          data: 'downtime/hari'
        }
      ],
    });

    $('#filter,#filter_bulan,#filter_tahun').change(function() {
      table.ajax.reload();
    });
  });
</script>

<script>
  function updateExportLinks() {
    const dept = $('#filter').val();
    const bulan = $('#filter_bulan').val();
    const tahun = $('#filter_tahun').val();

    $('#btn-export-excel').attr('href', `<?= base_url('downtime_mesin/export_excel') ?>?dept_id=${dept}&bulan=${bulan}&tahun=${tahun}`);
    $('#btn-export-pdf').attr('href', `<?= base_url('downtime_mesin/export_pdf') ?>?dept_id=${dept}&bulan=${bulan}&tahun=${tahun}`);
  }

  $('#filter, #filter_bulan, #filter_tahun').change(updateExportLinks);
  $(document).ready(updateExportLinks); // set awal
</script>