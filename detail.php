<div class="pagetitle">
    <div class="row">
        <div class="mt-1 col-lg-8" style="text-align: left;">
            <h1> 🔐<?= $title; ?></h1>
            <nav>
                <?= $this->session->flashdata('message'); ?>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item active"><?= $title; ?></li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-4 mt-1" style="text-align:right;">
            <a href="<?= base_url('event'); ?>" class="btn btn-sm btn-warning"> <i class="bi bi-arrow-left-square"></i> Kembali</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="border: 1px solid darkgray;">
                <?= $this->session->flashdata('message'); ?>
                <div class="card-body font-kecil p-1">
                    <div class="row mb-1">
                        <div class="col-md-4 mb-0 border-bottom pb-2">
                            <div class="text-muted fw-semibold">📑 Nomor Dokumen</div>
                            <div><?= $detail['nomor']; ?></div>
                        </div>
                        <div class="col-md-4 mb-0 border-bottom pb-2">
                            <div class="text-muted fw-semibold">🏢 Departemen</div>
                            <div><?= $detail['departemen']; ?></div>
                        </div>
                        <div class="col-md-4 mb-0 border-bottom pb-2">
                            <div class="text-muted fw-semibold">📅 Tanggal</div>
                            <div><?= format_tanggal_indonesia($detail['tgl']); ?></div>
                        </div>
                        <div class="col-md-4 mb-0 border-bottom pb-2">
                            <div class="text-muted fw-semibold">📝 Subjek</div>
                            <div><?= $detail['subjek']; ?></div>
                        </div>
                        <div class="col-md-4  mb-0 border-bottom ">
                            <div class="text-muted fw-semibold">🎯 Tujuan</div>
                            <div><?= $detail['tujuan']; ?></div>
                        </div>
                        <div class="col-md-4  mb-0 border-bottom ">
                            <div class="text-muted fw-semibold">🔓 Status</div>
                            <div> <?php if ($detail['status'] == 0) : ?>
                                    <span class="badge bg-primary">PROGRES</span>
                                <?php else : ?>
                                    <span class="badge bg-success">CLOSE</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4  mb-0 border-bottom ">
                            <div class="text-muted fw-semibold">👨 Peminta</div>
                            <div class="font-bold"><?= $detail['nama_peminta']; ?></div>
                        </div>
                        <div class="col-md-4  mb-0 border-bottom ">
                            <div class="text-muted fw-semibold">😁 Register</div>
                            <div class="font-bold"><?= $detail['name']; ?></div>
                            <div class="font-bold">
                                <?php if (!empty($detail['created_on'])) : ?>
                                    <?= format_tanggal_indonesia_waktu($detail['created_on']); ?>
                                <?php else : ?>
                                    <i>...</i>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4  mb-0 border-bottom ">
                            <div class="text-muted fw-semibold">📌 Kesimpulan</div>
                            <div class="font-bold">
                                <textarea class="form-control font-kecil" rows="3" disabled><?= htmlspecialchars($detail['kesimpulan']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-md-3">
            <?php if ($detail['status'] == 0) : ?>
                <a href="#" class="btn btn-outline-primary " id="tambahdata" data-bs-toggle="modal" data-bs-target="#basicModal">
                    <i class=" bi bi-folder-plus"></i>
                </a>
            <?php endif; ?>


            <!-- <div id="detail-nama" class="mt-2 font-kecil"></div> -->
            <div id="table-nama-event" class="mt-3 font-kecil">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th style="text-align: center;">No</th>
                            <th style="text-align: center;">Nama Event</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0;
                        foreach ($detail_event as $data) : $no++; ?>
                            <tr>
                                <td style="text-align: center;"><?= $no; ?></td>
                                <td style="text-align: center;">
                                    <a href="javascript:void(0)" class="detail-link" data-id="<?= $data['id']; ?>" style="text-decoration: underline;">
                                        <?= $data['nama_event']; ?>
                                    </a>

                                </td>
                                <td style="text-align: center;">
                                    <?php if ($detail['status'] == 0) : ?>
                                        <a href="#" data-id="<?= $data['id']; ?>" class="btn btn-outline-success btn-sm font-kecil edit-detail" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="#" class=" btn btn-outline-danger font-kecil hapus-detail" data-id="<?= $data['id']; ?>" data-url="<?= base_url(); ?>event/hapus_detail/<?= $data['id']; ?>/<?= $id_halaman; ?>" title=" Hapus Data Event">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-9 font-kecil">
            <div class="form-group mb-2">
                <label for="detail-tgl_event"><b>Event</b></label>
                <input type="text" id="detail-nama" class="form-control font-kecil" style="color: blue;" readonly>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="detail-tgl_event"><b>Tanggal Event</b></label>
                    <input type="text" id="detail-tgl_event" class="form-control font-kecil" readonly>

                </div>
                <div class="col-6">
                    <label for="detail-tgl_event"><b>User Event</b></label>
                    <input type="text" id="detail-user_event" class="form-control font-kecil" readonly>
                </div>
            </div>
            <div class="form-group mb-2">
                <label for="detail-remark"><b>Remark</b></label>
                <textarea id="detail-remark" class="form-control font-kecil " rows="15" readonly></textarea>
                <div id="detail-file" class="mt-2 font-kecil"></div>
            </div>
        </div>
    </div>


    </div><!-- End Left side columns -->

    <!-- Right side columns -->
</section>

<div class="modal fade" id="largeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="loadformview"></div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="basicModal-edit_detail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Event Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="loadforminput-edit_detail"></div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-hapus_detail" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <div class="col"><a id="btn-ok_detail" href="#" class="btn btn-danger w-100">
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
<script src="<?= base_url(); ?>/assets/vendor/chart.js/jquery-3.6.4.min.js"></script>


<script>
    $(document).ready(function() {
        // nama id hreff modal
        $("#tambahdata").click(function() {
            $("#largeModal").modal("show");
            $("#loadformview").load("<?= base_url('event/tambah_event/' . $id_halaman); ?>");

        });



        $(document).on("click", ".edit-detail", function() {
            var data = $(this).data("id");
            var id_halaman = <?= json_encode($id_halaman); ?>;
            if (data) {
                $("#basicModal-edit_detail").modal("show");
                $("#loadforminput-edit_detail").load("<?= base_url(); ?>event/edit_detail/" + encodeURIComponent(data) + "/" + encodeURIComponent(id_halaman));

            } else {
                alert("Data tidak valid!");
            }
        });

        $(document).on("click", ".hapus-detail", function() {
            var url = $(this).data("url");
            $("#btn-ok_detail").attr("href", url);
            $("#modal-hapus_detail").modal("show");
        });

    });
</script>


<script>
    function formatTanggalIndonesia(tgl) {
        const hariIndonesia = {
            'Sunday': 'Minggu',
            'Monday': 'Senin',
            'Tuesday': 'Selasa',
            'Wednesday': 'Rabu',
            'Thursday': 'Kamis',
            'Friday': 'Jumat',
            'Saturday': 'Sabtu'
        };

        const bulanIndonesia = {
            'January': 'Januari',
            'February': 'Februari',
            'March': 'Maret',
            'April': 'April',
            'May': 'Mei',
            'June': 'Juni',
            'July': 'Juli',
            'August': 'Agustus',
            'September': 'September',
            'October': 'Oktober',
            'November': 'November',
            'December': 'Desember'
        };

        const date = new Date(tgl);
        const hari = hariIndonesia[date.toLocaleDateString('en-US', {
            weekday: 'long'
        })];
        const tanggal = date.getDate().toString().padStart(2, '0');
        const bulan = bulanIndonesia[date.toLocaleDateString('en-US', {
            month: 'long'
        })];
        const tahun = date.getFullYear();

        return `${hari}, ${tanggal} ${bulan} ${tahun}`;
    }
    $(document).ready(function() {
        // Ambil ID dari event pertama
        var firstEvent = $('.detail-link').first();
        if (firstEvent.length) {
            var firstId = firstEvent.data('id');
            loadDetail(firstId);
        }


        $(document).on('click', '.detail-link', function() {
            var id = $(this).data('id');
            loadDetail(id);
        });

        function loadDetail(id) {
            $.ajax({
                url: '<?= base_url("event/get_detail/"); ?>' + id,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    const nama = data.nama_event;
                    const remark = data.remark;
                    const tgl_event = data.tgl_event;
                    const user_event = data.user;

                    const fileUrls = JSON.parse(data.path_file);
                    const fileNames = JSON.parse(data.file);

                    $('#detail-nama').val(nama);
                    $('#detail-tgl_event').val(formatTanggalIndonesia(tgl_event));
                    $('#detail-remark').val(remark);
                    $('#detail-user_event').val(user_event);

                    let fileHtml = '';

                    if (fileUrls && fileNames && fileUrls.length > 0) {
                        fileUrls.forEach(function(fileUrl, index) {
                            const fileName = fileNames[index];
                            const isVideo = fileName.match(/\.(mp4|webm|ogg)$/i);

                            const fullFileUrl = '<?= base_url(); ?>' + fileUrl;

                            if (isVideo) {
                                fileHtml += `
                            <div class="text-center mb-3">
                                <div class="mb-2 fw-bold">${fileName}</div>
                                <video controls style="max-width: 100%; max-height: 500px;">
                                    <source src="${fullFileUrl}" type="video/mp4">
                                    Browser kamu tidak mendukung tag video.
                                </video>
                            </div>
                        `;
                            } else {
                                fileHtml += `
                            <div class="mb-2">
                                <a href="${fullFileUrl}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    📎 ${fileName}
                                </a>
                            </div>
                        `;
                            }
                        });
                        $('#detail-file').html(fileHtml);
                    } else {
                        $('#detail-file').html('<em class="text-muted">Tidak ada file terlampir</em>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Gagal load detail:', error);
                    alert('Gagal mengambil data detail. Silakan coba lagi.');
                }
            });
        }



    });
</script>