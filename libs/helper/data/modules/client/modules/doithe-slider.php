<?php
$sliders = $db->get_list("SELECT * FROM sliders WHERE status = 1 ORDER BY sort_order ASC, id DESC");
$top_deposit_users = $db->get_list("SELECT user_id, SUM(net_amount) AS total_net FROM invoices WHERE order_type = 'deposit' GROUP BY user_id ORDER BY total_net DESC LIMIT 5");
