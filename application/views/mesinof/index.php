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
                <div class="col-lg-6 mb-1">
                  <a href="#" class="btn btn-outline-primary font-kecil" id="tambahdata">
                    <i class="fa-solid fa-pen-to-square me-2"> </i>Tambah Data
                  </a>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div style="border: 1px solid grey;  padding: 5px; border-radius: 10px;">
            <div class="col-lg-12">
              <div class="row" style="padding:10px;">

                <div class="col-lg-3">
                  <label class="font-kecil font-bold text-azure text-dark" st>Bulan</label>
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
                  <label class="font-kecil font-bold text-azure text-dark">Tahun</label>
                  <select name="filter_tahun" id="filter_tahun" class="form-select font-kecil mt-0">
                    <option value="all" <?= $filter_tahun == 'all' ? 'selected' : '' ?>>Semua Tahun</option>
                    <?php foreach ($tahun_options as $th) : ?>
                      <option value="<?= $th['tahun']; ?>" <?= ($filter_tahun == $th['tahun'] || ($filter_tahun == 'all' && $thn_sekarang == $th['tahun'])) ? 'selected' : '' ?>>
                        <?= $th['tahun']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-lg-3">
                  <label class="font-kecil font-bold text-azure text-dark">Departemen</label>

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
          </div>

          <div class="table-responsive">
            <table id="mesinofTable" class="table table-bordered">
              <thead>
                <tr class="bg-warning text-dark">
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Departemen</th>
                  <th>Mesin</th>
                  <th>Keterangan</th>
                  <th>Shift</th>
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
<!-- / Content -->

<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Input Kerusakan</h5>
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
        <h5 class="modal-title">Edit Kerusakan</h5>
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
        <h3>Anda Yakin,</h3>
        <div class="text-secondary" id="message">Data Ini Akan Di Hapus ?</div>
      </div>
      <div class="modal-footer">
        <div class="w-100">
          <div class="row">
            <div class="col"><a id="btn-okk" href="#" class="btn btn-danger w-100">
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

<!-- modal -->
<script>
  $(document).ready(function() {
    $("#tambahdata").click(function(e) {


      const filter_dept = $('#filter').val();
      // cek filter
      if (filter_dept === 'all') {
        e.preventDefault();
        alert("Silakan pilih departemen terlebih dahulu.");
        return false;
      }
      const url = "<?= base_url('mesin_of/tambahdata/'); ?>" + filter_dept;
      $("#basicModal").modal("show");
      $("#loadforminput").load(url, function() {
        $("#nomesin").autocomplete({
          source: function(request, response) {
            $.ajax({
              url: "<?= base_url('mesin_of/nomor_mesin'); ?>",
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

        $("#ketof").autocomplete({
          source: function(request, response) {
            $.ajax({
              url: "<?= base_url('mesin_of/ket_mesinof'); ?>",
              dataType: "json",
              data: {
                term: request.term,
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

        $("#ket_tb").autocomplete({
          source: function(request, response) {
            $.ajax({
              url: "<?= base_url('mesin_of/benang'); ?>",
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

      });
    });


    $(document).on('click', '.edit', function() {
      var data = $(this).data("id");

      if (data) {

        $("#basicModal-edit").modal("show");

        $("#loadforminput-edit").load("<?= base_url(); ?>mesin_of/edit/" + encodeURIComponent(data), function() {


          $("#ketof").autocomplete({
            source: function(request, response) {

              $.ajax({
                url: "<?= base_url('mesin_of/ket_mesinof'); ?>",
                dataType: "json",
                data: {
                  term: request.term
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

            }

          });
          $("#ketof").on("input", function() {

            if ($(this).val() == "") {
              $("#ket_id").val("");
            }

          });

          // AUTOCOMPLETE BENANG
          $("#ket_tb").autocomplete({
            source: function(request, response) {
              $.ajax({
                url: "<?= base_url('mesin_of/benang'); ?>",
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
            minLength: 1
          });
          $("#ket_tb").on("input", function() {

            if ($(this).val() == "") {
              $("#id_benang").val("");
            }

          });
        });



      } else {
        alert("Data tidak valid!");
      }
    });

    $(document).on('click', '.hapus', function() {
      var url = $(this).data("url");
      $("#btn-okk").attr("href", url);
      $("#modal-hapus").modal("show");
    });


  });
</script>

<!-- ajax -->




<script>
  $(document).ready(function() {
    var table = $('#mesinofTable').DataTable({
      processing: true,
      serverSide: true,
      stateSave: true,
      ajax: {
        url: "<?= base_url('mesin_of/filter') ?>",
        type: "POST",
        data: function(d) {
          d.dept_id = $('#filter').val();
          d.bulan = $('#filter_bulan').val();
          d.tahun = $('#filter_tahun').val();
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
          data: 'ket'
        },
        {
          data: 'shift'
        },
        {
          data: 'aksi'
        }
      ],
    });

    $('#filter,#filter_bulan,#filter_tahun').change(function() {
      table.ajax.reload();
    });

  });
</script>