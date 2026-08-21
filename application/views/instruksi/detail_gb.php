<span style="font-size: 18px; color : red ;">Ganti Bahan</span> <br>
<span style="font-size: 18px; color : black ;">Departemen : <?= $header['departemen']; ?></span>
<hr>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr style=" background-color:aliceblue; ">
                <th style="font-size: 12px;">No</th>
                <th style="font-size: 12px;">Mesin</th>
                <th style="font-size: 12px;">Tanggal</th>
                <th style="font-size: 12px;">Jenis Instruksi</th>
            </tr>
        </thead>
        <tbody style="font-size: 12px;">
            <?php $no = 0;
            foreach ($detail as $data) : $no++; ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><span style="font-size: 10px;">Mesin <?= $data['mach_no']; ?> <br> <?= $data['mach_name']; ?> </span></td>
                    <td><?= format_tanggal_indonesia($data['tanggal']); ?></td>
                    <td><?= $data['ins_kode']; ?>-<?= $data['instruksi']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>