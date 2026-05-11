<style>
    .card-background-overlay {
        background-color: transparent;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .card-background-overlay::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;

        background-image: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
            url('<?= base_url('assets/img/elements/tes4.png') ?>');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        z-index: 0;

    }


    .card-background-overlay .card-body {
        position: relative;
        z-index: 1;
    }
</style>


<div class="container-xxl ">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Register -->
            <div class="card card-background-overlay px-sm-6 px-0 ">
                <div class="card-body ">
                    <p class="mb-5 lead fw-bold text-dark font-sans-serif text-center" style="font-size: 22px;">Selamat Datang Di Sistem Informasi Downtime Mesin! 👋</p>
                    <br>
                    <?= $this->session->flashdata('message'); ?>
                    <form action="<?= base_url('auth'); ?>" method="POST" class="mb-4">
                        <div class="mb-6">
                            <label for="username" class="form-label text-dark">Username</label>
                            <input type="text" class="form-control text-dark" id="username" name="username" placeholder="Enter  username" autofocus />
                        </div>
                        <div class="mb-6 form-password-toggle">
                            <label class="form-label text-dark" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" name="password" class="form-control text-dark" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer text-dark"><i class="icon-base bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="mb-8">
                            <div class="d-flex justify-content-between">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="remember-me" />
                                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                                </div>
                                <a href="#">
                                    <span>Forgot Password?</span>
                                </a>
                            </div>
                        </div>
                        <div class="mb-6">
                            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                        </div>
                    </form>

                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
</div>