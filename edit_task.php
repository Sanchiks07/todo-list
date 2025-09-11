<?php

require_once "Database.php";

$config = require("config.php");
$db = new Database($config["database"]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $list_name = $_POST['list'];

    $list_id_statement = $db->query("SELECT id FROM lists WHERE name = ?", [$list_name]);
    $list_id = $list_id_statement->fetchColumn();

    $sql = "UPDATE tasks SET task = ?, description = ?, list_id = ?, due_date = ? WHERE id = ?";
    $db->query($sql, [$title, $description, $list_id, $due_date, $id]);

    header("Location: index.php");
    exit();
}

?>