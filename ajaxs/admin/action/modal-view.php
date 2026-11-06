<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

if (!isset($data_user) || empty($capbac['status']) || $capbac['status'] !== true) {
    http_response_code(404);
    exit;
}

if (isset($_POST['type']) && $_POST['type'] === 'the_cao') {

    // CSRF
    if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        die(JsonMsg('error', 'CSRF token không hợp lệ!'));
    }
    if ($db->site("status_demo") == 1) {
        die(JsonMsg('error', 'Đây là trang web demo, không thể thực hiện chức năng này!'));
    }
    if (!check_login()) {
        die(JsonMsg('error', 'Vui lòng đăng nhập !'));
    }
    $id = intval($_POST['id'] ?? 0);
    if (empty($id)) {
        die(JsonMsg('error', 'Thiếu ID !'));
    }
    $row = $db->get_row("SELECT * FROM `invoices` WHERE `id` = {$id} AND `card_info` IS NOT NULL AND `card_info` <> ''");
    if (!$row) {
        die(JsonMsg('error', 'Không tìm thấy hóa đơn trong hệ thống !'));
    }
    $status = status_invoice_admin($row['id']);
    $cardLogs = $db->get_list("SELECT * FROM `cards_log` WHERE `charging_id` = {$id} ORDER BY id DESC LIMIT 5");
    $pin = '';
    $seri = '';
    if (!empty($row['card_info'])) {
        $tmp = explode('|', $row['card_info'], 2);
        $pin = $tmp[0] ?? '';
        $seri = $tmp[1] ?? '';
    }
    $ilog = 1;
    $actionButtons = '';
    // chỉ hiện khi unpaid
    if ($row['status'] === 'unpaid') {
        $actionButtons .= '
            <button type="submit" name="submit" class="btn btn-dark btn-sm me-1" value="gui_lai_the"><i class="ti ti-rotate"></i> Gửi lại thẻ</button>
            <button type="submit" name="submit" class="btn btn-light btn-sm me-1" value="lay_trang_thai_the"><i class="ti ti-download"></i> Lấy Trạng Thái</button>
        ';
    }
    $modalContent = '
    <div class="modal fade" id="CharginModal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form method="POST" action="'.BASE_URL("ajax/action/the-cao").' " accept-charset="UTF-8">
                <input name="csrf_token" type="hidden" value="'.$csrf_token.'">
                <input type="hidden" name="id" value="'.$row['id'].'">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            ID: ' . $row['id'] . ' , Request ID: ' . $row['request_id'] . ',
                            Khách: ' . getRowRealtime('users', $row['user_id'], 'username') . '
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <ul class="nav nav-tabs" id="customTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active"
                                        id="tab-home"
                                        data-bs-toggle="tab"
                                        data-bs-target="#tab-home-pane"
                                        type="button"
                                        role="tab">
                                    Xử lý thẻ
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"
                                        id="tab-log"
                                        data-bs-toggle="tab"
                                        data-bs-target="#tab-log-pane"
                                        type="button"
                                        role="tab">
                                    Log từ NCC
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content pt-2">

                            <!-- TAB XỬ LÝ THẺ -->
                            <div class="tab-pane fade show active" id="tab-home-pane" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle mb-0 text-nowrap">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Loại thẻ</th>
                                                <th>Mã nạp / Serial</th>
                                                <th>Trạng thái</th>
                                                <th>Khai / Nhận</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>' . htmlspecialchars($row['paygate']) . '</td>
                                                <td>
                                                    <strong>' . htmlspecialchars($pin) . '</strong><br>
                                                    ' . htmlspecialchars($seri) . '
                                                </td>
                                                <td>
                                                    <span class="' . $status['class'] . '">
                                                        ' . $status['text'] . '
                                                    </span>
                                                </td>
                                                <td>
                                                    ' . format_number($row['pay_amount']) . ' đ<br>
                                                    <span class="text-success">
                                                        ' . format_number($row['net_amount']) . ' đ
                                                    </span>
                                                </td>
                                                <td class="text-nowrap">
                                                    ' . $actionButtons . '
                                                    <button type="submit" name="submit" class="btn btn-info btn-sm me-1" value="phuc_hoi_the"><i class="ti ti-health-recognition"></i> Phục hồi</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="position-relative mt-1">
                                    <label for="admin_note" class="col-form-label font-weight-bold">Ghi chú:</label>
                                    <textarea class="form-control">' . $row['admin_note'] . '</textarea>

                                </div>
                                <!-- NÚT ĐÚNG / SAI Ở DƯỚI – BÊN PHẢI -->
                                
                            </div>


                            <!-- TAB LOG NCC -->
                            <div class="tab-pane fade" id="tab-log-pane" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="60">#</th>
                                                <th>Log</th>
                                                <th width="120">NCC</th>
                                                <th width="180">Thời gian</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
            if (!empty($cardLogs)) {
                foreach ($cardLogs as $data) {
                    $modalContent .= '
                                            <tr>
                                                <td>' . $ilog++ . '</td>
                                                <td>
                                                    <textarea class="form-control" rows="2" readonly>'
                        . htmlspecialchars($data['log']) . '
                                                    </textarea>
                                                </td>
                                                <td>DoiThe</td>
                                                <td>' . format_datetime($data['created_at']) . '</td>
                                            </tr>';
                }
            }
            $modalContent .= '
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>';


    echo $modalContent; // Send the content back to AJAX

}