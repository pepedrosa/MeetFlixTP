<?php
// add_comment.php

// Inclua a conexão com o banco de dados e outras configurações necessárias
include("db_connection.php");

// Inicie a sessão para obter o ID do usuário
session_start();
if (isset($_SESSION['UserID'])) {
    $userID = $_SESSION['UserID'];
} else {
    // Redirecione para a página de login ou exiba uma mensagem de erro
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupere os dados do formulário
    $mediaID = $_POST["mediaID"];
    $comment = $_POST["comment"];

    // Adicione o comentário à base de dados
    $addCommentSql = "INSERT INTO comments (MediaID, UserID, Comment) VALUES ($mediaID, $userID, '$comment')";
    $conn->query($addCommentSql);

    // Redirecione de volta para a página de medias
    header("Location: medias.php");
    exit();
} else {
    echo "Acesso inválido ao script.";
}
?>
