<style>
  .modern-card {
    border: none;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    overflow: hidden;
  }

  .card-header-status {
    background: #e3f2fd;
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
    /* Red for damage info */
  }

  .status-badge {
    padding: 6px 16px;
    border-radius: 50px;
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
      <a href="<?= base_url('instruksi'); ?>" class="btn btn-sm btn-warning"> <i class="fa fa-arrow-left me-2"></i> Kembali</a>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-lg-12">
      <div class="card modern-card">

        <div class="card-header-status">
          <div class="d-flex align-items-center">
            <span class="me-3" style="font-size: 1.2rem;">🎰</span>
            <div>
              <h6 class="mb-0 text-dark">Mesin <?= $detail['mach_no']; ?></h6>
              <small class="text-dark"><?= $detail['mach_name']; ?></small>
            </div>
          </div>
          <div>
            <?php if ($detail['status'] == 0) : ?>
              <span class="badge status-badge bg-warning shadow-sm text-dark">PROGRESS..</span>
            <?php elseif ($detail['status'] == 2) : ?>
              <span class="badge status-badge bg-info shadow-sm text-dark">ANTRIAN..</span>
            <?php else : ?>
              <span class="badge status-badge bg-danger shadow-sm text-dark">CLOSE</span>
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
                  <span class="label-text">👨 Dibuat Oleh</span>
                  <span class="value-text text-primary"><?= $detail['user']; ?></span>
                </div>

                <div class="col-12 mt-2">
                  <div class="p-3 font-kecil border-start border-danger border-4 bg-light rounded-end">
                    <span class="label-text text-info">🔍 Pengerjaan</span>
                    <div class="value-text value-highlight">
                      <?= $detail['ins_kode']; ?> - <?= $detail['remark']; ?>
                    </div>
                    <div class="text-muted small mt-1">
                      Instruksi : <?= $detail['keterangan'] ?: '-'; ?>
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
                  <span class="label-text text-danger m-0">📌 Keterangan</span>
                  <?php if ($detail['status'] == 0) : ?>
                    <a href="#" data-id="<?= $detail['id']; ?>" class="kesimpulan text-decoration-none small fw-bold">Klik Isi Disini</a>
                  <?php endif; ?>
                </div>
                <p class="mb-0 small text-dark" style="font-style: italic;">
                  <?= $detail['kesimpulan'] ?: 'Keterangan Petugas...'; ?>
                </p>
              </div>
            </div>
          </div>

          <div class="card-body font-kecil ">
            <div class="card-title mb-0">
              <h5 class="mb-1 me-2"><?= $title; ?></h5>
            </div>

            <?php if (!$sudah_ada) : ?>
              <form action="<?= base_url('instruksi/simpan_tindakan/' . $id_halaman) ?>" method="post" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-5">
                    <div class="row">
                      <div class="col-12">
                        <label for="tgl" class="form-label font-kecil">Tanggal <span class="text-info"><?= format_tanggal_indonesia($tgl_sekarang); ?></span> </label>
                        <input type="date" name="tanggal" class="form-control font-kecil" id="tanggal" value="<?= $tgl_sekarang ?>" required>
                      </div>
                      <div class="col-6 mt-2">
                        <label for="tgl" class="form-label font-kecil">Kakesu (Sebelum)</label>
                        <input type="text" name="kakesu_sebelum" class="form-control font-kecil" id="kakesu_sebelum" required>
                      </div>
                      <div class="col-6  mt-2">
                        <label for="tgl" class="form-label font-kecil">Kakesu (Baru)</label>
                        <input type="text" name="kakesu_baru" class="form-control font-kecil" id="kakesu_baru" required>
                      </div>
                      <div class=" col-6 mt-2">
                        <label class="font-kecil">Benang (Sebelum)</label>
                        <input type="text" name="sebelum" id="sebelum" class="form-control font-kecil" placeholder="Ketik Benang Sebelum..">
                        <input type="hidden" name="bs_id" id="bs_id">
                      </div>
                      <div class="col-6  mt-2">
                        <label class="font-kecil">Benang (Baru)</label>
                        <input type="text" name="baru" id="baru" class="form-control font-kecil" placeholder="Ketik  Benang Baru..">
                        <input type="hidden" name="bb_id" id="bb_id">
                      </div>
                      <div class="col-12">
                        <label for="teknisi" class="form-label font-kecil">Petugas </label>
                        <input name="teknisi" id="teknisi" class="form-control font-kecil" placeholder="Ketik Nama Teknisi" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <?php
                    $kategori_sekarang = '';
                    foreach ($tindakan as $row) :

                      if ($kategori_sekarang != $row->id_kategori) :
                        $kategori_sekarang = $row->id_kategori;
                    ?>
                        <br>
                        <b><?= $row->kode_kategori . '. ' . $row->nama_kategori ?></b><br>
                      <?php endif; ?>

                      <input type="checkbox" name="tindakan[]" value="<?= $row->id_tindakan ?>">

                      <?= $row->nama_tindakan ?><br>

                    <?php endforeach; ?>
                  </div>
                </div>


                <br>
                <div class="col-md-5">
                  <button type="button" class="btn btn-warning text-dark w-100" data-bs-toggle="modal" data-bs-target="#confirmModal">
                    Simpan
                  </button>
                </div>


              </form>
            <?php else : ?>
              <div class="row">
                <div class="col-md-5">
                  <div class="row">
                    <div class="col-12">
                      <label for="tgl" class="form-label font-kecil">Tanggal <span class="text-info"><?= format_tanggal_indonesia($tgl_sekarang); ?></span> </label>
                      <input type="date" name="tanggal" class="form-control font-kecil" id="tanggal" value="<?= $cek['tanggal']; ?>" disabled>
                    </div>
                    <div class="col-6 mt-2">
                      <label for="tgl" class="form-label font-kecil">Kakesu (Sebelum)</label>
                      <input type="text" name="kakesu_sebelum" class="form-control font-kecil" id="kakesu_sebelum" value="<?= $cek['kakesu_sebelum']; ?>" disabled>
                    </div>
                    <div class="col-6  mt-2">
                      <label for="tgl" class="form-label font-kecil">Kakesu (Baru)</label>
                      <input type="text" name="kakesu_baru" class="form-control font-kecil" id="kakesu_baru" value="<?= $cek['kakesu_baru']; ?>" disabled>
                    </div>
                    <div class=" col-6 mt-2">
                      <label class="font-kecil">Benang (Sebelum)</label>
                      <input type="text" name="sebelum" id="sebelum" class="form-control font-kecil" placeholder="Ketik Benang Sebelum.." value="<?= $cek['jenis_sebelum']; ?>" disabled>
                      <input type="hidden" name="bs_id" id="bs_id">
                    </div>
                    <div class="col-6  mt-2">
                      <label class="font-kecil">Benang (Baru)</label>
                      <input type="text" name="baru" id="baru" class="form-control font-kecil" placeholder="Ketik  Benang Baru.." value="<?= $cek['jenis_baru']; ?>" disabled>
                      <input type="hidden" name="bb_id" id="bb_id">
                    </div>
                    <div class="col-12">
                      <label for="teknisi" class="form-label font-kecil">Petugas </label>
                      <input name="teknisi" id="teknisi" class="form-control font-kecil" placeholder="Ketik Nama Teknisi" disabled>
                    </div>
                  </div>
                </div>
                <div class="col-md-7">
                  <?php
                  $kategori_sekarang = '';
                  foreach ($tindakan as $row) :

                    if ($kategori_sekarang != $row->id_kategori) :
                      $kategori_sekarang = $row->id_kategori;
                  ?>
                      <br>
                      <b><?= $row->kode_kategori . '. ' . $row->nama_kategori ?></b><br>
                    <?php endif; ?>

                    <input type="checkbox" name="tindakan[]" value="<?= $row->id_tindakan ?>" <?= in_array($row->id_tindakan, $checked) ? 'checked' : '' ?> disabled>

                    <?= $row->nama_tindakan ?><br>

                  <?php endforeach; ?>
                </div>
              </div>
              <?php if ($this->session->userdata('cekdowntime') == 1) : ?>
                <?php if ($detail['status'] == 0) : ?>
                  <div class="row mt-3">
                    <div class="col-md-4" style="text-align: center;">
                      <a href="<?= base_url('instruksi/status_ok/' . $id_halaman); ?>" class="btn btn-sm btn-success text-dark"> SELESAI</a>
                      <a href="#" data-id="<?= $detail['id']; ?>" data-url="<?= base_url('instruksi/hapus_tindakan/' . $id_halaman); ?>" class="btn btn-warning btn-sm text-dark status_cansel" title="Reset Data">
                        Reset Data
                      </a>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endif; ?>



            <?php endif; ?>




          </div>


        </div>

      </div>
    </div>
  </div>

</div>
<!-- / Content -->

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
        <div class="text-secondary" id="message">Data Ini Ingin Di Reset ?</div>
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



<div class="modal fade" id="confirmModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        ⚠️ Pastikan semua data sudah benar! <br>
        Data yang sudah disimpan <b>tidak dapat diubah</b>.
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Batal
        </button>

        <button type="button" id="confirmSubmit" class="btn btn-info">
          Ya, Simpan
        </button>
      </div>

    </div>
  </div>
</div>




<div class="modal fade" id="basicModal-kesimpulan" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Keterangan Petugas</h5>
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
<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery-ui.min.js"></script>
<script src="<?= base_url(); ?>/assets/tagify/tagify.min.js"></script>


<!-- modal -->

<script>
  $(document).ready(function() {


    $("#sebelum").autocomplete({
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
        $("#bs_id").val(ui.item.id);
      },
      minLength: 1
    });

    $("#baru").autocomplete({
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
        $("#bb_id").val(ui.item.id);
      },
      minLength: 1
    });
    $(document).on('click', '.kesimpulan', function() {
      var data = $(this).data("id");
      if (data) {
        $("#basicModal-kesimpulan").modal("show");
        $("#load-kesimpulan").load("<?= base_url(); ?>instruksi/kesimpulan_detail/" + encodeURIComponent(data));
      } else {
        alert("Data tidak valid!");
      }
    });

    document.getElementById('confirmSubmit').addEventListener('click', function() {
      document.querySelector('form').submit();
    });

    $(document).on('click', '.status_cansel', function() {
      var url = $(this).data("url");
      $("#btn-okk").attr("href", url);
      $("#modal-status_cansel").modal("show");
    });


  });
</script>
<script>
  var teknisiTerpilih =
    <?= json_encode(array_map(function ($u) {
      return ['value' => $u['user']];
    }, $user)); ?>;
  $(document).ready(function() {

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
          url: "<?= base_url('instruksi/teknisi'); ?>",
          data: {
            term: value
          },
          success: function(data) {
            try {
              var list = JSON.parse(data).map(item => ({
                value: item.name
              }));

              tagify.settings.whitelist = [
                ...new Set([...whitelistAwal, ...list.map(t => t.value)])
              ];

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

  });
</script>