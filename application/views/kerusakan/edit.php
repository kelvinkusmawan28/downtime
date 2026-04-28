<form action="<?php echo base_url('kerusakan/update'); ?>" method="post" enctype="multipart/form-data">
    <div class="col-12">
        <label for="remark" class="form-label font-kecil">Nama Kerusakan </label>
        <input type="hidden" name="rusak_id" class="form-control font-kecil" id="remark" value="<?= $detail['rusak_id']; ?>" required>
        <input type="text" name="remark" class="form-control font-kecil" id="remark" value="<?= $detail['remark']; ?>" required>
    </div>
    <br>
    <div class="col-3">
        <button class="btn btn-primary w-100" type="submit">Update</button>
    </div>
</form>