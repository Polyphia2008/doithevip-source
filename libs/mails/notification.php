<?php
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php');
?>
<!doctype html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <?=$title ?? 'Thông báo từ ' . $db->site('tenweb') . ''; ?>
    <style>
        img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
        }

        body {
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        table {
            border-collapse: separate;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            width: 100%;
        }

        table td {
            font-family: sans-serif;
            font-size: 14px;
            vertical-align: top;
        }

        .container {
            display: block;
            margin: 0 auto !important;
            /* makes it centered */
            max-width: 900px;
            padding: 10px;
            width: 900px;
        }

        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            max-width: 900px;
            padding: 10px;
        }

        .main {
            background: #ffffff;
            border-radius: 5px;
            width: 100%;
        }

        .wrapper {
            box-sizing: border-box;
            padding: 20px;
        }

        .content-block {
            padding-bottom: 10px;
            padding-top: 10px;
        }

        .footer {
            clear: both;
            margin-top: 10px;
            text-align: center;
            width: 100%;
        }

        .footer td,
        .footer p,
        .footer span,
        .footer a {
            color: #999999;
            font-size: 12px;
            text-align: center;
        }

        h1,
        h2,
        h3,
        h4 {
            color: #000000;
            font-family: sans-serif;
            font-weight: 400;
            line-height: 1.4;
            margin: 0;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 35px;
            font-weight: 300;
            text-align: center;
            text-transform: capitalize;
        }

        p,
        ul,
        ol {
            font-family: sans-serif;
            font-size: 14px;
            font-weight: normal;
            margin: 0;
            margin-bottom: 15px;
        }

        p li,
        ul li,
        ol li {
            list-style-position: inside;
            margin-left: 5px;
        }

        a {
            color: #3498db;
            text-decoration: underline;
        }

        .btn {
            box-sizing: border-box;
            width: 100%;
        }

        .btn>tbody>tr>td {
            padding-bottom: 15px;
        }

        .btn table {
            width: auto;
        }

        .btn table td {
            background-color: #ffffff;
            border-radius: 5px;
            text-align: center;
        }

        .btn a {
            background-color: #ffffff;
            border: solid 1px #3498db;
            border-radius: 5px;
            box-sizing: border-box;
            color: #3498db;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            padding: 12px 25px;
            text-decoration: none;
            text-transform: capitalize;
        }

        .btn-primary table td {
            background-color: #3498db;
        }

        .btn-primary a {
            background-color: #3498db;
            border-color: #3498db;
            color: #ffffff;
        }

        .powered-by a {
            text-decoration: none;
        }

        hr {
            border: 0;
            border-bottom: 1px solid #f6f6f6;
            margin: 20px 0;
        }

        @media only screen and (max-width: 620px) {
            table[class=body] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }

            table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
                font-size: 16px !important;
            }

            table[class=body] .wrapper,
            table[class=body] .article {
                padding: 10px !important;
            }

            table[class=body] .content {
                padding: 0 !important;
            }

            table[class=body] .container {
                padding: 0 !important;
                width: 100% !important;
            }

            table[class=body] .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }

            table[class=body] .btn table {
                width: 100% !important;
            }

            table[class=body] .btn a {
                width: 100% !important;
            }

            table[class=body] .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }
        }

        @media all {
            .ExternalClass {
                width: 100%;
            }

            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }

            .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }

            #MessageViewBody a {
                color: inherit;
                text-decoration: none;
                font-size: inherit;
                font-family: inherit;
                font-weight: inherit;
                line-height: inherit;
            }

            .btn-primary table td:hover {
                background-color: #34495e !important;
            }

            .btn-primary a:hover {
                background-color: #34495e !important;
                border-color: #34495e !important;
            }
        }
    </style>
</head>

