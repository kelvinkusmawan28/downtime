<form action="<?= base_url('mesin_of/simpan'); ?>" method="post" enctype="multipart/form-data">

    <div class="row font-kecil ">


        <div class="col-md-6 ">
            <label>User</label>
            <input type="text" class="form-control font-kecil has-feedback-left" name="user" id="user" value="<?= $this->session->userdata('name'); ?>" readonly>
        </div>


        <div class="col-md-6">
            <label>Kode Departemen</label>
            <input type="text" class=" form-control font-kecil" id="dept_id" name="dept_id" value="<?= $dept_id; ?>" readonly>

        </div>
    </div>

    <div class="col-12">
        <label class="font-kecil">Tanggal</label><br>
        <label style="font-size: 10px; color :red ;"><?= format_tanggal_indonesia($tgl_sekarang); ?></label>
        <input type="date" name="tanggal" class="form-control font-kecil" id="tanggal" value="<?= $tgl_sekarang; ?>" required>

    </div>


    <div class="col-12">
        <label class="font-kecil">Shift</label>
        <select name="shift" id="shift" class="form-control font-kecil">
            <option value="1">PAGI</option>
            <option value="2">SIANG</option>
            <option value="3">MALAM</option>
        </select>
    </div>


    <div class="col-12">
        <label class="font-kecil">No Mesin</label>
        <input type="text" name="nomesin" id="nomesin" class="form-control font-kecil" placeholder="Ketik No Mesin .." required>
        <input type="hidden" name="mach_id" id="mach_id">
    </div>

    <div class="row">
        <div class="col-6">
            <label class="font-kecil">Ket Mesin Stop</label>
            <input type="text" name="ketof" id="ketof" class="form-control font-kecil" placeholder="Ketik Keterangan Mesin OF .." required>
            <input type="hidden" name="id" id="id">
        </div>
        <div class="col-6">
            <label class="font-kecil">Spek Bahan</label>
            <input type="text" name="ket_tb" id="ket_tb" class="form-control font-kecil" placeholder="Ketik Spek Benang .." readonly>
            <input type="hidden" name="id_benang" id="id_benang">
        </div>

    </div>

    <div class="col-12">
        <label for="ket_tb" class="form-label">Keterangan</label>
        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
    </div>





    <br>
    <div class="col-md-3">
        <button class="btn btn-primary btn-block" type="submit">
            Simpan
        </button>
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
    $(document).ready(function() {

        function cekSpekBahan() {

            let id = $("#id").val();

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
    $("form").on("submit", function(e) {
        const nomesin = $("#nomesin").val();
        const mach_id = $("#mach_id").val();
        const ketof = $("#ketof").val();
        const id = $("#id").val();
        const id_benang = $("#id_benang").val();

        function showError(message) {
            $("#error-message").text(message);
            $("#modal-error").modal("show");
        }

        if (!nomesin || !mach_id) {
            e.preventDefault();
            showError("Silakan Pilih Nomor Mesin Dari Data Yang Telah Di Sediakan.");
            return false;
        }
        if (!ketof || !id) {
            e.preventDefault();
            showError("Silakan Pilih Keterangan Mesin Stop Dari Data Yang Telah Di Sediakan.");
            return false;
        }

        if (id == 24 && !id_benang) {
            e.preventDefault();
            showError("Spek Benang wajib diisi jika memilih TB - Tunggu Bahan.");
            return false;
        }

    });
</script>