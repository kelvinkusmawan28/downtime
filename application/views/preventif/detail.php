<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row ">
        <div class="col-lg-12 ">

            <div class="card h-100">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="mb-1 me-2"><?= $title; ?></h5>
                        </div>
                        <div class="col-lg-6" style="text-align: right;">
                            <a href="<?= base_url('preventif'); ?>" class="btn btn-sm btn-warning"> <i class="fa fa-arrow-left me-2"></i> Kembali</a>
                        </div>
                    </div>


                </div>
                <div class="card-body font-kecil ">
                    <div class=" mt-2 row">
                        <div class="col-lg-5">
                            <?= $this->session->flashdata('message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <label class="font-kecil font-bold text-azure text-primary">Kategori Preventif</label>
                            <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                            <select name="filter_kategori" id="filter_kategori" class="form-select font-kecil mt-0">
                                <option value="all" <?= $filter_kategori == 'all' ? 'selected' : '' ?>>Semua Kategori</option>
                                <option value="harian" <?= $filter_kategori == 'harian'  ? 'selected' : '' ?>>Harian</option>
                                <option value="mingguan" <?= $filter_kategori == 'mingguan'  ? 'selected' : '' ?>>Per Minggu</option>
                                <option value="bulanan" <?= $filter_kategori == 'bulanan'  ? 'selected' : '' ?>>Per Bulan</option>
                                <option value="3 bulan" <?= $filter_kategori == '3 bulan'  ? 'selected' : '' ?>>Per 3 Bulan</option>
                                <option value="6 bulan" <?= $filter_kategori == '6 bulan' ? 'selected' : '' ?>>Per 6 Bulan</option>
                                <option value="tahunan" <?= $filter_kategori == 'tahunan'  ? 'selected' : '' ?>>Per Tahun</option>
                                <option value="setahun_setengah" <?= $filter_kategori == 'setahun_setengah'  ? 'selected' : '' ?>>Setahun Setengah</option>
                            </select>
                        </div>
                        <div class="col-lg-3" style="padding-top: 17px; ">
                            <a href="#" class="btn btn-outline-primary font-kecil " id="tambahdata">
                                Tambah Data
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="detTable" class="tabel datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Item Pemeriksaan</th>
                                    <th>Standar Pemeriksaan</th>
                                    <th>Kategori</th>
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
                <h5 class="modal-title" id="exampleModalLabel1">Input Data Mesin Baru</h5>
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
                <h5 class="modal-title">Edit Data</h5>
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
<div class="modal modal-blur fade" id="modal-cek" tabindex="-1" role="dialog" aria-hidden="true">
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
                <h3>Tambah Item ?</h3>
                <div class="text-secondary" id="message">Tambah Data Preventif ?</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col"><a id="btn-okdong" href="#" class="btn btn-info w-100">
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

<div class="modal modal-blur fade" id="modal-error" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-warning"></div>
            <div class="modal-body text-center py-4">
                <svg class="icon mb-2 text-warning icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 9v2m0 4v.01" />
                    <path d="M12 5a7 7 0 1 0 0 14a7 7 0 0 0 0 -14z" />
                </svg>
                <h3>Mohon Maaf,</h3>
                <div class="text-secondary" id="error-message">Pesan error akan muncul di sini</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <a href="#" class="btn btn-warning w-100" data-bs-dismiss="modal">Tutup</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>
<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery-ui.min.js"></script>

<!-- modal -->
<script>
    function showError(message) {
        $("#error-message").text(message);
        $("#modal-error").modal("show");
    }
    $(document).ready(function() {
        $("#tambahdata").click(function(e) {
            const url = "<?= base_url('preventif/tambahdata_item/' . $id); ?>";
            $("#basicModal").modal("show");
            $("#loadforminput").load(url);

        });



        $(document).on('click', '.edit', function() {
            var data = $(this).data("id");
            if (data) {
                $("#basicModal-edit").modal("show");
                $("#loadforminput-edit").load("<?= base_url(); ?>spekmesin/edit/" + encodeURIComponent(data));
            } else {
                alert("Data tidak valid!");
            }
        });

        $(document).on('click', '.hapus', function() {
            var url = $(this).data("url");
            $("#btn-ok").attr("href", url);
            $("#modal-hapus").modal("show");
        });
        $(document).on('click', '.hapus1', function() {
            var url = $(this).data("url");
            $("#btn-okdong").attr("href", url);
            $("#modal-cek").modal("show");
        });

    });
</script>

<script>
    $(document).ready(function() {

        var table = $('#detTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "<?= base_url('preventif/item') ?>",
                type: "POST",
                data: function(d) {

                    d.id = $('#id').val();
                    d.kategori = $('#filter_kategori').val();
                }
            },
            columns: [{
                    data: 'no'
                },
                {
                    data: 'item'
                },
                {
                    data: 'standar'
                },
                {
                    data: 'kategori'
                },
                {
                    data: 'aksi'
                }
            ]

        });


        $('#filter_kategori').change(function() {
            table.ajax.reload();
        });
    });
</script>