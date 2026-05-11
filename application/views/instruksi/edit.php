<form action="<?php echo base_url('instruksi/update/' . $detail['id']); ?>" method="post" enctype="multipart/form-data">
    <div class="col-12">
        <label for="keterangan" class="form-label font-kecil">No Instruksi </label>
        <input type="text" name="keterangan" id="keterangan" class="form-control font-kecil" placeholder="No Instruksi" required>
    </div>
    <div class=" col-12">
        <label for="file_upload" class="form-label font-kecil">Upload( Foto, Gambar) Instruksi</label>
        <input type="file" name="file_upload[]" class="form-control font-kecil" id="file_upload" accept=".pdf,.xls,.xlsx,.jpg,.jpeg,.png,.mp4" multiple required>
    </div>

    <br>
    <div class="col-4">
        <button class="btn btn-primary w-100" type="submit">MULAI</button>
    </div>
</form>

<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ⚠️ Pastikan semua data sudah benar! <br>
                Data yang sudah disimpan <b>tidak dapat diubah</b>.
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="button" id="confirmSubmit" class="btn btn-info">
                    Ya, Simpan
                </button>
            </div>

        </div>
    </div>
</div>