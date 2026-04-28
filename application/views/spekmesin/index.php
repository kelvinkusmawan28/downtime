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
                <div class="card-body font-kecil ">
                    <div class=" mt-2 row">
                        <div class="col-lg-5">
                            <?= $this->session->flashdata('message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3" style="padding-bottom: 5px; ">
                            <label class="font-kecil font-bold text-azure">Departemen</label>

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
                        <div class="col-lg-3" style="padding-top: 17px; ">
                            <a href="#" class="btn btn-outline-primary font-kecil " id="tambahdata">
                                Tambah Data
                            </a>
                        </div>

                        <div class="col-lg-3" style="padding-top: 15px; text-align:right;">
                            <a href="<?= base_url('spekmesin/reset_filter') ?>" class="btn btn-primary  font-kecil">
                                <i class="fa-solid fa-rotate me-2"></i> Bersihkan Filter
                            </a>
                        </div>


                    </div>
                    <div class="table-responsive">
                        <table id="spekTable" class="table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode Mesin</th>
                                    <th>No Mesin</th>
                                    <th>Spek Mesin</th>
                                    <th>Group Mesin</th>
                                    <th>Als Mesin</th>
                                    <th>Kapasitas</th>
                                    <th>Status</th>
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

<div class="modal fade" id="modalFile" tabindex="-1">
    <div class="modal-dialog">
        <div class=" modal-content">
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
<script src="<?= base_url(); ?>/assets/vendor/libs/jquery/jquery-ui.min.js"></script>

<!-- modal -->
<script>
    const base_url = "<?= base_url() ?>";

    function showError(message) {
        $("#error-message").text(message);
        $("#modal-error").modal("show");
    }
    $(document).ready(function() {
        $("#tambahdata").click(function(e) {
            const filter_dept = $('#filter').val();

            if (filter_dept === 'all') {
                e.preventDefault();
                showError("Silakan Pilih Departemen Yang Ada 😉");
                return false;
            }
            const url = "<?= base_url('spekmesin/tambahdata/'); ?>" + filter_dept;
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

        $(document).on('click', '.lihat-file', function() {

            let data = $(this).data('files');
            let html = '';

            if (data && data.paths && data.paths.length > 0) {

                let indicators = '';
                let items = '';

                data.paths.forEach(function(path, i) {
                    let name = data.names && data.names[i] ? data.names[i] : 'File';

                    indicators += `
            <button type="button" data-bs-target="#carouselFile" 
                data-bs-slide-to="${i}" 
                class="${i === 0 ? 'active' : ''}">
            </button>
              `;

                    items += `
            <div class="carousel-item ${i === 0 ? 'active' : ''}">
                <p class="text-center">${name}</p>
                <img src="${base_url + path}" 
                     class="d-block w-100"
                     style="max-height:450px; object-fit:contain;">
            </div>
              `;
                });

                html = `
                <div id="carouselFile" class="carousel slide" data-bs-ride="carousel">
                    
                    <div class="carousel-indicators">
                        ${indicators}
                    </div>

                    <div class="carousel-inner">
                        ${items}
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselFile" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#carouselFile" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>

                </div>
                `;

            } else {
                html = '<span style="color:gray">Tidak ada file</span>';
            }

            $('#contentFile').html(html);
            $('#modalFile').modal('show');
        });


    });
</script>

<script>
    $(document).ready(function() {

        var table = $('#spekTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "<?= base_url('spekmesin/filter_spek') ?>",
                type: "POST",
                data: function(d) {
                    d.dept_id = $('#filter').val();
                    console.log(d.dept_id);
                }


            },
            columns: [{
                    data: 'kodemesin',
                    className: 'text-start'
                },
                {
                    data: 'nomesin'
                },
                {
                    data: 'spekmesin'
                },
                {
                    data: 'groupmesin'
                },
                {
                    data: 'alsmesin'
                },
                {
                    data: 'kapasitas'
                },
                {
                    data: 'statusmesin'
                },
                {
                    data: 'aksi'
                }
            ]

        });


        $('#filter').change(function() {
            table.ajax.reload();
        });
    });
</script>