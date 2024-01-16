<?php
// medias.php

// Inclua a conexão com o banco de dados e outras configurações necessárias
include("db_connection.php");

session_start();

// Obtenha o userID da sessão
$userID = $_SESSION['UserID'];

// Função para listar os medias em uma tabela
function listMediasTable($conn,$userID)
{
    // Condições de pesquisa
    $searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';
    $searchGenre = isset($_GET['searchGenre']) ? $_GET['searchGenre'] : '';

    // Consulta SQL para obter todos os gêneros
    $genresSql = "SELECT * FROM genre";
    $genresResult = $conn->query($genresSql);

    // Formulário de pesquisa
    echo '<form class="form-inline mb-3" method="get" action="medias.php">';
    echo '<div class="form-group mr-2">';
    echo '<label for="searchName">Nome:</label>';
    echo '<input type="text" class="form-control" id="searchName" name="searchName" placeholder="Digite o nome" value="' . $searchName . '">';
    echo '</div>';
    echo '<div class="form-group mr-2">';
    echo '<label for="searchGenre">Género:</label>';
    echo '<select class="form-control" id="searchGenre" name="searchGenre">';
    echo '<option value="">Todos</option>';

    // Loop para exibir opções da combobox
    while ($genreRow = $genresResult->fetch_assoc()) {
        $selected = ($searchGenre == $genreRow['GenreID']) ? 'selected' : '';
        echo '<option value="' . $genreRow['GenreID'] . '" ' . $selected . '>' . $genreRow['Name'] . '</option>';
    }

    echo '</select>';
    echo '</div>';
    echo '<button type="submit" class="btn btn-danger">Pesquisar</button>';
    echo '</form>';

    // Consulta SQL para obter todos os medias com base nas condições de pesquisa
    $sql = "SELECT * FROM media WHERE Title LIKE '%$searchName%'";

    if ($searchGenre != '') {
        $sql .= " AND GenreID = $searchGenre";
    }

    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        // Início da tabela
        echo '<div class="mb-3">';
        echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#addMediaModal">Adicionar</button>';
        echo '</div>';

        echo '<table class="table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Thumbnail</th>';
        echo '<th>Título</th>';
        echo '<th>Descrição</th>';
        echo '<th>Data de Lançamento</th>';
        echo '<th>Género</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Loop através dos resultados e adicione linhas à tabela
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td><img src="thumbnails/' . $row['thumbnail'] . '" alt="Thumbnail" style="width: 50px; height: 50px;"></td>';
            echo '<td>' . $row['Title'] . '</td>';
            echo '<td>' . $row['Description'] . '</td>';
            echo '<td>' . $row['releaseDate'] . '</td>';

            // Obter o nome do género com base no GenreID
            $genreSql = "SELECT Name FROM genre WHERE GenreID = " . $row['GenreID'];
            $genreResult = $conn->query($genreSql);
            $genreRow = $genreResult->fetch_assoc();
            $genreName = ($genreRow) ? $genreRow['Name'] : 'N/A';

            echo '<td>' . $genreName . '</td>';
            echo '</tr>';
            echo '<td><a href="add_to_library.php?mediaID=' . $row['MediaID'] . '" class="btn btn-success">Adicionar à Biblioteca</a></td>'; 
            echo '<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#shareMediaModal' . $row['MediaID'] . '">Partilhar</button></td>';
            echo '<td>';
            echo '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#viewCommentsModal' . $row['MediaID'] . '">Ver Comentários</button>';
            echo '</td>';
            echo '<td>';
            echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#viewRateMediaModal' . $row['MediaID'] . '">Ver Avaliações</button>';
            echo '</td>';
    echo '</tr>';

    // Adicione o modal de partilha para cada filme
    echo '<div class="modal fade" id="shareMediaModal' . $row['MediaID'] . '" tabindex="-1" role="dialog" aria-labelledby="shareMediaModalLabel' . $row['MediaID'] . '" aria-hidden="true">';
    echo '<div class="modal-dialog" role="document">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header">';
    echo '<h5 class="modal-title" id="shareMediaModalLabel' . $row['MediaID'] . '">Partilhar Filme</h5>';
    echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
    echo '<span aria-hidden="true">&times;</span>';
    echo '</button>';
    echo '</div>';
    echo '<div class="modal-body">';

    // Formulário para escolher o utilizador para partilhar
    echo '<form action="share_media.php" method="post">';
    echo '<input type="hidden" name="mediaID" value="' . $row['MediaID'] . '">';
    echo '<input type="hidden" name="userID" value="' . $userID . '">'; // Adicione esta linha para enviar o userID
    echo 'Escolha o utilizador para partilhar:';

    // Consulta SQL para obter todos os utilizadores
    $usersSql = "SELECT UserID, UserName FROM users";
    $usersResult = $conn->query($usersSql);

    if ($usersResult->num_rows > 0) {
        echo '<select name="shareWithUserID">';
        while ($userRow = $usersResult->fetch_assoc()) {
            echo '<option value="' . $userRow['UserID'] . '">' . $userRow['UserName'] . '</option>';
        }
        echo '</select>';
    } else {
        echo 'Nenhum utilizador encontrado.';
    }

    echo '<br>';
    echo '<button type="submit" class="btn btn-primary">Partilhar</button>';
    echo '</form>';

    echo '</div>';
    echo '<div class="modal-footer">';
    echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    // Modal para visualizar/comentar
    echo '<div class="modal fade" id="viewCommentsModal' . $row['MediaID'] . '" tabindex="-1" role="dialog" aria-labelledby="viewCommentsModalLabel' . $row['MediaID'] . '" aria-hidden="true">';
    echo '<div class="modal-dialog modal-lg" role="document">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header">';
    echo '<h5 class="modal-title" id="viewCommentsModalLabel' . $row['MediaID'] . '">Comentários</h5>';
    echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
    echo '<span aria-hidden="true">&times;</span>';
    echo '</button>';
    echo '</div>';
    echo '<div class="modal-body">';

    // Consulta SQL para obter os comentários do filme com o nome de utilizador
    $commentsSql = "SELECT comments.*, users.UserName 
                    FROM comments
                    JOIN users ON comments.UserID = users.UserID
                    WHERE comments.MediaID = " . $row['MediaID'];
    $commentsResult = $conn->query($commentsSql);

    if ($commentsResult->num_rows > 0) {
        // Exibir os comentários
        while ($commentRow = $commentsResult->fetch_assoc()) {
            echo '<p><strong>Utilizador:</strong> ' . $commentRow['UserName'] . '</p>';
            echo '<p><strong>Comentário:</strong> ' . $commentRow['Comment'] . '</p>';
            echo '<p><strong>Data de Criação:</strong> ' . $commentRow['CreatedAt'] . '</p>';
            echo '<hr>';
        }
    } else {
        echo '<p>Nenhum comentário encontrado.</p>';
    }

    // Formulário para adicionar comentário
    echo '<form id="addCommentForm' . $row['MediaID'] . '" action="add_comment.php" method="post">';
    echo '<input type="hidden" name="mediaID" value="' . $row['MediaID'] . '">';
    echo '<div class="form-group">';
    echo '<label for="comment">Seu Comentário:</label>';
    echo '<textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>';
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary">Adicionar Comentário</button>';
    echo '</form>';

    echo '</div>';
    echo '<div class="modal-footer">';
    echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    // Modal para avaliar
echo '<div class="modal fade" id="viewRateMediaModal' . $row['MediaID'] . '" tabindex="-1" role="dialog" aria-labelledby="viewRateMediaModalLabel' . $row['MediaID'] . '" aria-hidden="true">';
echo '<div class="modal-dialog modal-lg" role="document">';
echo '<div class="modal-content">';
echo '<div class="modal-header">';
echo '<h5 class="modal-title" id="viewRateMediaModalLabel' . $row['MediaID'] . '">Avaliações para ' . $row['Title'] . '</h5>';
echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
echo '<span aria-hidden="true">&times;</span>';
echo '</button>';
echo '</div>';
echo '<div class="modal-body">';

// Consulta SQL para obter as avaliações do filme com o nome de utilizador
$ratesSql = "SELECT ratings.*, users.UserName 
            FROM ratings
            JOIN users ON ratings.UserID = users.UserID
            WHERE ratings.MediaID = " . $row['MediaID'];
$ratesResult = $conn->query($ratesSql);

if ($ratesResult->num_rows > 0) {
    // Exibir as avaliações
    while ($rateRow = $ratesResult->fetch_assoc()) {
        echo '<p><strong>Utilizador:</strong> ' . $rateRow['UserName'] . '</p>';
        echo '<p><strong>Avaliação:</strong> ' . $rateRow['rating'] . '</p>';
        echo '<p><strong>Data de Criação:</strong> ' . $rateRow['CreatedAt'] . '</p>';
        echo '<hr>';
    }
} else {
    echo '<p>Nenhuma avaliação encontrada.</p>';
}

// Formulário para adicionar/atualizar avaliação
echo '<form id="rateMediaForm' . $row['MediaID'] . '" action="rate_media.php" method="post">';
echo '<input type="hidden" name="mediaID" value="' . $row['MediaID'] . '">';
echo '<div class="form-group">';
echo '<label for="rating">Sua Avaliação (0-5):</label>';
echo '<input type="number" class="form-control" id="rating" name="rating" min="0" max="5" step="0.1" required>';
echo '</div>';
echo '<button type="submit" class="btn btn-primary">Avaliar</button>';
echo '</form>';

echo '</div>';
echo '<div class="modal-footer">';
echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';

// Adicione o modal com o formulário para adicionar um novo media
echo '<div class="modal fade" id="addMediaModal" tabindex="-1" role="dialog" aria-labelledby="addMediaModalLabel" aria-hidden="true">';
echo '<div class="modal-dialog" role="document">';
echo '<div class="modal-content">';
echo '<div class="modal-header">';
echo '<h5 class="modal-title" id="addMediaModalLabel">Adicionar Novo Media</h5>';
echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
echo '<span aria-hidden="true">&times;</span>';
echo '</button>';
echo '</div>';
echo '<div class="modal-body">';
echo '<form action="add_media.php" method="post" enctype="multipart/form-data">';
echo '<div class="form-group">';
echo '<label for="title">Título:</label>';
echo '<input type="text" class="form-control" id="title" name="title" required>';
echo '</div>';
echo '<div class="form-group">';
echo '<label for="description">Descrição:</label>';
echo '<textarea class="form-control" id="description" name="description" rows="3" required></textarea>';
echo '</div>';
echo '<div class="form-group">';
echo '<label for="releaseDate">Data de Lançamento:</label>';
echo '<input type="date" class="form-control" id="releaseDate" name="releaseDate" required>';
echo '</div>';
echo '<div class="form-group">';
echo '<label for="genre">Género:</label>';
echo '<select class="form-control" id="genre" name="genre" required>';

// Consulta SQL para obter todos os géneros
$genresSql = "SELECT * FROM genre";
$genresResult = $conn->query($genresSql);

// Loop para exibir opções no dropdown
while ($genreRow = $genresResult->fetch_assoc()) {
    echo '<option value="' . $genreRow['GenreID'] . '">' . $genreRow['Name'] . '</option>';
}

echo '</select>';
echo '</div>';
echo '<div class="form-group">';
echo '<label for="thumbnail">Thumbnail:</label>';
echo '<input type="file" class="form-control-file" id="thumbnail" name="thumbnail">';
echo '</div>';
echo '<button type="submit" class="btn btn-primary">Adicionar</button>';
echo '</form>';
echo '</div>';
echo '<div class="modal-footer">';
echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';



}

        // Fim da tabela
        echo '</tbody>';
        echo '</table>';
    } else {
        // Se não houver medias
        echo '<p>Não foram encontrados medias.</p>';
    }


}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Filmes/Séries</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="media.css">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-transparent fixed-top">
        <a class="navbar-brand mr-auto ml-5 mt-2" href="home.php">
            <img src="./imgs/logoMf.png" alt="MeetFlix Logo" class="img-fluid logo-img">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto ml-lg-auto">
                
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Perfil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="shared_media.php">Partilhados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="medias.php">Filmes/Séries</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="library.php">Biblioteca</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <div class="container mt-5">
        <h2>Lista de Filmes/Séries</h2>
        <!-- Chame a função para listar os medias em uma tabela -->
        <?php listMediasTable($conn,$userID); ?>        
    </div>

    

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

   

