<form action="<?php echo base_url('mesin/update_kesimpulan_detail/' . $id_halaman); ?>" method="post" enctype="multipart/form-data">
    <div class="col-12">
        <label for="tgl" class="form-label font-kecil">Kesimpulan</label>
        <input type="hidden" name="id" class="form-control font-kecil" id="id" value="<?= $detail['id']; ?>">
        <textarea class="form-control font-kecil" name="kesimpulan" style="height: 100px"><?= htmlspecialchars($detail['kesimpulan']); ?></textarea>
    </div>
    </div>
    <br>
    <div class="col-3">
        <button class="btn btn-primary w-100" type="submit">Simpan</button>
    </div>

</form>