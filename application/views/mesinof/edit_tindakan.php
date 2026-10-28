<form action="<?php echo base_url('mesin/update_tindakan/' . $detail['id'] . '/' . $id_halaman); ?>" method="post" enctype="multipart/form-data">
    <div class="col-12">
        <label for="tgl" class="form-label font-kecil">Tanggal </label>
        <input type="hidden" name="id" class="form-control font-kecil" id="id" value="<?= $detail['id']; ?>" required>
        <input type="hidden" name="id_downtime" class="form-control font-kecil" id="id_downtime" value="<?= $detail['id_downtime']; ?>" required>
        <input type="date" name="tanggal" class="form-control font-kecil" id="tanggal" value="<?= $detail['tanggal']; ?>" required>
    </div>
    <div class="col-12">
        <label for="tindakan" class="form-label font-kecil">Tindakan</label>
        <textarea class="form-control font-kecil" name="tindakan" id="tindakan" style="height: 100px"><?= htmlspecialchars($detail['tindakan']); ?></textarea>
    </div>

    <div class="col-12">
        <label for="subjek" class="form-label font-kecil">Jam Mulai</label>
        <input type="time" name="jam_start" id="jam_start" class="form-control font-kecil" placeholder="Jam Mulai" value="<?= $detail['jam_start']; ?>" required>
    </div>

    <div class="col-12">
        <label for="subjek" class="form-label font-kecil">Jam Selesai</label>
        <input type="time" name="jam_end" id="jam_end" class="form-control font-kecil" placeholder="Jam Selesai" value="<?= $detail['jam_end']; ?>" required>
    </div>

    <div class="col-12">
        <span style="font-size: 11px; color:red;"> 📌 Ceklis <i class="fa fa-check"></i> Untuk Menghapus Foto/Gambar.</span><br>
        <label class="form-label font-kecil">File Tersedia:</label><br>
        <?php
        if (!empty($detail['file'])) {
            $files = json_decode($detail['file'], true);
            foreach ($files as $index => $file) {
                echo '<div class="form-check mb-1">';
                echo '<input class="form-check-input" type="checkbox" name="hapus_file[]" value="' . htmlspecialchars($file) . '" id="hapusFile' . $index . '">';
                echo '<label class="form-check-label me-2" for="hapusFile' . $index . '">';
                echo '<a href="' . base_url('assets/img/upload/' . $file) . '" target="_blank">' . htmlspecialchars($file) . '</a>';
                echo '</label>';
                echo '<input type="text" name="rename_file[]" class="form-control form-control-sm d-inline-block" style="width: 200px; " placeholder="Rename File ..." />';
                echo '<input type="hidden" name="nama_file_asli[]" value="' . htmlspecialchars($file) . '">';
                echo '</div>';
            }
        } else {
            echo "<small>Tidak ada file.</small>";
        }
        ?>
    </div>

    <div class="col-12">
        <label for="file_upload" class="form-label font-kecil">Upload File Baru</label>
        <input type="file" name="file_upload[]" class="form-control font-kecil" id="file_upload" multiple>

    </div>

    <div class="col-12">
        <label for="teknisi" class="form-label font-kecil">Teknisi</label>
        <input name="teknisi" id="teknisi" class="form-control font-kecil" placeholder="Ketik Nama Teknisi">
    </div>
    <br>
    <div class="col-3">
        <button class="btn btn-primary w-100 font-kecil" type="submit">Update Tindakan</button>
    </div>
</form>
<script>
    var teknisiTerpilih =
        <?= json_encode(array_map(function ($u) {
            return ['value' => $u['user']];
        }, $user)); ?>;
</script>