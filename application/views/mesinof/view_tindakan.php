<div class="container py-3 px-4 rounded shadow-sm bg-white font-kecil" style="border: 1px solid black;">
    <h5 class="mb-4 fw-bold text-primary">Detail Tindakan Perbaikan</h5>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Tanggal</label>
            <div class="form-control-plaintext"><?= format_tanggal_indonesia($detail['tanggal']); ?></div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Jam Mulai</label>
            <div class="form-control-plaintext"><?= $detail['jam_start']; ?></div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Jam Selesai</label>
            <div class="form-control-plaintext"><?= $detail['jam_end']; ?></div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Waktu Perbaikan</label>
            <div class="form-control-plaintext"><?= format_downtime($detail['downtime']); ?></div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Tindakan</label>
        <textarea class="form-control" style="height: 100px ; color:red ;" disabled><?= htmlspecialchars($detail['tindakan']); ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Foto/Gambar Hasil Perbaikan</label>
        <div>
            <?php
            $path_files = json_decode($detail['path_file'], true);
            $file_names = json_decode($detail['file'], true);

            if (!empty($path_files)) {
                foreach ($path_files as $index => $path) {
                    $filename = isset($file_names[$index]) ? $file_names[$index] : basename($path);
                    echo '<a href="' . base_url($path) . '" target="_blank" class="d-block text-decoration-underline text-primary">' . $filename . '</a>';
                }
            } else {
                echo '<span class="text-muted">Tidak ada file</span>';
            }
            ?>
        </div>
    </div>

    <!-- Teknisi -->
    <div class="mb-3">
        <label class="form-label fw-semibold">Teknisi Perbaikan</label>
        <div class="table-responsive">
            <table class="table table-bordered  ">
                <thead class="table">
                    <tr>
                        <th class="text-primary" style="font-size:8px;">No</th>
                        <th class="text-primary" style="font-size:8px;">Nama Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($user as $data) : ?>
                        <tr>
                            <td class="text-dark" style="font-size:10px;"><?= $no++; ?></td>
                            <td class="text-dark" style="font-size:10px;"><?= $data['user']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($user)) : ?>
                        <tr>
                            <td colspan="2" class="text-center text-muted" style="color:red;">Belum ada teknisi</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>