<body style="background: #d0d0d0">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
        <tr>
            <td class="container">
                <div class="content">
                    <table role="presentation" class="main">
                        <tr>
                            <td class="wrapper">
                                <table role="presentation">
                                    <tr>
                                        <td align="left">
                                            <img src="<?= BASE_URL($db->site('logo_light')); ?>" alt="img" width="135" border="0" style="display: block; width: 135px; margin-bottom: 30px" />
                                        </td>
                                        <td align="right" style="font-size: 18px; font-weight: bold">
                                            <?= ($db->site('phone') ? 'Hotline hỗ trợ: ' . $db->site('phone') : '') ?>
                                        </td>
                                    </tr>
                                </table>

                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            <table role="presentation" style="font-weight: bold; font-size: 16px; color: #0a3461">
                                                <tr>
                                                    <td align="left">
                                                        Xin chào <?= $name ?? ''; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>

                                            <?php if (!empty($content['description'])): ?>
                                            <table role="presentation">
                                                <tr>
                                                    <td align="center">
                                                        <?= $content['description']; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                            <?php endif; ?>
                                            <?php if (!empty($content['details'])): ?>
                                                <?php if (is_array($content['details'])): ?>
                                                    <table role="presentation" style="margin:15px 0; border-collapse: collapse; width:100%; font-size:14px;">
                                                        <?php foreach ($content['details'] as $label => $value): ?>
                                                            <tr>
                                                                <td style="padding:8px 10px; border:1px solid #eee; font-weight:bold; color:#333; width:40%;">
                                                                    <?= $label; ?>
                                                                </td>
                                                                <td style="padding:8px 10px; border:1px solid #eee; color:#555;">
                                                                    <?= $value; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </table>
                                                <?php else: ?>
                                                    <table role="presentation" style="margin:15px 0; border-collapse: collapse; width:100%; font-size:14px;">
                                                        <?php foreach (explode("\n", trim(strip_tags($content['details']))) as $line): 
                                                            if (empty(trim($line))) continue;
                                                            $parts = explode(":", $line, 2);
                                                        ?>
                                                        <tr>
                                                            <td style="padding:8px 10px; border:1px solid #eee; font-weight:bold; color:#333; width:40%;">
                                                                <?= trim($parts[0]); ?>
                                                            </td>
                                                            <td style="padding:8px 10px; border:1px solid #eee; color:#555;">
                                                                <?= trim($parts[1] ?? ''); ?>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </table>
                                                <?php endif; ?>
                                            <?php endif; ?>


                                            <?php if (!empty($content['secret'])): ?>
                                            <table role="presentation" style="background: #f5f5f5; border-radius: 5px; padding: 10px; font-size: 18px; font-weight: bold">
                                                <tr>
                                                    <td align="center">
                                                        <?= $content['secret']; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                            <?php endif; ?>

                                            <?php if (!empty($content['url'])): ?>
                                            <table role="presentation" class="btn btn-primary">
                                                <tr>
                                                    <td align="center">
                                                        <table role="presentation">
                                                            <tr>
                                                                <td>
                                                                    <a href="<?= $content['url']; ?>" target="_blank">
                                                                        <?= $content['button_name'] ?? 'Xác nhận ngay'; ?>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>

                                            <table role="presentation">
                                                <tr>
                                                    <td align="center">Nếu nút không hoạt động, bạn có thể sao chép liên kết sau:</td>
                                                </tr>
                                                <tr>
                                                    <td align="center" style="font-weight: bold">
                                                        <?= $content['url']; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <?php endif; ?>

                                            <br>

                                            <table role="presentation">
                                                <tr>
                                                    <td align="left" style="font-size: 11px">
                                                        IP: <?= myip(); ?><br>
                                                        Trình duyệt: <?= $_SERVER['HTTP_USER_AGENT']; ?><br>
                                                        Thời gian: <?= gettime(); ?><br>
                                                        Website: <?= $_SERVER['HTTP_HOST']; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <div class="footer">
                        <table role="presentation">
                            <tr>
                                <td class="content-block">
                                    Tel.: <?= $db->site('phone'); ?>,
                                    Email: <?= $db->site('email'); ?><br>
                                    <?= $db->site('copyright'); ?><br>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </td>
        </tr>
    </table>
</body>
</html>
