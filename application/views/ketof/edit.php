<form action="<?php echo base_url('ketof/update'); ?>" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-6">
            <label for="remark" class="form-label font-kecil">Lama </label>
            <input type="text" name="lama" class="form-control font-kecil" id="lama" value="<?= $detail['lama']; ?>">
            <input type="hidden" name="id" class="form-control font-kecil" id="id" value="<?= $detail['id']; ?>">
        </div>
        <div class="col-6">
            <label for="remark" class="form-label font-kecil">Code </label>
            <input type="text" name="code" class="form-control font-kecil" id="code" value="<?= $detail['code']; ?>">
        </div>

    </div>

    <div class="row">
        <div class="col-6">
            <label for="remark" class="form-label font-kecil">Reason </label>
            <input type="text" name="reason" class="form-control font-kecil" id="reason" value="<?= $detail['reason']; ?>">
        </div>
        <div class="col-4">
            <label for="remark" class="form-label font-kecil">Warna </label>
            <input type="color" id="clr" onchange="convertToRGB(this.value)">
            <input type="text" name="clr_rgb" id="clr_rgb" value="<?= $detail['clr']; ?>">
            <script>
                function rgbToHex(rgb) {
                    let split = rgb.split(",");
                    let r = parseInt(split[0]).toString(16).padStart(2, '0');
                    let g = parseInt(split[1]).toString(16).padStart(2, '0');
                    let b = parseInt(split[2]).toString(16).padStart(2, '0');

                    return "#" + r + g + b;
                }
                document.addEventListener("DOMContentLoaded", function() {
                    let rgb = document.getElementById("clr_rgb").value;
                    if (rgb) {
                        document.getElementById("clr").value = rgbToHex(rgb);
                    }
                });

                function convertToRGB(hex) {
                    let r = parseInt(hex.substring(1, 3), 16);
                    let g = parseInt(hex.substring(3, 5), 16);
                    let b = parseInt(hex.substring(5, 7), 16);

                    document.getElementById("clr_rgb").value = r + "," + g + "," + b;
                }
            </script>
        </div>

    </div>
    <div class="row">
        <label for="remark" class="form-label font-kecil">To Depp : </label>
        <div class="col-6">
            <?php
            $selected_dept = str_split($detail['sp'], 2);
            ?>
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
            <input class="form-check-input" type="checkbox" name="sp[]" value="<?= $dept['dept_id']; ?>" <?= in_array($dept['dept_id'], $selected_dept) ? 'checked' : '' ?>>
            <span class="form-check-label"><?= $dept['departemen']; ?></span>
        </label>
    <?php endforeach; ?>
        </div>
    </div>
    <br>
    <div class="col-3">
        <button class="btn btn-primary w-100" type="submit">Update</button>
    </div>
</form>