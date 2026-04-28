<style>
    /* Menggunakan Font Modern */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

    .main-container {
        font-family: 'Inter', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
    }

    .glass-box {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        width: 100%;
        max-width: 550px;
        padding: 50px 40px;
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: transform 0.3s ease;
    }



    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-15px);
        }
    }

    /* Judul Gradient */
    .title-gradient {
        background: linear-gradient(45deg, #2563eb, #7c3aed);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 700;
        font-size: 26px;
        margin-top: 25px;
    }

    .subtitle {
        color: #6b7280;
        font-size: 15px;
        margin-bottom: 30px;
    }

    /* Modern Dots Loader */
    .modern-loader {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-bottom: 30px;
    }

    .modern-loader span {
        width: 12px;
        height: 12px;
        background: #2563eb;
        border-radius: 50%;
        opacity: 0.3;
        animation: dots 1.4s infinite ease-in-out both;
    }

    .modern-loader span:nth-child(1) {
        animation-delay: -0.32s;
    }

    .modern-loader span:nth-child(2) {
        animation-delay: -0.16s;
    }

    @keyframes dots {

        0%,
        80%,
        100% {
            transform: scale(0.8);
            opacity: 0.3;
        }

        40% {
            transform: scale(1.2);
            opacity: 1;
        }
    }


    .info-alert {
        background: #fef2f2;
        color: #dc2626;
        padding: 15px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 30px;
        border-left: 4px solid #dc2626;
    }


    .btn-custom {
        display: inline-block;
        padding: 12px 30px;
        background: #1f2937;
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-custom:hover {
        background: #000;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        color: #fff;
    }

    .footer-minimal {
        margin-top: 40px;
        border-top: 1px solid #f3f4f6;
        padding-top: 20px;
        font-size: 12px;
        color: #9ca3af;
    }

    .footer-minimal span {
        font-weight: 600;
        color: #4b5563;
    }
</style>

<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Order Statistics -->
        <div class="col-lg-12 ">
            <div class="card h-100">
                <div class="card-header ">
                    <div class="card-title mb-0">
                        <!-- <h5 class="mb-1 me-2"><?= $title; ?></h5> -->
                    </div>

                </div>
                <div class="card-body font-kecil">
                    <div class="main-container">
                        <div class="glass-box">
                            <div class="icon-wrapper">
                                <img src="<?= base_url('assets/img/avatars/user.jpg') ?>" class="floating-img" alt="Under Construction" style="width: 210px;">
                            </div>

                            <div class="content">
                                <p style="font-size: 21px; " class="text-primary">Sistem Sedang Dalam Pengembangan</p>
                                <p class="subtitle">Kami sedang membuat fitur baru. Mohon tunggu sejenak.</p>

                                <div class="modern-loader">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>

                                <div class="info-alert">
                                    <i class="fas fa-info-circle"></i>
                                    Silakan kembali beberapa saat lagi, Terima kasih..
                                </div>

                                <div class="action-area">
                                    <a href="<?= base_url('dashboard'); ?>" class="btn-custom">
                                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                                    </a>
                                </div>
                            </div>

                            <div class="footer-minimal">
                                <p>© <?= date('Y'); ?> • <span>IT Team Indoneptune</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- / Content -->