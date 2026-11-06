<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
load_client_data('invoice');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán #<?= $order_code; ?></title>
    <link rel="stylesheet" href="/assets/client/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2563eb;
            --bg-body: #f1f5f9;
            --surface: #ffffff;
            --text-main: #1e293b;
            --text-sub: #64748b;
            --border: #e2e8f0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }
        .pay-card {
            background: var(--surface);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 850px; /* Độ rộng tối ưu, gọn hơn */
            overflow: hidden;
            border: 1px solid var(--border);
        }
        /* Cột trái: Thông tin */
        .info-side {
            padding: 30px;
        }
        .header-compact {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px dashed var(--border);
        }
        .bank-logo-box {
            width: 45px;
            height: 45px;
            background: #eff6ff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.2rem;
        }
        .label-xs {
            font-size: 0.75rem;
            color: var(--text-sub);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 4px;
            display: block;
        }
        
        /* Input giả lập cho Copy */
        .copy-input {
            position: relative;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 15px;
            transition: all 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .copy-input:hover {
            border-color: var(--primary);
            background: #fff;
        }
        .copy-text {
            font-family: 'Consolas', monospace; /* Font monospace cho số dễ đọc */
            font-weight: 700;
            font-size: 1.1rem;
            color: #0f172a;
        }
        .copy-btn {
            background: transparent;
            border: none;
            color: var(--primary);
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
        }
        .copy-btn:hover {
            background: #eff6ff;
        }
        
        /* Highlight số tiền */
        .amount-box {
            text-align: center;
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        .amount-val {
            font-size: 1.5rem;
            font-weight: 800;
        }
        
        /* Cột phải: QR */
        .qr-side {
            background: #f8fafc;
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }
        .qr-img {
            max-width: 300px; /* Thu nhỏ QR lại một chút cho gọn */
            border-radius: 12px;
            border: 1px solid var(--border);
            background: white;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .loading-text {
            font-size: 0.85rem;
            color: var(--primary);
            font-weight: 600;
            margin-top: 15px;
            align-items: center;
            gap: 8px;
        }

        /* Mobile */
        @media (max-width: 991px) {
            .qr-side {
                border-left: none;
                border-top: 1px solid var(--border);
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="pay-card">
        <div class="row g-0">
            <div class="col-lg-7 info-side">
                <div class="header-compact d-flex align-items-center">
                    <div class="bank-logo-box me-3">
                        <i class="fa-solid fa-building-columns fa-lg"></i>
                    </div>
                    <div class="ms-auto text-end lh-sm">
                        <div class="label-xs text-muted">Đơn hàng</div>
                        <div class="fw-bold text-primary">#<?= $order_code; ?></div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="label-xs">Ngân hàng</label>
                    <div class="copy-input">
                        <span class="copy-text" id="textNameBank"><?= $bank_info['textNameBank'] ?? 'Unknown'; ?></span>
                        <button class="copy-btn" onclick="copyToClipboard('textNameBank')">
                            <i class="fa-regular fa-copy fa-lg"></i>
                        </button>
                    </div>
                    <label class="label-xs">Chủ tài khoản</label>
                    <div class="copy-input">
                        <span class="copy-text" id="bankOwner"><?= $bank_info['bankOwner'] ?? '---'; ?></span>
                        <button class="copy-btn" onclick="copyToClipboard('bankOwner')">
                            <i class="fa-regular fa-copy fa-lg"></i>
                        </button>
                    </div>
                    <label class="label-xs">Số tài khoản</label>
                    <div class="copy-input">
                        <span class="copy-text" id="bankAccount"><?= $bank_info['bankAccount'] ?? '---'; ?></span>
                        <button class="copy-btn" onclick="copyToClipboard('bankAccount')">
                            <i class="fa-regular fa-copy fa-lg"></i>
                        </button>
                    </div>

                    <label class="label-xs mt-3">Nội dung (Bắt buộc)</label>
                    <div class="copy-input" style="border-color: #fbbf24; background: #fffbeb;">
                        <span class="copy-text text-warning" style="color: #b45309;" id="noidung"><?= anti_xss($invoice['order_code']); ?></span>
                        <button class="copy-btn" style="color: #b45309;" onclick="copyToClipboard('noidung')">
                            <i class="fa-regular fa-copy fa-lg"></i>
                        </button>
                    </div>
                    <div class="small text-danger fst-italic mt-1" style="font-size: 11px;">
                        * Nhập đúng nội dung để hệ thống duyệt tự động
                    </div>

                    <div class="amount-box">
                        <div style="font-size: 0.8rem; opacity: 0.9;">Số tiền cần thanh toán</div>
                        <div class="amount-val"><?= format_number($invoice['pay_amount']); ?> đ</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 qr-side">
                <?php if ($showQR && $imgSrc): ?>
                    <div class="text-center">
                        <img src="<?= $imgSrc; ?>" class="qr-img img-fluid" alt="QR Code">
                        <div class="loading-text">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
                            <span>Đang kiểm tra giao dịch...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0" style="font-size: 11px;">Mở App ngân hàng - ví điện tử để quét mã thanh toán</p>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <i class="fa-brands fa-paypal fa-3x text-primary mb-3"></i>
                        <h6 class="fw-bold">Thanh toán qua <?= $bank_info['textNameBank'] ?? '#'; ?></h6>
                        <a href="https://<?= $bank_info['bankName'] ?? '#'; ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill mt-2">
                            Mở trang thanh toán <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="mt-4 text-center w-100 border-top pt-3">
                     <a href="/" class="text-decoration-none text-secondary small fw-bold">
                        <i class="fa-solid fa-angle-left"></i> Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/assets/client/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function copyToClipboard(id) {
            var text = document.getElementById(id).innerText;
            navigator.clipboard.writeText(text).then(function() {
                // Có thể thêm thông báo copy thành công ở đây nếu muốn
            });
        }

        // Khai báo biến interval để có thể dừng nó lại khi cần
        var checkInterval;

        function checkInvoiceStatus() {
            $.ajax({
                url: "/ajax/invoice",
                type: "POST",
                dataType: "JSON",
                data: {
                    type: "check_invoice",
                    order_code: "<?= $order_code; ?>"
                },
                success: function(res) {
                    if (res.status === 'success' || res.return == 1) {
                        clearInterval(checkInterval);
                        $('.loading-text').html('<span class="text-success fw-bold"><i class="fa-solid fa-check"></i> Thành công!</span>');
                        setTimeout(() => window.location.href = res.url || "/wallet/history", 1500);
                    } 
                    else if (res.status === 'error') {
                        clearInterval(checkInterval);
                        $('.loading-text').html('<span class="text-danger fw-bold"><i class="fa-solid fa-circle-xmark"></i> ' + res.msg + '</span>');
                    }
                    else if (res.status === 'pending') {
                        // Hiện vòng quay loading + tin nhắn từ server
                        $('.loading-text').html(`
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            <span class="ms-2 fw-bold text-primary">${res.msg}</span>
                        `);
                    }
                }
            });
        }

        // Gán vào biến để quản lý
        checkInterval = setInterval(checkInvoiceStatus, 3000);
    </script>
</body>
</html>