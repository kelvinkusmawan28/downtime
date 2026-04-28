<form action="<?php echo base_url('Preventif/simpan'); ?>" method="post" enctype="multipart/form-data">
    <div class="col-12">
        <label for="nama_item" class="form-label font-kecil">Kode ITEM</label>
        <input type="text" name="kode" class="form-control font-kecil" id="kode" placeholder="Kode Item">
    </div>
    <div class=" col-12">
        <label for="nama_item" class="form-label font-kecil">Keterangan</label>
        <input type="text" name="keterangan" class="form-control font-kecil" id="keterangan" placeholder="Keterangan" required>
    </div>
    <br>
    <div class="col-3">
        <button class="btn btn-primary w-100" type="submit">Simpan</button>
    </div>
</form>