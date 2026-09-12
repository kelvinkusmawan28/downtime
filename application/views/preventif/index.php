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
                            <a href="<?= base_url('dashboard'); ?>" class="btn btn-sm btn-warning"> <i class="fa fa-arrow-left me-2"></i> Kembali</a>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <div class="row align-items-end justify-content-between">
                        <div class="col-lg-2">

                        </div>


                        <div class="col-lg-6">
                            <div class="row justify-content-end">

                                <div class="col-lg-5">

                                </div>

                                <div class="col-lg-5">

                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="row p-3 mt-2" style="border:1px solid #4274D9; border-radius :10px; background-color:#F8F9FA">
                        <div class="card shadow-sm border-0 mb-3">
                            <div class="card-body p-3">
                                <div class="row align-items-end g-1">

                                    <div class="col-lg-3 col-md-3">
                                        <a href="#" class="btn btn-outline-primary font-kecil " id="tambahdata">
                                            Tambah Data
                                        </a>
                                    </div>
                                    <div class="col-lg-9 col-md-2" style="text-align: right;">

                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="row align-items-center">

                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-3 align-items-center bg-light p-2 rounded">
                                    <div class="me-2">
                                        <i class="fas fa-chart-bar text-muted me-1"></i>
                                        <span class="text-muted small fw-bold text-uppercase" style="font-size: 11px;">Data:</span>
                                    </div>
                                    <div>
                                        <span class="text-muted small fw-medium">Total Record:</span>
                                        <span class="badge bg-primary text-white p-1.5" id="totalFiltered" style="font-size: 12px;"><?= $record['total']; ?></span>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body font-kecil ">
                    <div class="table-responsive">
                        <table id="itemTable" class="table datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Departemen</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 0;
                                foreach ($header as $key) : $no++; ?>
                                    <tr>
                                        <td><?= $no; ?></td>
                                        <td><?= $key['kode']; ?></td>
                                        <td><?= $key['keterangan']; ?></td>
                                        <td>
                                            <a href=" <?= base_url('preventif/detail/') . $key['id'] ?>" class="btn btn-primary btn-sm font-kecil "><span class="ml-1" style="color: aliceblue;"> View </span></a>
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
<!-- / Content -->

<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Input Kode Item Preventif</h5>
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
    $(document).ready(function() {
        $("#tambahdata").click(function(e) {
            const url = "<?= base_url('preventif/tambahdata/'); ?>";
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