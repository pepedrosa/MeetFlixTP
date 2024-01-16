<?php
// add_to_library.php

// Inclua a conexão com o banco de dados e outras configurações necessárias
include("db_connection.php");

// Verifique se o parâmetro MediaID foi passado na URL
if (isset($_GET['mediaID'])) {
    $mediaID = $_GET['mediaID'];

    // Inicie a sessão
    session_start();   

    // Verifique se o utilizador está logado
    if (isset($_SESSION['UserID'])) {
        $userID = $_SESSION['UserID'];

        // Verifique se o media já está na biblioteca do utilizador
        $checkSql = "SELECT * FROM user_library WHERE UserID = '$userID' AND MediaID = '$mediaID'";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {
            echo "Este media já está na sua biblioteca.";
        } else {
            // Adicione o media à biblioteca
            $insertSql = "INSERT INTO user_library (UserID, MediaID) VALUES ('$userID', '$mediaID')";

            if ($conn->query($insertSql) === TRUE) {
                header("Location: library.php");
                echo "Media adicionado à biblioteca com sucesso!";
                exit();
            } else {
                echo "Erro ao adicionar media à biblioteca: " . $conn->error;
            }
        }
    } else {
        echo "Precisa estar logado para adicionar media à sua biblioteca.";
    }
} else {
    echo "ID de media não fornecido.";
}
?>
