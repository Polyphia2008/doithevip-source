# Báo cáo kiểm tra mã nguồn (keylogger / backdoor)

Phạm vi: toàn bộ source PHP/JS/HTML/SQL (kể cả `vendor/` và `assets/`), quét tĩnh bằng grep/regex.

## Kết luận
Không phát hiện keylogger hoặc backdoor trong code của shop (ngoài `vendor/`, `assets/` thư viện).

## Đã kiểm tra
- Hàm thực thi code/lệnh (`eval`, `system`, `exec`, `shell_exec`, `assert`, `popen`...) trong code shop: không có.
  `eval(` chỉ xuất hiện trong danh sách chặn của `clean_input` (libs/classes/functions.php).
- Mã bị làm rối (base64+gzinflate, `$$var`, `chr()` nối, chuỗi hex, chuỗi base64 dài): không có.
- `include/require` theo tham số người dùng: không có.
- Webshell/file PHP lạ trong `assets/`, `storage/`, `upload/`: không có
  (chỉ 2 file mẫu của CKEditor: `samples/old/*posteddata.php`, vô hại).
- JS keylogger: các `keydown/keyup` chỉ nằm trong thư viện (bootstrap, select2, ckeditor, jquery-ui...) và
  `onkeyup=checkcoupon()` (kiểm tra mã giảm giá). JS tự viết (`assets/client/js`, `assets/admin/js/app.js`) không có
  sendBeacon/WebSocket/XHR gửi ra ngoài, không đọc `document.cookie`.
- `vendor/`: khớp 100% với `composer.lock` (không thừa/thiếu package). `eval` chỉ ở htmlpurifier/phpseclib (đúng gốc).
- Kết nối ra ngoài (đều là cổng thanh toán/dịch vụ chính danh, cấu hình qua admin):
  `doithevip.com` (nạp thẻ), `pinocloud.vn`, `api.sieuthicode.net`, `api.rpay.vn` (check giao dịch ngân hàng),
  `challenges.cloudflare.com` (Turnstile), Google/Facebook OAuth, CDN (jsdelivr, cdnjs, datatables).

## Lưu ý rủi ro (không phải backdoor nhưng nên xử lý)
1. `database/shopgame.sql` seed sẵn `admin/admin` và `thanhvien` dùng mật khẩu MD5 (`password_hash=md5`) -> đổi ngay.
2. `CURLOPT_SSL_VERIFYPEER=false` ở nhiều chỗ curl (kể cả gọi API nạp thẻ chứa partner_key) -> dễ bị MITM.
3. `install.php` ghi `AUTH_URL/AUTH_NAME` cố định về tác giả (hiển thị "Create By" ở footer admin) - chỉ là branding.
4. Footer có `referer` gửi `HTTP_HOST` của site tới `curl_get` (các API thanh toán sẽ biết domain của bạn).
5. Thư mục upload `mkdir 0777`; chỉ lọc đuôi jpg/png/gif theo tên file (không kiểm MIME).
6. SQL dùng nối chuỗi + `addslashes`/`anti_xss` thay vì prepared statement -> nên rà SQLi.
7. `callback` nạp thẻ chỉ kiểm IP bằng DNS của doithevip.com.
