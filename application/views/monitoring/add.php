<style>
    .modal-content {
        border-radius: 15px;
        border: none;
        background-color: #f8f9fa;
    }


    .bg-soft-danger {
        background-color: #ffe5e5;
        border: 1px solid #ffcccc;
    }

    .bg-soft-success {
        background-color: #e6ffed;
        border: 1px solid #c2ffd8;
    }


    .dot-animation {
        height: 8px;
        width: 8px;
        background-color: #dc3545;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
        animation: blink 1s infinite;
    }

    @keyframes blink {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.3;
        }

        100% {
            opacity: 1;
        }
    }


    .timeline-item {
        border-left: 4px solid #0d6efd !important;
        transition: transform 0.2s ease;
    }

    .timeline-item:hover {
        transform: translateY(-3px);
    }

    .btn-outline-primary {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h6 class="text-secondary mb-1">Informasi Mesin</h6>
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-cpu me-2"></i><?= $detail['mach_no']; ?>
                <span class="text-muted fw-normal fs-6">(<?= $detail['mach_name']; ?>)</span>
            </h5>
        </div>
        <div class="text-end">
            <span class="badge bg-light text-danger border border-danger px-3 py-2">
                <i class="bi bi-building me-1"></i> <?= $detail['departemen']; ?>
            </span>
        </div>
    </div>

    <div class="history-timeline">
        <div class="timeline-item mb-4 shadow-sm border-0 card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-muted small mb-1">
                            <i class="bi bi-calendar3 me-1"></i> <?= format_tanggal_indonesia($detail['tanggal']); ?>
                        </div>
                        <h6 class="fw-bold text-danger mb-0">
                            <?= $detail['remark']; ?>
                        </h6>
                    </div>
                    <div class="d-flex flex-column align-items-end">

                        <?php if ($detail['status'] == 0) : ?>
                            <?php $start_time = strtotime($detail['kerusakan_mulai']) * 1000; ?>

                            <span class="font-bold text-success updateon mb-1" style="font-size:12px;" data-start="<?= $start_time ?>">
                                Loading...
                            </span>

                            <span class="badge rounded-pill bg-soft-danger text-danger px-3">
                                <span class="dot-animation"></span> Progress ..
                            </span>

                        <?php else : ?>

                            <span class="badge rounded-pill bg-light text-danger px-3">
                                <i class="bi bi-check-circle me-1"></i> Waiting List ..
                            </span>

                        <?php endif; ?>

                    </div>
                </div>
                <?php if ($detail['status'] == 0) : ?>
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <p class="small text-secondary mb-1 fw-bold text-uppercase" style="font-size: 10px;">Keterangan:</p>
                        <p class="mb-0 text-dark" style="font-size: 14px;"><?= $detail['keterangan']; ?></p>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-3">
                        <p class="small text-secondary mb-1 fw-bold text-uppercase" style="font-size: 10px;">link foto:</p>
                        <?php
                        $path_files = json_decode($detail['path_file'], true);
                        $file_names = json_decode($detail['file'], true);

                        if (!empty($path_files)) {
                            foreach ($path_files as $index => $path) {
                                $filename = isset($file_names[$index]) ? $file_names[$index] : 'File-' . ($index + 1);
                                echo '<a href="' . base_url($path) . '" target="_blank" 
                                class="btn btn-sm btn-outline-primary rounded-pill d-inline-flex align-items-center gap-1 file-link">
                                <i class="bi bi-paperclip"></i> 
                                <span class="text-truncate d-inline-block" style="max-width:130px;">' . $filename . '</span>
                              </a>';
                            }
                        }
                        ?>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="small text-muted mb-0">
                                <i class="bi bi-person-badge me-1"></i> Petugas: <strong><?= $detail['user']; ?></strong>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>