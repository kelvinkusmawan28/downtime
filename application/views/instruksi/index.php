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
            <div class="col-lg-12">
              <div class="row">
                <?php
                if ($this->session->userdata('cekdowntime') == '1') : ?>
                  <div class="col-lg-6 mb-1">
                    <a href="#" class="btn btn-outline-primary font-kecil" id="tambahdata">
                      <i class="fa-solid fa-pen-to-square me-2"> </i>Tambah Data
                    </a>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <br>
          <div>
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
                  <label class="font-kecil font-bold text-azure text-primary">Cek Status</label>
                  <select name="filter_status" id="filter_status" class="form-select font-kecil mt-0">
                    <option value="all" <?= $filter_status == 'all' ? 'selected' : '' ?>>Semua Status</option>
                    <option class="text-dark" value="2" <?= $filter_status == 2  ? 'selected' : '' ?>>Antrian</option>
                    <!-- <option class="text-dark" value="1" <?= $filter_status == 1  ? 'selected' : '' ?>>Close</option> -->
                    <option class="text-dark" value="0" <?= $filter_status == 0  ? 'selected' : '' ?>>Progres</option>

                  </select>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row mt-3">
            <div class="col-3 col-md-2">
              <select id="entries" class="form-control">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>

            <div class="col-5">
              <input type="text" id="search" class="form-control" placeholder="Search...">
            </div>


          </div>
          <div class="row mt-3" id="cardContainer"></div>
          <div class="d-flex justify-content-between align-items-center mt-3">
            <button id="prevPage" class="btn btn-secondary btn-sm">Prev </button> <small id="infoData"></small>
            <button id="nextPage" class="btn btn-secondary btn-sm">Next</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<!-- / Content -->

<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Input Data Ganti Instruksi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- <div class="row">
          <div class="col mb-6">
            <label for="nameBasic" class="form-label">Name</label>
            <input type="text" id="nameBasic" class="form-control" placeholder="Enter Name" />
          </div>
        </div>
        <div class="row g-6">
          <div class="col mb-0">
            <label for="emailBasic" class="form-label">Email</label>
            <input type="email" id="emailBasic" class="form-control" placeholder="xxxx@xxx.xx" />
          </div>
          <div class="col mb-0">
            <label for="dobBasic" class="form-label">DOB</label>
            <input type="date" id="dobBasic" class="form-control" />
          </div>
        </div> -->
        <div id="loadforminput"></div>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="basicModal-edit" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Instruksi</h5>
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

<div class="modal fade" id="basicModal-file" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lihat File</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="loadforminput-file"></div>
      </div>
    </div>
  </div>
</div>


<div class="modal modal-blur fade" id="modal-hapus" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-status bg-danger"></div>
      <div class="modal-body text-center py-4">
        <svg class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
          <path d="M12 9v4" />
          <path d="M12 17h.01" />
        </svg>
        <h3>Anda Yakin ?</h3>
        <div class="text-secondary" id="message">Ingin Menghapus Data Ini ?</div>
      </div>
      <div class="modal-footer">
        <div class="w-100">
          <div class="row">
            <div class="col"><a id="btn-okhapus" href="#" class="btn btn-danger w-100">
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

<div class="modal modal-blur fade" id="modal-status" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-status bg-info"></div>
      <div class="modal-body text-center py-4">
        <svg class="icon mb-2 text-info icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
          <path d="M12 9v4" />
          <path d="M12 17h.01" />
        </svg>
        <h3>Anda Yakin,</h3>
        <div class="text-secondary" id="message">Data Ini Sudah Close ?</div>
      </div>
      <div class="modal-footer">
        <div class="w-100">
          <div class="row">
            <div class="col"><a id="btn-ok" href="#" class="btn btn-info w-100">
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
        <div class="text-secondary" id="message">Status Data Kembali Progres ?</div>
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

<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>
<!-- to filter form -->
<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery-ui.min.js"></script>

