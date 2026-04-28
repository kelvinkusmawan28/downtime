<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr class="bg-info text-dark">
                <th>No</th>
                <th>Tanggal</th>
                <th>Mesin</th>
                <th>Downtime</th>
                <th>Peetugas</th>
            </tr>
        </thead>
        <tbody style="font-size: 12px;">
            <?php $no = 0;
            foreach ($detail as $data) : $no++; ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $data['tanggal']; ?></td>
                    <td><span style="font-size: 10px;">Mesin <?= $data['mach_no']; ?> <br> <?= $data['mach_name']; ?> </span></td>
                    <td><?= format_downtime($data['downtime_kerusakan']);  ?></td>
                    <td><?= $data['user']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>