<?php
$wallet_rows = [];
foreach ($db->get_list("SELECT * FROM `inter_wallet_code` ORDER BY `wallet_name` ASC") as $row) {
    $supportData = json_decode($row['support'] ?? '', true);
    if (!is_array($supportData)) {
        $supportData = [];
    }

    $row['support_rpay'] = ($supportData['rpay'] ?? '0') === '1' ? 1 : 0;
    $row['support_stc'] = ($supportData['stc'] ?? '0') === '1' ? 1 : 0;
    $row['support_pino'] = ($supportData['pino'] ?? '0') === '1' ? 1 : 0;

    $row['support_html'] =
        wallet_provider_ribbon('rpay', $row['support_rpay'] === 1) .
        wallet_provider_ribbon('stc', $row['support_stc'] === 1) .
        wallet_provider_ribbon('pino', $row['support_pino'] === 1);

    $wallet_rows[] = $row;
}
