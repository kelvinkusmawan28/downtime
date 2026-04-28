<span style="font-size: 18px; color : red ;">Instruksi Progres</span> <br>
<span style="font-size: 12px; color : black ;">Departemen : <?= $header['departemen']; ?></span>
<hr>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr style=" background-color: #e3f2fd; font-size :12px ;">
                <th style="font-size: 12px;">No</th>
                <th style="font-size: 12px;">Mesin</th>
                <th style="font-size: 12px;">Tanggal</th>
                <th style="font-size: 12px;">Jenis Instruksi</th>
                <th style="font-size: 12px;">Status</th>
            </tr>
        </thead>
        <tbody style="font-size: 12px;">
            <?php $no = 0;
            foreach ($detail as $data) : $no++; ?>
                <?php $start_time = strtotime($data['kerusakan_mulai']) * 1000; ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><span style="font-size: 10px;">Mesin <?= $data['mach_no']; ?> <br> <?= $data['mach_name']; ?> </span></td>
                    <td><?= format_tanggal_indonesia($data['tanggal']); ?></td>
                    <td><?= $data['ins_kode']; ?>-<?= $data['instruksi']; ?></td>
                    <td> <span class="text-danger"><?= $data['status'] == 0 ? 'Progres..' : '' ?></span><br>
                        <small class="font-bold text-dark" style="font-size: 9px;"><?= format_tanggal_indonesia_waktu($data['kerusakan_mulai'])  ?></small><br>

                        <span class="font-bold text-success updateon" style="font-size:10px;" data-start="<?= $start_time ?>">
                            Loading...
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {

        let timerData = [];

        function simpanTimestamp() {
            timerData = [];
            $(".updateon").each(function() {
                const el = $(this);
                const startTimestamp = parseInt(el.data("start"));
                if (!isNaN(startTimestamp)) {
                    timerData.push({
                        el: el,
                        start: startTimestamp
                    });
                } else {
                    el.text("Waktu tidak valid");
                }
            });
        }

        simpanTimestamp();

        setInterval(function() {
            const now = new Date().getTime();

            timerData.forEach(function(item) {
                const distance = now - item.start;

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                item.el.text(
                    days + " Hari, " +
                    hours + " Jam, " +
                    minutes + " Menit, " +
                    seconds + " Detik"
                );
            });
        }, 1000);

    });
</script>