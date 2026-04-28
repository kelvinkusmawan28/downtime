<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <link rel="icon" href="images/favicon.ico" type="image/ico" /> -->
  <link rel="icon" href="<?= base_url('assets'); ?>/production/images/new.png " class="img-circle profile_img " type="image/x-icon">




  <title><?= $title; ?></title>

  <!-- Bootstrap -->
  <link href="<?= base_url('assets'); ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- modal -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="<?= base_url('assets'); ?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="<?= base_url('assets'); ?>/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="<?= base_url('assets'); ?>/vendors/iCheck/skins/flat/green.css" rel="stylesheet">


  <!-- bootstrap-progressbar -->
  <link href="<?= base_url('assets'); ?>/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="<?= base_url('assets'); ?>/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <!-- bootstrap-datetimepicker -->
  <link href="<?= base_url('assets'); ?>/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
  <!-- Bootstrap Colorpicker -->
  <script src="<?= base_url('assets'); ?>/vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>


  <!-- JQVMap -->
  <link href="<?= base_url('assets'); ?>/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
  <!-- bootstrap-daterangepicker -->
  <link href="<?= base_url('assets'); ?>/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

  <!-- Data Tables -->
  <link href="<?= base_url('assets'); ?>/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url('assets'); ?>/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url('assets'); ?>/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url('assets'); ?>/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url('assets'); ?>/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

  <!-- PNotify -->
  <link href="<?= base_url('assets'); ?>/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
  <link href="<?= base_url('assets'); ?>/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
  <link href="<?= base_url('assets'); ?>/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="<?= base_url('assets'); ?>/build/css/custom.min.css" rel="stylesheet">
  <link href="<?= base_url('assets'); ?>/build/css/own-style.css" rel="stylesheet">
  <style>
    .notification {
      display: inline-block;
      position: relative;
      margin-left: 5px;
      /* Jarak dari teks */
    }

    .badge {
      position: absolute;
      top: -25px;
      right: -15px;
      background: red;
      color: white;
      font-size: 10px;
      border-radius: 50%;
      padding: 5px 8px;
      display: none;

    }


    .notification.active .badge {
      display: inline-block;
    }

    @keyframes bounce {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-5px);
      }
    }


    .notification.active .badge {
      display: inline-block;
      animation: bounce 0.5s infinite;
    }
  </style>

</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <!-- <a href="index.html" class="site_title"><span style="color: aquamarine; font-style :italic ; font-size : 17px ; ">Environmental Management</span></a> -->
            <a href="index.html" class="site_title"><i class="fa fa-tag"></i> <span style="font-size: 14px; font-style:oblique ;">Environmental Management</span></a>
            <!-- <img src="<?= base_url('assets'); ?>/production/images/logodepan.png" alt="..." width="200px" style="margin-left: 10px;"> -->
          </div>

          <div class="clearfix"></div>

          <!-- menu profile quick info -->
          <div class="profile clearfix">
            <div class="profile_pic">
              <img src="<?= base_url('assets'); ?>/production/images/new.jpeg" alt="..." class="img-circle profile_img " style="width: 72px;">
            </div>
            <div class="profile_info">
              <span>Selamat Datang,</span>
              <h2><?= $this->session->userdata('name'); ?></h2>
            </div>
          </div>
          <!-- /menu profile quick info -->

          <!-- Spinner -->
          <div id="loading-spinner" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; text-align: center; align-items: center; justify-content: center;">
            <div class="spinner-border text-success" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>

            <!-- <img src="<?= base_url('assets'); ?>/production/images/login3.jpeg" alt="..." width="290px ; opacity: 0.5;"> -->

          </div>
          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
              <h3>General</h3>
              <ul class="nav side-menu">

                <?php if ($this->session->userdata('cekenv') == '1') : ?>
                  <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                  </li>
                  <li><a><i class="fa fa-edit"></i>Laporan Harian<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?= base_url('ph'); ?>" data-spinner>Data PH</a></li>
                      <li><a href="<?= base_url('datado'); ?>" data-spinner>Data DO</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-thumb-tack"></i> Data Debit <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?= base_url('debit_limbah'); ?>" data-spinner>Debit Limbah Outlet</a></li>
                      <li><a href="<?= base_url('debit_pengolahan'); ?>" data-spinner>Debit Inlet Pengolahan</a></li>
                      <li><a href="<?= base_url('debit_produksi'); ?>" data-spinner>Debit Inlet Produksi</a></li>
                      <li><a href="<?= base_url('debit_domestik'); ?>" data-spinner>Debit Outlet Domestik</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-bar-chart"></i> Data COD <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?= base_url('cod_form'); ?>" data-spinner>COD</a></li>
                      <li><a href="<?= base_url('cod_form/pengujian'); ?>" data-spinner>Report Pengujian COD</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-flask"></i> Data Chemical <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?= base_url('chemical'); ?>" data-spinner>Chemical</a></li>
                      <li><a href="<?= base_url('chemical/journal'); ?>" data-spinner>Report Journal</a></li>

                    </ul>
                  </li>
                  <li><a><i class="fa fa-recycle"></i>EI <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li>
                        <a href="<?= base_url('approve_ei'); ?>" data-spinner class="d-flex align-items-center">
                          Approve Data
                          <div class="notification <?= ($total_ok['total'] > 0) ? 'active' : '' ?>">
                            <?php if ($total_ok['total'] > 0) : ?>
                              <span class="badge"><?= $total_ok['total']; ?></span>
                            <?php endif; ?>
                          </div>
                        </a>
                      </li>
                      <!-- <li><a href="<?= base_url('approve_ei'); ?>" data-spinner>Approve Data</a></li> -->
                      <li><a href="<?= base_url('logbook'); ?>" data-spinner>Logbook Data</a></li>
                      <!-- <li><a href="<?= base_url('limbah_keluar'); ?>" data-spinner>Limbah Keluar</a></li> -->
                      <li>
                        <a href="<?= base_url('limbah_keluar'); ?>" data-spinner>
                          Limbah Keluar
                          <div class="notification <?= ($ok_tuju['total_ok'] > 0) ? 'active' : '' ?>">
                            <?php if ($ok_tuju['total_ok'] > 0) : ?>
                              <span class="badge"><?= $ok_tuju['total_ok']; ?></span>
                            <?php endif; ?>
                          </div>
                        </a>
                      </li>


                      <li><a href="<?= base_url('limbah_masuk'); ?>" data-spinner>Limbah Masuk</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-fire"></i> Limbah B3 <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?= base_url('limbah_form'); ?>" data-spinner>Limbah B3</a></li>
                      <li><a href="<?= base_url('pb'); ?>" data-spinner>Permintaan Barang EI</a></li>
                    </ul>
                  </li>
                <?php endif; ?>
                <?php if ($this->session->userdata('cekenv') == '0') : ?>
                  <li><a><i class="fa fa-fire"></i> Limbah B3 <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?= base_url('limbah_form'); ?>" data-spinner>Limbah B3</a></li>
                    </ul>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
          <!-- /sidebar menu -->
        </div>
      </div>

      <!-- top navigation -->
      <div class="top_nav">
        <div class="nav_menu">
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
          </div>
          <nav class="nav navbar-nav">
            <ul class=" navbar-right">
              <li class="nav-item dropdown open" style="padding-left: 15px;">
                <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                  <?= $this->session->userdata('name'); ?>
                </a>
                <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="javascript:;"> Profile</a>
                  <a class="dropdown-item" href="javascript:;">Help</a>
                  <a class="dropdown-item" href="<?= base_url('auth/logout'); ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                </div>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- /top navigation -->