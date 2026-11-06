<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
load_client_data('account/auth/password/verify');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Xác thực liên kết</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Font + CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/client/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/client/fonts/fontawesome/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.05);
        }

        .progress {
            height: 20px;
            border-radius: 10px;
        }

        .progress-bar {
            transition: width 0.3s ease-in-out;
        }
    </style>
</head>

<body>

    <div class="card p-4 text-center" style="max-width: 420px; width: 100%;">
        <div class="card-body">
            <div class="mb-3">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            </div>
            <h5 class="card-title mb-2">Đang xác thực liên kết...</h5>
            <p class="text-muted">Hệ thống đang xử lý yêu cầu đặt lại mật khẩu của bạn.</p>
            <div class="progress mt-4">
                <div id="progressBar" class="progress-bar bg-primary" role="progressbar" style="width: 0%">0%</div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/assets/client/plugins/jquery.min.js"></script>
    <script src="/assets/client/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        const progressBar = document.getElementById('progressBar');
        let progress = 0;
        const simulateProgress = setInterval(() => {
            if (progress < 95) {
                progress++;
                progressBar.style.width = progress + '%';
                progressBar.innerText = progress + '%';
            }
        }, 15);

        fetch("<?= BASE_URL('ajax/password/verify') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "user=<?= urlencode($username) ?>&token=<?= urlencode($token) ?>"
        })
            .then(response => response.text())
            .then(() => {
                clearInterval(simulateProgress);
                progressBar.style.width = "100%";
                progressBar.innerText = "100%";
                setTimeout(() => {
                    window.location.href = "<?= BASE_URL('') ?>";
                }, 1000);
            })
            .catch(() => {
                alert("Xác thực thất bại. Vui lòng thử lại.");
                window.location.href = "<?= BASE_URL('') ?>";
            });
    </script>
</body>

</html>