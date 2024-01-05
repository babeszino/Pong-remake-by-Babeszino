<?php
require_once "config.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION["username"];
    $result = $_POST["result"];

    if ($result == "win") {
        $sql = "UPDATE users SET wins = wins + 1 WHERE username = ?";
    } else {
        $sql = "UPDATE users SET losses = losses + 1 WHERE username = ?";
    }

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($link);
?>