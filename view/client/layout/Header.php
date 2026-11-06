<?php if (!isset($menus)) { load_client_data('layout/common'); } ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="csrf-token" content="<?=$csrf_token;?>">
        <meta http-equiv="content-language" content="vi">
        <meta name="robots" content="index, follow">

        <title><?= $title ?? ($site['title'] ?? '') ?></title>
        <meta name="description" content="<?= $description ?>">
        <meta name="keywords" content="<?= $keywords ?>">

        <!-- Open Graph data -->
        <meta property="og:title" content="<?= $title ?? ($site['title'] ?? '') ?>">
        <meta property="og:type" content="Website">
        <meta property="og:url" content="<?= BASE_URL($_SERVER['REQUEST_URI']) ?>"/>
        <meta property="og:image" content="<?= BASE_URL($image) ?>">
        <meta property="og:description" content="<?= $description ?>">
        <meta property="og:site_name" content="<?=  $title ?? ($site['title'] ?? '') ?>">
        <meta property="article:section" content="<?= $title ?? ($site['title'] ?? '') ?>">
        <meta property="article:tag" content="<?= $keywords ?>">

        <!-- Twitter Card data -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@nguyenanhvu208">
        <meta name="twitter:title" content="<?= $title ?? ($site['title'] ?? '') ?>">
        <meta name="twitter:description" content="<?= $description ?>">
        <meta name="twitter:creator" content="@nguyenanhvu208">
        <meta name="twitter:image:src" content="<?= BASE_URL($image) ?>">

        <link rel="alternate" hreflang="vi-vn" href="<?= BASE_URL($_SERVER['REQUEST_URI']) ?>">
        <link rel="canonical" href="<?= BASE_URL($_SERVER['REQUEST_URI']) ?>">
        <link rel="shortcut icon" href="<?= BASE_URL($site['favicon'] ?? '') ?>">

        <style>
            :root {
                --primary-color: <?=$site['theme_color_top'] ?? '';?>;
                --primary-hover: <?=$site['theme_color_footer'] ?? '';?>;
                --primary-rgb: 1, 36, 103;
            }
        </style>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/assets/client/plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="/assets/client/plugins/swiper/swiper-bundle.min.css">
        <link rel="stylesheet" href="/assets/client/fonts/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="/assets/client/plugins/fancybox/fancybox.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="/assets/client/css/style.css?v=1.1">
        <script type="text/javascript" src="/assets/client/plugins/jquery.min.js"></script> 
        
    </head>