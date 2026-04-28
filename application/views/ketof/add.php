<form action="<?php echo base_url('ketof/simpan'); ?>" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-6">
            <label for="remark" class="form-label font-kecil">Lama </label>
            <input type="text" name="lama" class="form-control font-kecil" id="lama" required>
        </div>
        <div class="col-6">
            <label for="remark" class="form-label font-kecil">Code </label>
            <input type="text" name="code" class="form-control font-kecil" id="code" required>
        </div>

    </div>

    <div class="row">
        <div class="col-6">
            <label for="remark" class="form-label font-kecil">Reason </label>
            <input type="text" name="reason" class="form-control font-kecil" id="reason" required>
        </div>
        <div class="col-4">
            <label for="remark" class="form-label font-kecil">Warna </label>
            <input type="color" id="clr" onchange="convertToRGB(this.value)">
            <input type="text" name="clr_rgb" id="clr_rgb">

            <script>
                function convertToRGB(hex) {
                    let r = parseInt(hex.substring(1, 3), 16);
                    let g = parseInt(hex.substring(3, 5), 16);
                    let b = parseInt(hex.substring(5, 7), 16);

                    let rgb = r + "," + g + "," + b;
                    document.getElementById("clr_rgb").value = rgb;
                }
            </script>
        </div>

    </div>



    <div class="row">
        <label for="remark" class="form-label font-kecil">To Dept : </label>
        <div class="col-6">
            <?php $no = 0;
            $nox = 0;
            $jml = $jmldept / 2;
            foreach ($dept_option as $dept) : $no++; ?>
                <?php if ($no % $jml == 0 && $nox == 0) {
                    $nox = 1; ?>
        </div>
        <div class="col-6">
        <?php } ?>

        <label class="form-check mb-1">
            <input class="form-check-input" type="checkbox" name="sp[]" value="<?= $dept['dept_id']; ?>">
            <span class="form-check-label"><?= $dept['departemen']; ?></span>
        </label>
    <?php endforeach; ?>
        </div>
    </div>
    <br>
    <div class="col-3">
        <button class="btn btn-primary w-100" type="submit">Simpan</button>
    </div>
</form>