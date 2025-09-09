<?php

class Database {
    public $pdo;

    public function __construct($config) {
        $dsn = "mysql:" . http_build_query($config, "", ";");
        $this->pdo = new PDO($dsn);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function query($sql, $params = []) {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement;
    }
}

?>