<form action="<?php echo base_url('Preventif/simpan_item/' . $id_halaman); ?>" method="post" enctype="multipart/form-data">
    <div class="col-12">
        <label for="nama_item" class="form-label font-kecil">Nama Item </label>
        <input type="hidden" name="id_preventif" id="id_preventif" value="<?= $id_halaman; ?>">
        <input type="text" name="nama_item" class="form-control font-kecil" id="nama_item" placeholder="Nama Item" required>
    </div>
    <div class="col-12">
        <label for="mach_id" class="form-label font-kecil">Standar </label>
        <input type="text" name="standar" class="form-control font-kecil" id="standar" placeholder="Standar Pemeriksaan" required>
    </div>
    <div class="col-12">
        <label for="kategori" class="form-label font-kecil">Kategori Pemeriksaan </label>
        <select name="kategori" id="kategori" class="form-select font-kecil mt-0">
            <option value="harian">Harian</option>
            <option value="mingguan">Per Minggu</option>
            <option value="bulanan">Per Bulan</option>
            <option value="3 bulan">Per 3 Bulan</option>
            <option value="6 bulan">Per 6 Bulan</option>
            <option value="tahunan">Per Tahun</option>
            <option value="setahun_setengah">Setengah Tahun</option>
        </select>
    </div>
    <br>
    <div class="col-3">
        <button class="btn btn-primary w-100" type="submit">Simpan</button>
    </div>
</form>