<script>
// JavaScript para carregar dinamicamente os comentários quando o modal é exibido
$('#commentsModal<?php echo $row['MediaID']; ?>').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Botão que acionou o modal
    var mediaID = button.data('mediaid'); // Extraia a informação dos atributos data-*

    // Use uma requisição AJAX para carregar os comentários do script load_comments.php
    $.ajax({
        type: 'GET',
        url: 'load_comments.php?mediaID=' + mediaID,
        success: function (data) {
            $('#commentsContainer' + mediaID).html(data); // Adicione os comentários ao local apropriado
        }
    });
});

// JavaScript para abrir o modal de adição de comentário
$('#addCommentModal<?php echo $row['MediaID']; ?>').on('show.bs.modal', function (event) {
    
});

// JavaScript para enviar o formulário de adição de comentário
$('#addCommentForm<?php echo $row['MediaID']; ?>').submit(function (event) {
    event.preventDefault();

    // Use uma requisição AJAX para enviar o formulário para o script add_comment.php
    $.ajax({
        type: 'POST',
        url: 'add_comment.php',
        data: $('#addCommentForm<?php echo $row['MediaID']; ?>').serialize(),
        success: function () {
            // Recarregue os comentários após adicionar um novo
            $('#commentsModal<?php echo $row['MediaID']; ?>').modal('show');
        }
    });
});
</script>


</body>

</html>
