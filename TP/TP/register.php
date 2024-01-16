<?php
session_start();

include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["RUsername"];
    $email = $_POST["REmail"];
    $fullName = $_POST["RFullName"];
    $password = $_POST["RPassword"];


    $sql = "INSERT INTO users (Username, Email, FullName, Password) VALUES ('$username', '$email', '$fullName', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Registo bem-sucedido
        $_SESSION["registration_success"] = "Registo efetuado com sucesso. Faça login para continuar.";
        header("Location: index.php");
        exit();
    } else {
        // Registo falhou
        $_SESSION["registration_error"] = "Erro no registo. Por favor, tente novamente.";
    }
}

$conn->close();
?>
