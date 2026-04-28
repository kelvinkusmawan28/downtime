<form action="<?php echo base_url('spekmesin/update/' . $detail['mach_id']); ?>" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-6">
            <label for="mach_id" class="form-label font-kecil">Kode Mesin </label>
            <input type="text" name="mach_id" class="form-control font-kecil" id="mach_id" placeholder="Kode Mesin" value="<?= $detail['mach_id']; ?>" readonly>
        </div>
        <div class="col-6">
            <label for="mach_id" class="form-label font-kecil">Nomer Mesin </label>
            <input type="text" name="mach_no" class="form-control font-kecil" id="mach_no" placeholder="Nomor Mesin" value="<?= $detail['mach_no']; ?>" required>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <label for="mach_id" class="form-label font-kecil">Spek Mesin </label>
            <input type="text" name="mach_name" class="form-control font-kecil" id="mach_name" placeholder="Spek Mesin" value="<?= $detail['mach_name']; ?>" required>
        </div>
        <div class="col-6">
            <label for="mach_id" class="form-label font-kecil">Kapasitas Mesin </label>
            <input type="text" name="kapasitas" class="form-control font-kecil" id="kapasitas" placeholder="Kapasitas Mesin" value="<?= $detail['kapasitas']; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <label for="mach_id" class="form-label font-kecil">Name Mesin </label>
            <input type="text" name="name" class="form-control font-kecil" id="name" placeholder="Name Mesin" value="<?= $detail['name']; ?>" required>
        </div>
        <div class="col-6">
            <label for="preventif_kode" class="form-label font-kecil">Group Mesin </label>
            <input type="text" name="preventif_kode" class="form-control font-kecil" id="preventif_kode" value="<?= $detail['preventif_kode']; ?>" required>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <label for="idle" class="form-label font-kecil">Status Mesin</label>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="idle" name="idle" value="0" <?= ($detail['idle'] == 0) ? 'checked' : ''; ?> />
                <label class="form-check-label" for="idle">
                    <?= ($detail['idle'] == 0) ? 'Aktif' : 'Tidak Aktif'; ?>
                </label>
            </div>
        </div>
        <div class=" col-6">
            <label for="preventif_kode" class="form-label font-kecil">Als Mesin </label>
            <input type="text" name="als" class="form-control font-kecil" id="als" value="<?= $detail['als']; ?>">
        </div>
    </div>

    <div class="col-12">
        <span style="font-size: 11px; color:red;"> 📌 Ceklis <i class="bi bi-patch-check"></i> Untuk Menghapus Foto.</span><br>
        <label class="form-label font-kecil">File Tersedia:</label><br>

        <?php
        if (!empty($detail['file_spek'])) {
            $files = json_decode($detail['file_spek'], true);
            foreach ($files as $index => $file) {
                echo '<div class="form-check mb-1">';
                echo '<input class="form-check-input" type="checkbox" name="hapus_file[]" value="' . htmlspecialchars($file) . '" id="hapusFile' . $index . '">';
                echo '<label class="form-check-label me-2" for="hapusFile' . $index . '">';
                echo '<a href="' . base_url('assets/img/upload_spek/' . $file) . '" target="_blank">' . htmlspecialchars($file) . '</a>';
                echo '</label>';
                echo '<input type="text" name="rename_file[]" class="form-control form-control-sm d-inline-block" style="width: 200px; " placeholder="Rename File ..." />';
                echo '<input type="hidden" name="nama_file_asli[]" value="' . htmlspecialchars($file) . '">';
                echo '</div>';
            }
        } else {
            echo "<small>Foto Mesin Masih Kosong.</small>";
        }
        ?>
    </div>

    <div class="col-12">
        <label for="file_upload" class="form-label font-kecil">Upload Foto Baru</label>
        <input type="file" name="file_upload[]" class="form-control font-kecil" id="file_upload" multiple>

    </div>
    <br>
    <div class="col-3">
        <button class="btn btn-primary w-100" type="submit">Update</button>
    </div>
</form>