<?php
// add_note.php

session_start();

if (!isset($_SESSION["UserID"])) {
    header("Location: index.php");
    exit();
}

include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_SESSION["UserID"];
    $mediaID = $_POST["mediaID"];
    $noteContent = $_POST["note"];

    // Inserir nota na base de dados
    $insertNoteQuery = "INSERT INTO notes (UserID, MediaID, Note) VALUES ($userID, $mediaID, '$noteContent')";
    
    if ($conn->query($insertNoteQuery) === TRUE) {
        // Nota adicionada com sucesso
        header("Location: library.php");
        exit();
    } else {
        // Erro ao adicionar nota
        echo "Erro ao adicionar nota: " . $conn->error;
    }
}
?>
