<form action="<?php echo base_url('mesin/simpan_tindakan/' . $id); ?>" method="post" enctype="multipart/form-data">
    <div class="col-12">
        <label for="tgl" class="form-label font-kecil">Tanggal </label>
        <input type="hidden" name="id_downtime" class="form-control font-kecil" id="id_downtime" value="<?= $id; ?>" required>
        <input type="date" name="tanggal" class="form-control font-kecil" id="tanggal" value="<?= $tgl_sekarang; ?>" required>
    </div>
    <div class="col-12">
        <label for="tindakan" class="form-label font-kecil">Tindakan</label>
        <textarea class="form-control font-kecil" name="tindakan" id="tindakan" style="height: 100px" required></textarea>
    </div>
    <div class="col-12">
        <label for="subjek" class="form-label font-kecil">Jam Mulai</label>
        <input type="time" name="jam_start" id="jam_start" class="form-control font-kecil" placeholder="Jam Mulai" required>
    </div>
    <div class="col-12">
        <label for="subjek" class="form-label font-kecil">Jam Selesai</label>
        <input type="time" name="jam_end" id="jam_end" class="form-control font-kecil" placeholder="Jam Selesai" required>
    </div>
    <div class="col-12">
        <label for="file_upload" class="form-label font-kecil">Upload Hasil Perbaikan (Foto, Video)</label>
        <input type="file" name="file_upload[]" class="form-control font-kecil" id="file_upload" accept=".pdf,.xls,.xlsx,.jpg,.jpeg,.png,.mp4" multiple>
    </div>

    <div class="col-12">
        <label for="teknisi" class="form-label font-kecil">Teknisi </label>
        <input name="teknisi" id="teknisi" class="form-control font-kecil" placeholder="Ketik Nama Teknisi">
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
        const teknisi = $("#teknisi").val();

        function showError(message) {
            $("#error-message").text(message);
            $("#modal-error").modal("show");
        }

        if (!teknisi) {
            e.preventDefault();
            showError("Pilih Departemen Anda.");
            return false;
        }


    });
</script>