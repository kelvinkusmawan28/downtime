<style>
  .modern-card {
    border: none;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    overflow: hidden;
  }

  .card-header-status {
    background: #FEF2A0;
    padding: 15px 25px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .info-group {
    padding: 15px;
    transition: all 0.3s;
    border-radius: 12px;
  }

  .info-group:hover {
    background: #fdfdfd;
  }

  .label-text {
    font-size: 0.7rem;
    font-weight: 700;
    color: #a0aec0;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 4px;
    display: block;
  }

  .value-text {
    font-size: 0.9rem;
    font-weight: 600;
    color: #2d3748;
  }

  .value-highlight {
    color: #e53e3e;

  }

  .status-badge {
    padding: 6px 16px;
    border-radius: 5px;
    font-size: 0.75rem;
    font-weight: 800;
  }

  .kesimpulan-area {
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 12px;
    padding: 15px;
  }

  .link-file {
    font-size: 0.85rem;
    color: #3182ce;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
</style>
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-lg-12" style="text-align: right;">
      <a href="<?= base_url('mesin'); ?>" class="btn btn-sm btn-warning"> <i class="fa fa-arrow-left me-2"></i> Kembali</a>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-lg-12">
      <div class="card modern-card">

        <div class="card-header-status">
          <div class="d-flex align-items-center">
            <span class="me-5" style="font-size: 1.2rem;"> <i class="fa-solid fa-screwdriver-wrench fs-2 text-dark"></i></span>
            <div>
              <h5 class="mb-0 text-dark">Mesin <?= $detail['mach_no']; ?></h5>
              <small class="text-dark"><?= $detail['mach_name']; ?></small>
            </div>
          </div>
          <div>
            <?php if ($detail['status'] == 0) : ?>
              <span class="badge status-badge bg-light shadow-sm text-dark">PROGRESS..</span>
            <?php elseif ($detail['status'] == 2) : ?>
              <span class="badge status-badge bg-light shadow-sm text-dark">ANTRIAN..</span>
            <?php else : ?>
              <span class="badge status-badge bg-light shadow-sm text-danger">CLOSE</span>
            <?php endif; ?>
          </div>
        </div>

        <div class="card-body p-4">
          <div class="row">
            <div class="col-md-8">
              <div class="row g-3">
                <div class="col-6 col-md-4 info-group">
                  <span class="label-text">📅 Tanggal Laporan</span>
                  <span class="value-text"><?= format_tanggal_indonesia($detail['tanggal']); ?></span>
                </div>
                <div class="col-6 col-md-4 info-group">
                  <span class="label-text">🏢 Departemen</span>
                  <span class="value-text"><?= $detail['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $detail['departemen']; ?></span>
                </div>
                <div class="col-6 col-md-4 info-group">
                  <span class="label-text">👨 User</span>
                  <span class="value-text text-primary"><?= $detail['user']; ?></span>
                </div>

                <div class="col-12 mt-2">
                  <div class="p-3 font-kecil border-start border-danger border-4 bg-light rounded-end">
                    <span class="label-text text-danger">🔍 Masalah / Kerusakan</span>
                    <div class="value-text value-highlight">
                      <?= $detail['remark']; ?>
                    </div>
                    <div class="text-muted small mt-1">
                      <?php if ($this->session->userdata('name') == $detail['user']) : ?>
                        <a href="#" data-id="<?= $detail['id']; ?>" class="keterangan text-info" style="text-decoration: underline;">Klik Edit Disini</a>
                      <?php endif; ?>
                      Detail: <?= $detail['keterangan'] ?: '-'; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-4 border-start ps-md-4">
              <div class="mb-4">
                <span class="label-text">🌐 Lampiran File</span>
                <div class="mt-2">
                  <?php
                  $path_files = json_decode($detail['path_file'], true);
                  $file_names = json_decode($detail['file'], true);

                  if (!empty($path_files)) {
                    foreach ($path_files as $index => $path) {
                      $filename = isset($file_names[$index]) ? $file_names[$index] : 'Attachment-' . ($index + 1);
                      echo '<a href="' . base_url($path) . '" target="_blank" class="link-file mb-1 d-block font-bold">📂 ' . $filename . '</a>';
                    }
                  } else {
                    echo '<span class="text-muted small"><i>Tidak ada lampiran</i></span>';
                  }
                  ?>
                </div>
              </div>

              <div class="kesimpulan-area">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="label-text text-danger m-0">📌 Kesimpulan</span>
                  <?php if ($this->session->userdata('cekdowntime') == 1) : ?>
                    <a href="#" data-id="<?= $detail['id']; ?>" class="kesimpulan text-decoration-none small fw-bold">Klik Isi Disini</a>
                  <?php endif; ?>
                </div>
                <p class="mb-0 small text-dark" style="font-style: italic;">
                  <?= $detail['kesimpulan'] ?: 'Menunggu evaluasi teknisi...'; ?>
                </p>
              </div>
            </div>
          </div>
          <div class="row mt-4 " style="border-top: 1px dashed grey;">
            <div class="col-lg-6">
              <h5 class="me-2 mt-4"><?= $title; ?></h5>
              <?php if ($detail['status'] == 0) : ?>
                <a href="#" class="btn btn-outline-primary font-kecil " id="tambahdata" data-bs-toggle="modal" data-bs-target="#basicModal">
                  Tambah Data
                </a>
              <?php endif; ?>
            </div>
            <div class="card-body font-kecil ">
              <div class="table-responsive">
                <!-- <table class="tabel datatable"> -->
                <table class="table  table-bordered">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tanggal</th>
                      <th>Jam Mulai</th>
                      <th>Jam Selesai</th>
                      <th>Waktu Perbaikan</th>
                      <th>User</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 0;
                    foreach ($tindakan as $data) : $no++; ?>
                      <tr>
                        <td><?= $no; ?></td>
                        <td><a href="#" data-id="<?= $data['id']; ?>" class="view" title="view Data" style="color: blueviolet; margin-left:10px; text-decoration: underline;">
                            <?= format_tanggal_indonesia($data['tanggal']); ?>
                          </a>
                        </td>
                        <td><?= $data['jam_start']; ?></td>
                        <td><?= $data['jam_end']; ?></td>
                        <td><?= format_downtime($data['downtime']);  ?></td>
                        <td><?= $data['user']; ?></td>
                        <td>
                          <?php if ($data['user'] == $this->session->userdata('name')) : ?>
                            <?php if ($detail['status'] == 0) : ?>
                              <div class="dropdown" class="font-kecil">
                                <a class="btn btn-secondary dropdown-toggle font-kecil" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                  Aksi
                                </a>
                                <ul class="dropdown-menu">
                                  <li><a class=" btn btn-outline-success font-kecil edit-tindakan" data-id="<?= $data['id']; ?>" href="#"> <i class="fa-solid fa-pencil me-2"></i> Edit Data</a></li>
                                  <li><a class=" btn btn-outline-danger font-kecil hapus-tindakan" data-id="<?= $data['id']; ?>" data-url="<?= base_url(); ?>mesin/hapus_tindakan/<?= $data['id']; ?>/<?= $id_halaman; ?>" href="#"> <i class="fa fa-trash me-2"></i> Hapus Data</a></li>
                                </ul>
                              </div>
                            <?php else : ?>
                              <p style="color:blue; padding-top:15px;">Data Sudah Close</p>
                            <?php endif; ?>
                          <?php endif; ?>

                        </td>
                      </tr>
                    <?php endforeach; ?>
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
<!-- / Content -->

<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Input Tindakan</h5>
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Tindakan</h5>
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
            <div class="col"><a id="btn-ok" href="#" class="btn btn-danger w-100">
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

<div class="modal fade" id="basicModal-kesimpulan" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesimpulan Project</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="load-kesimpulan"></div>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="basicModal-keterangan" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Kerusakan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="load-detail"></div>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>

<!-- <div class="offcanvas offcanvas-start" tabindex="-1" id="modal-view" aria-labelledby=" offcanvasExampleLabel" style="width: 50%;">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Downtime Mesin</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div id="loadformview-sub"></div>
  </div>
</div> -->

<div class="modal fade" id="modal-view" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Tindakan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="loadformview-sub"></div>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>


<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>
<!-- <script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery-ui.min.js"></script> -->
<script src="<?= base_url(); ?>/assets/tagify/tagify.min.js"></script>


<!-- modal -->
<script>
  $(document).ready(function() {



    $("#tambahdata").click(function() {
      $("#basicModal").modal("show");
      $("#loadforminput").load("<?= base_url('mesin/tambah_tindakan/' . $id_halaman); ?>", function() {

        setTimeout(function() {
          var input = document.querySelector("#teknisi");

          if (!input) {
            console.warn("Input teknisi tidak ditemukan");
            return;
          }


          if (input.tagify) {
            input.tagify.destroy();
          }

          var tagify = new Tagify(input, {
            enforceWhitelist: true,
            tagTextProp: 'label',
            whitelist: [],
            maxTags: 10,
            dropdown: {
              enabled: 1,
              classname: "teknisi-suggestions",
              maxItems: 10
            }
          });

          tagify.on("input", function(e) {
            let value = e.detail.value;

            $.ajax({
              url: "<?= base_url('mesin/teknisi'); ?>",
              data: {
                term: value
              },
              success: function(data) {
                try {
                  var list = JSON.parse(data).map(item => ({
                    value: item.name
                  }));
                  tagify.settings.whitelist = list;
                  tagify.dropdown.show.call(tagify, value);
                } catch (err) {
                  console.error("Error parsing data teknisi:", err);
                }
              },
              error: function(xhr) {
                console.error("Gagal mengambil data teknisi:", xhr);
              }
            });
          });
        }, 100); // delay 100ms
      });
    });



    $(document).on("click", ".edit-tindakan", function() {
      var data = $(this).data("id");
      var id_halaman = <?= json_encode($id_halaman); ?>;
      if (!data) {
        alert("Data tidak valid!");
        return;
      }

      $("#basicModal-edit").modal("show");
      $("#loadforminput-edit").load(
        "<?= base_url(); ?>mesin/edit_tindakan/" + encodeURIComponent(data) + "/" + encodeURIComponent(id_halaman),
        function() {

          setTimeout(function() {
            var input = document.querySelector("#teknisi");
            if (!input) {
              console.warn("Elemen #teknisi tidak ditemukan.");
              return;
            }


            if (input.tagify) {
              input.tagify.destroy();
            }


            var whitelistAwal = teknisiTerpilih.map(t => t.value);

            var tagify = new Tagify(input, {
              enforceWhitelist: true,
              tagTextProp: "value",
              whitelist: whitelistAwal,
              maxTags: 10,
              dropdown: {
                enabled: 1,
                classname: "teknisi-suggestions",
                maxItems: 10
              }
            });


            tagify.addTags(teknisiTerpilih);

            tagify.on("input", function(e) {
              let value = e.detail.value;

              $.ajax({
                url: "<?= base_url('mesin/teknisi'); ?>",
                data: {
                  term: value
                },
                success: function(data) {
                  try {
                    var list = JSON.parse(data).map(item => ({
                      value: item.name
                    }));
                    tagify.settings.whitelist = [...new Set([...whitelistAwal, ...list.map(t => t.value)])];
                    tagify.dropdown.show.call(tagify, value);
                  } catch (err) {
                    console.error("Gagal parsing JSON teknisi:", err);
                  }
                },
                error: function(xhr) {
                  console.error("Gagal load teknisi:", xhr);
                }
              });
            });
          }, 100);
        }
      );
    });




    $(document).on("click", ".hapus-tindakan", function() {
      var url = $(this).data("url");
      $("#btn-ok").attr("href", url);
      $("#modal-hapus").modal("show");
    });

    $(document).on('click', '.kesimpulan', function() {
      var data = $(this).data("id");
      if (data) {
        $("#basicModal-kesimpulan").modal("show");
        $("#load-kesimpulan").load("<?= base_url(); ?>mesin/kesimpulan_detail/" + encodeURIComponent(data));
      } else {
        alert("Data tidak valid!");
      }
    });
    $(document).on('click', '.keterangan', function() {
      var data = $(this).data("id");
      if (data) {
        $("#basicModal-keterangan").modal("show");
        $("#load-detail").load("<?= base_url(); ?>mesin/keterangan_detail/" + encodeURIComponent(data));
      } else {
        alert("Data tidak valid!");
      }
    });

    $(document).on("click", ".view", function() {
      var data = $(this).data("id");
      if (data) {
        $("#modal-view").modal("show");
        $("#loadformview-sub").load("<?= base_url(); ?>mesin/view_tindakan/" + encodeURIComponent(data));
      } else {
        alert("Data tanggal tidak valid!");
      }
    });


  });
</script>