<form action="<?php echo base_url('spekmesin/update'); ?>" method="post" enctype="multipart/form-data">
    <div class="col-12">
        <label for="mach_id" class="form-label font-kecil">Kode Mesin </label>
        <input type="text" name="mach_id" class="form-control font-kecil" id="mach_id" placeholder="Kode Mesin" value="<?= $detail['mach_id']; ?>" readonly>
    </div>
    <div class="col-12">
        <label for="mach_id" class="form-label font-kecil">Nomer Mesin </label>
        <input type="text" name="mach_no" class="form-control font-kecil" id="mach_no" placeholder="Nomor Mesin" value="<?= $detail['mach_no']; ?>" required>
    </div>
    <div class="col-12">
        <label for="mach_id" class="form-label font-kecil">Spek Mesin </label>
        <input type="text" name="mach_name" class="form-control font-kecil" id="mach_name" placeholder="Spek Mesin" value="<?= $detail['mach_name']; ?>" required>
    </div>
    <br>
    <div class="col-3">
        <button class="btn btn-primary w-100" type="submit">Update</button>
    </div>
</form>