<?php
// share_media.php

// Inicie a sessão
session_start();

// Verifique se a chave 'userID' está definida na sessão
if (isset($_SESSION['UserID'])) {
    // Recupere o ID do utilizador da sessão
    $userID = $_SESSION['UserID'];

    // Inclua a conexão com o banco de dados e outras configurações necessárias
    include("db_connection.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recupere os dados do formulário
        $mediaID = $_POST["mediaID"];
        $shareWithUserID = $_POST["shareWithUserID"];

        // Verifique se o utilizador com o ID especificado existe
        $checkUserSql = "SELECT * FROM users WHERE UserID = $shareWithUserID";
        $checkUserResult = $conn->query($checkUserSql);

        if ($checkUserResult->num_rows > 0) {
            // O utilizador existe, então podemos adicionar a entrada à tabela de compartilhamento
            $shareSql = "INSERT INTO sharing (ShareByUserID, ShareWithUserID, MediaID) VALUES ($userID, $shareWithUserID, $mediaID)";
            $conn->query($shareSql);

            // Redirecione de volta para a página de medias
            header("Location: medias.php");
            exit();
        } else {
            // Usuário com o ID especificado não encontrado
            echo "Utilizador não encontrado.";
        }
    }
} else {
    // Se a chave 'userID' não estiver definida, redirecione para a página de login ou faça o que for apropriado para o seu caso
    header("Location: index.php");
    exit();
}
?>

