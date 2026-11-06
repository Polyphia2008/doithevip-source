<?php
include_once realpath(__DIR__ . '/../../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__ . '/../../'));
$dotenv->safeLoad();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class DB
{
    private $connect;
    function connect()
    {
        if (!$this->connect) {
            if (!$this->connect) {
                $this->connect = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE']) or die('Error => DATABASE');
                mysqli_query($this->connect, "set names 'utf8mb4'");
            }
        }
    }
    public function get_id_insert()
    {
        $this->connect();
        return mysqli_insert_id($this->connect);
    }
    public function dis_connect()
    {
        if ($this->connect) {
            mysqli_close($this->connect);
        }
    }
    public function query($sql)
    {
        $this->connect();
        $row = $this->connect->query($sql);
        return $row;
    }
    public function insert($table, $data)
    {
        $this->connect();
    
        $fields = [];
        $values = [];
    
        foreach ($data as $key => $value) {
            $fields[] = "`$key`";
    
            if ($value === null) {
                // Nếu null thì để giá trị NULL (không có dấu nháy)
                $values[] = "NULL";
            } else {
                // Nếu không null thì escape bình thường
                $values[] = "'" . mysqli_real_escape_string($this->connect, (string)$value) . "'";
            }
        }
    
        $sql = "INSERT INTO `$table` (" . implode(',', $fields) . ") VALUES (" . implode(',', $values) . ")";
        return mysqli_query($this->connect, $sql);
    }

    public function update($table, $data, $where)
    {
        $this->connect();

        $sql = '';
        foreach ($data as $key => $value) {
            if ($value === null) {
                $escapedValue = 'NULL';
            } else {
                $escapedValue = "'" . mysqli_real_escape_string($this->connect, $value) . "'";
            }
            $sql .= "$key = $escapedValue,";
        }

        // Build the UPDATE query
        $sql = 'UPDATE ' . $table . ' SET ' . rtrim($sql, ',') . ' WHERE ' . $where;

        // Execute the query
        return mysqli_query($this->connect, $sql);
    }

    public function update_value($table, $data, $where, $value1)
    {
        $this->connect();
        $sql = '';
        foreach ($data as $key => $value) {
            $sql .= "$key = '" . mysqli_real_escape_string($this->connect, $value) . "',";
        }
        $sql = 'UPDATE ' . $table . ' SET ' . trim($sql, ',') . ' WHERE ' . $where . ' LIMIT ' . $value1;
        return mysqli_query($this->connect, $sql);
    }
    function cong($table, $data, $sotien, $where)
    {
        $this->connect();
        $row = $this->connect->query("UPDATE `$table` SET `$data` = `$data` + '$sotien' WHERE $where ");
        return $row;
    }
    function tru($table, $data, $sotien, $where)
    {
        $this->connect();
        $row = $this->connect->query("UPDATE `$table` SET `$data` = `$data` - '$sotien' WHERE $where ");
        return $row;
    }
    public function table_exists($table)
    {
        $this->connect();
        $safeTable = preg_replace('/[^a-zA-Z0-9_]/', '', strval($table));
        if ($safeTable === '') {
            return false;
        }
        $result = $this->connect->query("SHOW TABLES LIKE '{$safeTable}'");
        return $result && $result->num_rows > 0;
    }
    public function site($key)
    {
        $this->connect();
        static $hasSettings = null;
        if ($hasSettings === null) {
            $check = $this->connect->query("SHOW TABLES LIKE 'settings'");
            $hasSettings = $check && $check->num_rows > 0;
        }
        if (!$hasSettings) {
            return null;
        }

        $keyEsc = mysqli_real_escape_string($this->connect, $key);
        $row = $this->connect->query("SELECT `value` FROM `settings` WHERE `key` = '{$keyEsc}' LIMIT 1")->fetch_array();
        return $row['value'] ?? null;
    }
    public function set_site($key, $value)
    {
        $this->connect();
        static $hasSettings = null;
        if ($hasSettings === null) {
            $check = $this->connect->query("SHOW TABLES LIKE 'settings'");
            $hasSettings = $check && $check->num_rows > 0;
        }
        if (!$hasSettings) {
            return false;
        }

        $keyEsc = mysqli_real_escape_string($this->connect, $key);
        $valueEsc = mysqli_real_escape_string($this->connect, (string) $value);
        $row = $this->get_row("SELECT `id` FROM `settings` WHERE `key` = '{$keyEsc}' LIMIT 1");
        if ($row) {
            return $this->update('settings', ['value' => $valueEsc], "`key` = '{$keyEsc}'");
        }
        return $this->insert('settings', ['key' => $key, 'value' => $value]);
    }
    public function remove($table, $where)
    {
        $this->connect();
        $sql = "DELETE FROM $table WHERE $where";
        return mysqli_query($this->connect, $sql);
    }
    public function get_list($sql)
    {
        $this->connect();
        $result = mysqli_query($this->connect, $sql);
        if (!$result) {
            die('Câu truy vấn bị sai');
        }
        $return = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $return[] = $row;
        }
        mysqli_free_result($result);
        return $return;
    }
    public function get_row($sql)
    {
        $this->connect();
        $result = mysqli_query($this->connect, $sql);
        if (!$result) {
            die('Câu truy vấn bị sai');
        }
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        if ($row) {
            return $row;
        }
        return false;
    }
    public function num_rows($sql)
    {
        $this->connect();
        $result = mysqli_query($this->connect, $sql);
        if (!$result) {
            die('Câu truy vấn bị sai');
        }
        $row = mysqli_num_rows($result);
        mysqli_free_result($result);
        if ($row) {
            return $row;
        }
        return false;
    }
}