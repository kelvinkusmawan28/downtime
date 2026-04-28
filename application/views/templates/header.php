<!doctype html>

<html lang="en" translate="no" class="layout-menu-fixed layout-compact" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title><?= $title; ?></title>

  <meta name="description" content="" />

  <!-- Favicon -->

  <link rel="icon" type="image/x-icon" href="<?= base_url(); ?>/assets/img/favicon/favicon.ico" />



  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendor/fonts/iconify-icons.css" />

  <!-- Core CSS -->
  <!-- build:css assets/vendor/css/theme.css  -->

  <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendor/css/core.css" />
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/demo.css" />
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/my.css" />
  <!-- to filter form -->
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/jquery-ui.css" />
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/fontawesome/css/all.css" />


  <link rel="stylesheet" href="<?= base_url(); ?>/assets/datatabel/datatables.min.css" />
  <!-- filter select -->
  <!-- <link rel="stylesheet" href="<?= base_url(); ?>/assets/tagify/tagify.css" /> -->
  <link rel="stylesheet" href="<?= base_url('assets/tagify/tagify.css'); ?>" />








  <!-- Vendors CSS -->

  <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- endbuild -->

  <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendor/libs/apex-charts/apex-charts.css" />

  <!-- Page CSS -->

  <!-- Helpers -->
  <script src="<?= base_url(); ?>/assets/vendor/js/helpers.js"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

  <script src="<?= base_url(); ?>/assets/js/config.js"></script>

  <!-- untuk filter form di add nama mesin dan jenis kerusakan -->
  <style>
    .ui-autocomplete {
      z-index: 99999 !important;
      max-height: 200px;
      overflow-y: auto;
      overflow-x: hidden;
      font-size: 12px;
      background-color: white;
    }
  </style>

</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->

      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <!-- <div class="app-brand demo">
          <a href="index.html" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-2">Downtime</span>
            <img src="<?= base_url(); ?>/assets/img/elements/user3.jpeg" style="width: 105px; margin-left: -20px;">
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
          </a>
        </div> -->

        <div class="app-brand demo">
          <a href="index.html" class="app-brand-link">
            <div style="display: flex; align-items: center;">

              <!-- <span class="app-brand-text demo menu-text fw-bold">Downtime</span> -->
              <img src="<?= base_url(); ?>/assets/img/elements/header.jpeg" style="width: 140px; ">
            </div>
          </a>
        </div>



        <div class="menu-divider mt-0"></div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboards -->
          <li class="menu-item">
            <a href="<?= base_url('dashboard'); ?>" class="menu-link">
              <i class="fa-solid fa-house me-2"></i>
              <div class="text-truncate" data-i18n="Email">Dashboard</div>
            </a>
          </li>

          <!-- Layouts -->
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="fa fa-file me-2"></i>
              <div class="text-truncate" data-i18n="Layouts">Document</div>
            </a>

            <ul class="menu-sub">
              <li class="menu-item">
                <a href="<?= base_url('mesin'); ?>" class="menu-link">
                  <div class="text-truncate" data-i18n="Without menu">Perbaikan Mesin</div>
                </a>
              </li>
            </ul>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="<?= base_url('cheklist'); ?>" class="menu-link">
                  <div class="text-truncate" data-i18n="Without menu">Preventif Maintenance</div>
                </a>
              </li>
            </ul>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="<?= base_url('mesin_of'); ?>" class="menu-link">
                  <div class="text-truncate" data-i18n="Without menu">Mesin OF</div>
                </a>
              </li>
            </ul>
          </li>
          <?php
          if ($this->session->userdata('cekdowntime') == '1') :
          ?>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="fa-solid fa-table me-2"></i>
                <div class="text-truncate" data-i18n="Layouts">Master Data</div>
              </a>

              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="<?= base_url('kerusakan'); ?>" class="menu-link">
                    <div class="text-truncate" data-i18n="Without menu">Kerusakan</div>
                  </a>
                </li>
              </ul>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="<?= base_url('spekmesin'); ?>" class="menu-link">
                    <div class="text-truncate" data-i18n="Without menu">Data Mesin</div>
                  </a>
                </li>
              </ul>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="<?= base_url('preventif'); ?>" class="menu-link">
                    <div class="text-truncate" data-i18n="Without menu">Master Preventif</div>
                  </a>
                </li>
              </ul>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="<?= base_url('ketof'); ?>" class="menu-link">
                    <div class="text-truncate" data-i18n="Without menu">Mesin Stop</div>
                  </a>
                </li>
              </ul>
            </li>

            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="fa-solid fa-list me-2"></i>
                <div class="text-truncate" data-i18n="Layouts">Report </div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="<?= base_url('report'); ?>" class="menu-link">
                    <div class="text-truncate" data-i18n="Without menu"> Perbaikan Teknisi</div>
                  </a>
                </li>
              </ul>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="<?= base_url('downtime_mesin'); ?>" class="menu-link">
                    <div class="text-truncate" data-i18n="Without menu">Downtime</div>
                  </a>
                </li>
              </ul>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="<?= base_url('downtime_mesin/pengerjaan'); ?>" class="menu-link">
                    <div class="text-truncate" data-i18n="Without menu">Rekap Pengerjaan</div>
                  </a>
                </li>
              </ul>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="<?= base_url('instruksi/report'); ?>" class="menu-link">
                    <div class="text-truncate" data-i18n="Without menu">Laporan Ganti Instruksi</div>
                  </a>
                </li>
              </ul>
            </li>

          <?php endif; ?>

          <li class="menu-item">
            <a href="<?= base_url('monitoring'); ?>" class="menu-link">
              <i class="fa-solid fa-gear me-2"></i>
              <div class="text-truncate" data-i18n="Email">Monitoring </div>
            </a>
          </li>

          <!-- Forms & Tables -->
          <!-- <li class="menu-header small text-uppercase"><span class="menu-header-text">Forms &amp; Tables</span></li> -->
          <!-- Forms -->

        </ul>
      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
              <i class="icon-base bx bx-menu icon-md"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
            <!-- Search -->
            <div class="navbar-nav align-items-center me-auto">
              <div class="nav-item d-flex align-items-center">
                <span class="w-px-22 h-px-22"><i class="icon-base bx bx-search icon-md"></i></span>
                <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2 d-md-block d-none" placeholder="Search..." aria-label="Search..." />
              </div>
            </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-md-auto">
              <!-- Place this tag where you want the button to render. -->
              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="<?= base_url(); ?>/assets/img/avatars/user.png" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img src="<?= base_url(); ?>/assets/img/avatars/user.png" alt class="w-px-40 h-auto rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-0"><?= $this->session->userdata('name'); ?></h6>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider my-1"></div>
                  </li>
                  <li>
                    <div class="dropdown-divider my-1"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="<?= base_url('auth/logout'); ?>">
                      <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Log Out</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">