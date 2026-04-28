<form action="<?php echo base_url('kerusakan/simpan'); ?>" method="post" enctype="multipart/form-data">
    <div class="col-12">
        <label for="remark" class="form-label font-kecil">Nama Kerusakan </label>
        <input type="hidden" name="dept_id" id="dept_id" class="form-control font-kecil" value="<?= $dept; ?>">
        <input type="text" name="remark" class="form-control font-kecil" id="remark" required>
    </div>
    <br>
    <div class="col-3">
        <button class="btn btn-primary w-100" type="submit">Simpan</button>
    </div>
</form>