<!-- modal -->
<script>
  const base_url = "<?= base_url() ?>";
  $(document).ready(function() {
    $("#tambahdata").click(function(e) {


      const filter_dept = $('#filter').val();
      // cek filter
      if (filter_dept === 'all') {
        e.preventDefault();
        alert("Silakan pilih departemen terlebih dahulu.");
        return false;
      }
      const url = "<?= base_url('instruksi/tambahdata/'); ?>" + filter_dept;
      $("#basicModal").modal("show");
      $("#loadforminput").load(url, function() {
        $("#nomesin").autocomplete({
          source: function(request, response) {
            $.ajax({
              url: "<?= base_url('instruksi/nomor_mesin'); ?>",
              dataType: "json",
              data: {
                term: request.term,
                filter: $('#filter').val()
              },
              success: function(data) {
                response($.map(data, function(item) {
                  return {
                    label: 'Mesin ' + item.mach_no + " - " + item.mach_name,
                    value: item.mach_no + " - " + item.mach_name,
                    mach_id: item.mach_id
                  };
                }));
              }
            });
          },
          select: function(event, ui) {
            $("#mach_id").val(ui.item.mach_id);
          },
          minLength: 1
        });

        $("#kerusakan").autocomplete({
          source: function(request, response) {
            $.ajax({
              url: "<?= base_url('instruksi/kerusakan_mesin'); ?>",
              dataType: "json",
              data: {
                kerusakan: request.term,
                filter: $('#filter').val()
              },
              success: function(data) {
                response($.map(data, function(key) {
                  return {
                    label: key.remark + " - " + key.ins_kode,
                    value: key.remark + " - " + key.ins_kode,
                    rusak_id: key.rusak_id
                  };
                }));
              }
            });
          },
          select: function(event, ui) {
            $("#rusak_id").val(ui.item.rusak_id);
          },
          minLength: 1
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


    $(document).on('click', '.edit', function() {
      var data = $(this).data("id");

      if (data) {

        $("#basicModal-edit").modal("show");
        $("#loadforminput-edit").load("<?= base_url(); ?>instruksi/edit/" + encodeURIComponent(data), function() {
          $("#kerusakan").autocomplete({
            source: function(request, response) {
              $.ajax({
                url: "<?= base_url('instruksi/kerusakan_mesin'); ?>",
                dataType: "json",
                data: {
                  kerusakan: request.term,
                  filter: $('#filter').val()
                },
                success: function(data) {
                  response($.map(data, function(key) {
                    return {
                      label: key.remark + " - " + key.ins_kode,
                      value: key.remark + " - " + key.ins_kode,
                      rusak_id: key.rusak_id
                    };
                  }));
                }
              });
            },
            select: function(event, ui) {
              $("#rusak_id").val(ui.item.rusak_id);
            },
            minLength: 1
          });

        });
      } else {
        alert("Data tidak valid!");
      }
    });

    $(document).on('click', '.hapus', function() {
      var url = $(this).data("url");
      $("#btn-okhapus").attr("href", url);
      $("#modal-hapus").modal("show");
    });

    $(document).on('click', '.status', function() {
      var url = $(this).data("url");
      $("#btn-ok").attr("href", url);
      $("#modal-status").modal("show");
    });

    $(document).on('click', '.status_cansel', function() {
      var url = $(this).data("url");
      $("#btn-okk").attr("href", url);
      $("#modal-status_cansel").modal("show");
    });
  });
</script>

<!-- ajax -->






<script>
  // function loadData() {
  //   $.ajax({
  //     url: "<?= base_url('instruksi/filter_instruksi') ?>",
  //     type: "POST",
  //     data: {
  //       dept_id: $('#filter').val() || 'all',
  //       status: $('#filter_status').val() || 'all',
  //       bulan: $('#filter_bulan').val() || 'all',
  //       tahun: $('#filter_tahun').val() || 'all',
  //       length: 100,
  //       start: 0
  //     },
  //     dataType: "json",
  //     success: function(res) {
  //       console.log("INI DATA:", res);
  //       renderCard(res.data);
  //     },
  //     error: function(xhr, status, error) {
  //       console.log("STATUS:", status);
  //       console.log("ERROR:", error);
  //       console.log("RESPONSE TEXT:", xhr.responseText);
  //     }
  //   });
  // }
  let currentPage = 1;
  let limit = 10;

  function loadData() {
    let search = $('#search').val();

    $.ajax({
      url: "<?= base_url('instruksi/filter_instruksi') ?>",
      type: "POST",
      data: {
        dept_id: $('#filter').val() || 'all',
        status: $('#filter_status').val() || 'all',
        bulan: $('#filter_bulan').val() || 'all',
        tahun: $('#filter_tahun').val() || 'all',
        length: limit,
        start: (currentPage - 1) * limit,

        // 🔥 penting (format datatable)
        search: {
          value: search
        }
      },
      dataType: "json",
      success: function(res) {
        renderCard(res.data);

        // INFO DATA
        let start = (currentPage - 1) * limit + 1;
        let end = start + res.data.length - 1;
        $('#infoData').text(`Showing ${start} to ${end} of ${res.recordsTotal}`);

        // BUTTON PAGINATION
        $('#prevPage').prop('disabled', currentPage === 1);
        $('#nextPage').prop('disabled', end >= res.recordsTotal);
      }
    });
  }

  function renderCard(data) {
    let html = '';

    data.forEach(row => {

      let borderColor = row.status.includes('PROGRES') ?
        'border-primary' :
        'border-success';

      html += `
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-1 rounded-3 hover-shadow transition-all"  >  
              <div class="card-header border-1 pt-4 pb-1" style = "background-color : #e3f2fd;">
                <div class="d-flex justify-content-between align-items-start">
                  <div class="d-flex align-items-center">
                    <div class="icon-box bg-light-white rounded-circle p-2 me-3">
                      <i class="fas fa-clipboard-list  text-dark fa-2x"></i> 
                    </div>
                    <div>
                  
                      <h6 class="card-title fw-bold text-dark mb-0">${row.mesin}</h6>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-body py-3">
                <div class="row mb-3 g-2">
                  <div class="col-6" style = "border-right : solid 1px grey ;"">
                    <label class="text-muted small d-block">Tanggal</label>
                    <span class="fw-semibold small" style = "font-size: 10px; ><i class="far fa-calendar-alt me-1"></i> ${row.tanggal}</span>
                  </div>
                  <div class="col-6">
                    <label class="text-muted small d-block">Departemen</label>
                    <span class=" text-dark border fw-medium small" style = "font-size: 10px; background-color : #e3f2fd;">${row.departemen}</span> <br>
                    <span class="text-dark border fw-medium small" style = "font-size: 10px;">${row.user}</span>
                  </div>
                </div>

                <div class="p-3 bg-light rounded-2 mb-3">
                  <label class="text-muted small d-block fw-bold mb-1 text-uppercase" style="font-size: 0.7rem;">Deskripsi </label>
                  <p class="card-text small mb-0 text-secondary italic">
                    "${row.kerusakan}"
                  </p>
                  <p class="card-text small mb-0 text-secondary  text-uppercase italic">
                    "${row.instruksi}"
                  </p>
                </div>
                <div class="p-3 bg-grey rounded-2 mt-2">
                  <label class="text-muted text-center small d-block fw-bold mb-1 text-uppercase" style="font-size: 0.7rem;">Status :</label>
                  <p class="card-text text-center small mb-0 text-secondary italic">
                    ${row.status} 
                  </p>
                </div>


              </div>

              <div class="card-footer bg-transparent border-0 pb-4 pt-0">
                <div class="d-grid gap-2">
                  ${row.aksi}
                </div>
              </div>

            </div>
        </div>
      `;
    });

    $('#cardContainer').html(html);


    simpanTimestamp();
  }
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
  // READY
  $(document).ready(function() {

    loadData();

    $('#filter,#filter_status,#filter_bulan,#filter_tahun').change(function() {
      loadData();
    });

  });

  // SEARCH (ketik langsung jalan)
  $('#search').on('keyup', function() {
    currentPage = 1;
    loadData();
  });

  // ENTRIES
  $('#entries').on('change', function() {
    limit = parseInt($(this).val());
    currentPage = 1;
    loadData();
  });

  // NEXT
  $('#nextPage').on('click', function() {
    currentPage++;
    loadData();
  });

  // PREV
  $('#prevPage').on('click', function() {
    if (currentPage > 1) {
      currentPage--;
      loadData();
    }
  });
</script>