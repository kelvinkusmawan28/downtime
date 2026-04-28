<form action="<?php echo base_url('spekmesin/simpan'); ?>" method="post" enctype="multipart/form-data">
    <div class="col-12">
        <label for="mach_id" class="form-label font-kecil">Nomer Mesin </label>
        <input type="hidden" name="mach_id" class="form-control font-kecil" id="mach_id" value="<?= $dept; ?>" required>
        <input type="text" name="mach_no" class="form-control font-kecil" id="mach_no" placeholder="Nomor Mesin" required>
    </div>
    <div class="col-12">
        <label for="mach_id" class="form-label font-kecil">Spek Mesin </label>
        <input type="text" name="mach_name" class="form-control font-kecil" id="mach_name" placeholder="Spek Mesin" required>
    </div>
    <br>
    <div class="col-3">
        <button class="btn btn-primary w-100" type="submit">Simpan</button>
    </div>
</form>