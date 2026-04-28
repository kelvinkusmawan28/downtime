<form action="<?= base_url('mesin_of/update'); ?>" method="post">

    <div class="row font-kecil">

        <div class="col-md-6">
            <label>User</label>
            <input type="text" class="form-control font-kecil" value="<?= $this->session->userdata('name'); ?>" readonly>
        </div>

        <div class="col-md-6">
            <label>Kode Departemen</label>
            <input type="text" class="form-control font-kecil" name="dept_id" value="<?= $mesinof['dept_id']; ?>" readonly>

            <!-- id data downtime -->
            <input type="hidden" name="id" value="<?= $mesinof['id']; ?>">
        </div>

    </div>


    <div class="col-12">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control font-kecil" value="<?= $mesinof['tanggal']; ?>">
    </div>


    <div class="col-12">
        <label>Shift</label>

        <select name="shift" class="form-control font-kecil">

            <option value="1" <?= $mesinof['shift'] == 1 ? 'selected' : '' ?>>PAGI</option>
            <option value="2" <?= $mesinof['shift'] == 2 ? 'selected' : '' ?>>SIANG</option>
            <option value="3" <?= $mesinof['shift'] == 3 ? 'selected' : '' ?>>MALAM</option>

        </select>
    </div>


    <div class="col-12">
        <label>No Mesin</label>

        <input type="text" class="form-control font-kecil" value="Mesin <?= $mesinof['mach_no']; ?> - <?= $mesinof['mach_name']; ?>" readonly>

        <input type="hidden" name="mach_id" value="<?= $mesinof['nomesin_id']; ?>">
    </div>


    <div class="row">

        <div class="col-6">
            <label>Ket Mesin Stop</label>

            <input type="text" name="ketof" id="ketof" class="form-control font-kecil" value="<?= $mesinof['code']; ?> - <?= $mesinof['reason']; ?>">

            <input type="hidden" name="ket_id" id="ket_id" value="<?= $mesinof['ket_id']; ?>">
        </div>


        <div class="col-6">
            <label>Spek Bahan</label>

            <input type="text" name="ket_tb" id="ket_tb" class="form-control font-kecil" value="<?= $mesinof['jenis']; ?>" readonly>

            <input type="hidden" name="id_benang" id="id_benang" value="<?= $mesinof['ket_tb']; ?>">
        </div>

    </div>


    <div class="col-12">
        <label>Keterangan</label>

        <textarea class="form-control" name="keterangan" rows="3"><?= $mesinof['keterangan']; ?></textarea>

    </div>

    <br>

    <button class="btn btn-primary" type="submit">
        Update
    </button>

</form>

<div class="modal modal-blur fade" id="modal-error" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-warning"></div>
            <div class="modal-body text-center py-4">
                <svg class="icon mb-2 text-warning icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 9v2m0 4v.01" />
                    <path d="M12 5a7 7 0 1 0 0 14a7 7 0 0 0 0 -14z" />
                </svg>
                <h3>Mohon Maaf,</h3>
                <div class="text-secondary" id="error-message">Pesan error akan muncul di sini</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <a href="#" class="btn btn-warning w-100" data-bs-dismiss="modal">Tutup</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        function cekSpekBahan() {

            let id = $("#ket_id").val();

            if (id == 24) {
                $("#ket_tb").prop("readonly", false);
            } else {
                $("#ket_tb").prop("readonly", true);
                $("#ket_tb").val("");
            }

        }


        setInterval(function() {
            cekSpekBahan();
        }, 300);

    });
</script>

<script>
    $(document).on("submit", "form", function(e) {

        const id = $("#id").val();
        const id_benang = $("#id_benang").val();

        function showError(message) {
            $("#error-message").text(message);

            const modal = new bootstrap.Modal(document.getElementById('modal-error'));
            modal.show();
        }

        if (id == 24 && !id_benang) {
            e.preventDefault();
            showError("Spek Benang wajib diisi jika memilih TB - Tunggu Bahan.");
            return false;
        }
    });
</script>