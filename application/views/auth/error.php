<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistem Sedang Dalam Perbaikan</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .box {
            background: #ffffff;
            width: 90%;
            max-width: 520px;
            padding: 35px 30px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            text-align: center;
        }

        .icon {
            font-size: 64px;
            margin-bottom: 10px;
        }

        h1 {
            margin: 0;
            font-size: 22px;
            color: #111827;
        }

        p {
            margin-top: 12px;
            font-size: 15px;
            color: #4b5563;
            line-height: 1.6;
        }

        .loader {
            margin: 22px auto 0;
            width: 55px;
            height: 55px;
            border: 6px solid #e5e7eb;
            border-top: 6px solid #2563eb;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .info {
            margin-top: 22px;
            font-size: 13px;
            background: #f9fafb;
            padding: 12px;
            border-radius: 10px;
        }

        .footer {
            margin-top: 18px;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>

<body>

    <div class="box ">
        <div class="icon">
            <img src="<?= base_url('assets\img\avatars\user.jpg') ?>" style="width: 210px;">
        </div>
        <h1 class="text-primary">Sistem Sedang Dalam Perbaikan</h1>

        <p>
            Mohon maaf, sistem sedang dilakukan maintenance untuk peningkatan layanan.
            Silakan coba kembali beberapa saat lagi.
        </p>

        <div class="loader"></div>

        <div class="info text-danger">
            Jika ada kebutuhan mendesak, silakan hubungi IT Programmer (Kelvin).
        </div>

        <div class="footer text-primary">
            © <?= date('Y'); ?> - IT Team Indoneptune
        </div>
    </div>

</body>

</html>