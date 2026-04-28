<form action="<?php echo base_url('instruksi/simpan'); ?>" method="post" enctype="multipart/form-data">
    <div class="col-12">
        <label for="tgl" class="form-label font-kecil">Tanggal </label>
        <input type="date" name="tanggal" class="form-control font-kecil" id="tanggal" value="<?= $tgl_sekarang; ?>" required>
        <input type="hidden" name="dept_id" id="dept_id" class="form-control font-kecil" value="<?= $dept_id; ?>">
    </div>
    <div class="col-12">
        <label for="subjek" class="form-label font-kecil">Jenis Instruksi</label>
        <input type="text" name="kerusakan" id="kerusakan" class="form-control font-kecil" placeholder="Jenis Instruksi" required>
        <input type="hidden" name="rusak_id" id="rusak_id">
    </div>
    <div class="col-12">
        <label for="subjek" class="form-label font-kecil">Mesin</label>
        <input type="text" name="nomesin" id="nomesin" class="form-control font-kecil" placeholder="Ketik No Mesin" required>
        <input type="hidden" name="mach_id" id="mach_id">
    </div>
    <br>
    <div class="col-3">
        <button class="btn btn-primary w-100" type="submit">Simpan</button>
    </div>
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
    $("form").on("submit", function(e) {
        const nomesin = $("#nomesin").val();
        const mach_id = $("#mach_id").val();
        const kerusakan = $("#kerusakan").val();
        const rusak_id = $("#rusak_id").val();

        function showError(message) {
            $("#error-message").text(message);
            $("#modal-error").modal("show");
        }

        if (!nomesin || !mach_id) {
            e.preventDefault();
            showError("Silakan Pilih Nomor Mesin Dari Data Yang Telah Di Sediakan.");
            return false;
        }
    });
</script>