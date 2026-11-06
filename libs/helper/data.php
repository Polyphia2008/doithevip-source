<?php
function getRowRealtime($table, $id, $row)
{
    global $db;
    return $db->get_row("SELECT * FROM `$table` WHERE `id` = '$id'")[$row] ?? '';
}
function get_informations($code) {
    global $db;
    $row = $db->get_row("SELECT `description` FROM `informations` WHERE `code` = '".addslashes($code)."' AND `status` = 1");
    return $row ? $row['description'] : null;
}

function insert_log($user_id, $reason)
{
    global $db;
    $db->insert("logs", [
        'user_id' => $user_id,
        'ip' => myip(),
        'device' => $_SERVER['HTTP_USER_AGENT'],
        'create_date' => gettime(),
        'action' => $reason,
    ]);
}
function insert_cards_log($charging_id, $log)
{
    global $db;

    return $db->insert('cards_log', [
        'charging_id' => $charging_id,
        'log'         => $log,
        'created_at'  => gettime(),
        'updated_at'  => gettime(),
    ]);
}

function pusher($username = null, $data_array = null)
{
    global $db;
    $pushdata = $db->get_row("SELECT * FROM `tb_pusher` ORDER BY RAND() LIMIT 1");
    $options = array(
        'cluster' => $pushdata['pusher_cluster'],
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        $pushdata['pusher_key'],
        $pushdata['pusher_secret'],
        (int)$pushdata['pusher_app_id'],
        $options
    );
    return $pusher->trigger($username, 'realtime', $data_array);
}

function load_module_data(string $area, string $module)
{
    $module = str_replace(['..', '\\'], '', strval($module));
    $area = $area === 'admin' ? 'admin' : 'client';
    $base = realpath(__DIR__ . '/data/modules/' . $area);
    $file = realpath(__DIR__ . '/data/modules/' . $area . '/' . $module . '.php');

    if (!$base || !$file || !is_file($file) || strpos($file, $base) !== 0) {
        return false;
    }

    $vars = (static function (string $path): array {
        extract($GLOBALS, EXTR_SKIP | EXTR_REFS);

        $before = get_defined_vars();
        include $path;
        $after = get_defined_vars();

        $result = [];
        foreach ($after as $key => $value) {
            if ($key === 'path' || $key === 'before' || $key === 'after') {
                continue;
            }
            if (!array_key_exists($key, $before) || $before[$key] !== $value) {
                $result[$key] = $value;
            }
        }

        return $result;
    })($file);

    foreach ($vars as $key => $value) {
        $GLOBALS[$key] = $value;
    }

    return true;
}

function load_client_data($module)
{
    return load_module_data('client', $module);
}

function load_admin_data($module)
{
    return load_module_data('admin', $module);
}
