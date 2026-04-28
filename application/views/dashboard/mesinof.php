<!-- <style>
    .timeline {
        position: relative;
        padding-left: 20px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        height: 100%;
        width: 2px;
        background: lavender;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
        background-color: ghostwhite;
        border-radius: 10px;
    }

    .font-kecil {
        font-size: 12px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -16px;
        top: 5px;
        width: 10px;
        height: 10px;
        background: mediumpurple;
        border-radius: 50%;
    }
</style>
<ul class="timeline mb-0 ">
    <h6>
        Periode: <?= date('d M Y', strtotime($startDate)); ?>
        s/d
        <?= date('d M Y', strtotime($endDate)); ?>
    </h6>
    <?php foreach ($detail as $row) : ?>

        <li class="timeline-item  ps-6 border-left-dashed">
            <div class="timeline-event ps-1">

                <div class="timeline-header">
                    <span style="border-left: 15px solid rgb(<?= $row['clr'] ?>); padding-left:10px;"> <?= $row['reason'] ?? '' ?></span>
                </div>
                <small class=" text-danger" style="font-size: 12px;">
                    <span>
                        Mesin : <?= $row['mach_no'] ?> <br>
                        <span><?= $row['mach_name'] ?></span>
                    </span>
                </small>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-primary" style="font-size: 12px;">Total /Jumlah:</span>
                    <span class="text-primary">
                        <?= $row['total'] ?> x
                    </span>
                </div>

            </div>
        </li>

    <?php endforeach; ?>

</ul> -->
<div class="mb-5" style="border-bottom: 1px solid darkslateblue; padding :5px ;">
    <span style="color: black; font-size : 16px; border-left: 17px solid rgb(<?= $header['clr'] ?>); padding-left:10px;"> <?= $header['reason'] ?? '' ?></span>
    <br>
    <span style="color: black; font-size : 14px ;">Departemen : <?= $header['departemen']; ?></span> <br>
    <span style="color: black; font-size : 14px ;">Periode : Periode: <?= date('d M Y', strtotime($startDate)); ?> s/d <?= date('d M Y', strtotime($endDate)); ?></span> <br>

</div>
<div class="table-responsive">

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th style="color: black;">No</th>
                <th style="color: black;">Mesin</th>
                <th style="color: black;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 0;
            foreach ($detail as $data) : $no++; ?>
                <tr>
                    <td style="color: black;"><?= $no; ?></td>
                    <td><span style="font-size: 12px; color:red ;">Mesin <?= $data['mach_no']; ?> <br> <?= $data['mach_name']; ?> </span></td>
                    <td style="color: red;"> <?= $data['total'] ?> x</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>