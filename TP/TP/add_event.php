<?php
// add_event.php

session_start();

// Verifique se o usuário está autenticado
if (!isset($_SESSION["UserID"])) {
    echo "Erro: Usuário não autenticado.";
    exit();
}

// Inclua a conexão com o banco de dados e outras configurações necessárias
include("db_connection.php");

// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Função para limpar e validar os dados
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Obtenha o UserID da sessão
    $userID = $_SESSION["UserID"];

    // Validar e obter dados do formulário
    $mediaID = test_input($_POST["mediaID"]);
    $eventTitle = test_input($_POST["eventTitle"]);
    $eventDescription = test_input($_POST["eventDescription"]);
    $eventDateTime = test_input($_POST["eventDateTime"]);

    // Inserir dados na tabela 'events' com UserID
    $sql = "INSERT INTO events (UserID, MediaID, EventTitle, EventDescription, EventDateTime) VALUES ('$userID', '$mediaID', '$eventTitle', '$eventDescription', '$eventDateTime')";

    if ($conn->query($sql) === TRUE) {
        // Redirecionar para a biblioteca após adicionar o evento
        header("Location: library.php");
        exit();
    } else {
        echo "Erro ao adicionar evento: " . $conn->error;
    }
}
?>
