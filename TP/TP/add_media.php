<?php
// add_media.php

// Inclua a conexão com o banco de dados e outras configurações necessárias
include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Processar o formulário quando for enviado

    // Certifique-se de validar e limpar os dados do formulário para evitar SQL injection
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $releaseDate = mysqli_real_escape_string($conn, $_POST['releaseDate']);
    $genreID = mysqli_real_escape_string($conn, $_POST['genre']);

    // Verifique se foi enviado um arquivo de thumbnail
    if ($_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
        // Nome temporário do arquivo
        $tmp_name = $_FILES['thumbnail']['tmp_name'];

        // Nome do arquivo no servidor
        $thumbnailName = $_FILES['thumbnail']['name'];

        // Mova o arquivo para o diretório desejado (certifique-se de ter permissões de escrita)
        move_uploaded_file($tmp_name, 'thumbnails/' . $thumbnailName);
        
        // Insira os dados do novo media na tabela de media, incluindo o nome do arquivo da thumbnail
        $insertMediaSql = "INSERT INTO media (Title, Description, releaseDate, GenreID, thumbnail) VALUES ('$title', '$description', '$releaseDate', '$genreID', '$thumbnailName')";
        
        if ($conn->query($insertMediaSql) === TRUE) {
            // Media adicionado com sucesso
            header("Location: medias.php");
            exit();
        } else {
            // Se houver algum erro ao adicionar o media
            echo "Erro ao adicionar o media: " . $conn->error;
        }
    } else {
        // Se não foi enviado um arquivo de thumbnail ou ocorreu um erro no upload
        echo "Erro no upload da thumbnail.";
    }
}

// Se o script for acessado diretamente sem o formulário ser enviado, redirecione para a página principal
header("Location: index.php");
exit();

?>
