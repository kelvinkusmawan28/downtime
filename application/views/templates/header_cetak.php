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