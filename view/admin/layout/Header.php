<?php
    if (!isset($data_user) || !isset($capbac['status']) || $capbac['status'] !== true) {
        http_response_code(404);
        readfile($_SERVER['DOCUMENT_ROOT'] . '/view/error_pages/404.html');
        exit;
    }
    if (!isset($site)) {
        load_admin_data('layout/common');
    }
?>
<!DOCTYPE html>
<html lang="vi" data-layout-mode="fluid" data-sidenav-size="default" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title><?=$title;?></title>
    <meta name="description" content="Quản trị hệ thống">
    <link rel="icon" href="<?= BASE_URL($site['favicon'] ?? '') ?>">
    <link rel="apple-touch-icon" href="<?= BASE_URL($site['favicon'] ?? '') ?>">
    <meta name="csrf-token" content="<?=$csrf_token;?>">
    <meta name="robots" content="noindex,nofollow">
    <!-- App -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico">

    <!-- ================== CSS ================== -->
    <link href="/assets/admin/css/choices.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="/assets/admin/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/fonts/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/vendor/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
    <link href="/assets/admin/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/vendor/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/vendor/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/vendor/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <!-- ================== JS ================== -->
    <script src="/assets/admin/js/config.js"></script>
    <script src="/assets/admin/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/admin/js/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <style>
        .table tr.selected {
            background-color: rgba(0, 94, 234, 0.08) !important;
            position: relative;
            outline: 1px solid #0d6efd !important;
            outline-offset: -1px;
        }

        .table tr.selected td {
            border-color: rgba(13, 110, 253, 0.2) !important;
            color: rgba(0, 0, 0, 0.7);
        }

        .table tr.selected:hover {
            background-color: rgba(13, 110, 253, 0.12) !important;
        }

        .table tr.selected td:first-child {
            border-left: 2px solid #0d6efd !important;
        }

        [data-bs-theme="dark"] .table tr.selected {
            background-color: rgba(13, 110, 253, 0.15) !important;
            outline: 1px solid #3384ff !important;
            color: rgba(255, 255, 255, 0.7);
        }

        [data-bs-theme="dark"] .table tr.selected td {
            border-color: rgba(13, 110, 253, 0.3) !important;
            color: rgba(255, 255, 255, 0.7);
        }
        .btn.btn-sm_table {
            font-size: 11px;
            height: 21px;
            line-height: 20px;
            padding: 0 0.375rem;
        }
    </style>
</head>