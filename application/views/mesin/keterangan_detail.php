<form action="<?php echo base_url('mesin/update_keterangan_detail/' . $id_halaman); ?>" method="post" enctype="multipart/form-data">
    <div class="col-12">
        <label for="tgl" class="form-label font-kecil">Kerusakan Detail</label>
        <input type="hidden" name="id" class="form-control font-kecil" id="id" value="<?= $detail['id']; ?>">
        <textarea class="form-control font-kecil" name="keterangan" style="height: 100px"><?= htmlspecialchars($detail['keterangan']); ?></textarea>
    </div>
    </div>
    <br>
    <div class="col-3">
        <button class="btn btn-primary w-100" type="submit">Simpan</button>
    </div>

</form>