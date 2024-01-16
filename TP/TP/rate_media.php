<?php
// rate_media.php

// Inclua a conexão com o banco de dados e outras configurações necessárias
include("db_connection.php");

// Certifique-se de iniciar a sessão para acessar o UserID
session_start();
$userID = $_SESSION['UserID']; // Certifique-se de que a chave 'userID' corresponde à sua implementação de sessão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupere os dados do formulário
    $mediaID = $_POST["mediaID"];
    $rating = $_POST["rating"];

    // Verifique se a avaliação está dentro do intervalo permitido
    if ($rating >= 0 && $rating <= 5) {
        // Insira a avaliação na tabela ratings
        $rateSql = "INSERT INTO ratings (MediaID, UserID, rating) VALUES ($mediaID, $userID, $rating)";
        $conn->query($rateSql);

        // Redirecione de volta para a página de medias
        header("Location: medias.php");
        exit();
    } else {
        // Avaliação fora do intervalo permitido
        echo "Avaliação inválida. Deve estar entre 0 e 5.";
    }
}
?>
