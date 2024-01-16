<?php
// shared_media.php

// Inclua a conexão com o banco de dados e outras configurações necessárias
include("db_connection.php");

// Certifique-se de que o utilizador está autenticado
session_start();
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php"); // Redirecione para a página de login se o utilizador não estiver autenticado
    exit();
}

$userID = $_SESSION['UserID'];

// Consulta SQL para obter os filmes partilhados com o utilizador
$sql = "SELECT media.Title, media.Description, media.releaseDate, genre.Name AS GenreName, users.UserName AS SharedBy
        FROM sharing
        INNER JOIN media ON sharing.MediaID = media.MediaID
        INNER JOIN genre ON media.GenreID = genre.GenreID
        INNER JOIN users ON sharing.ShareByUserID = users.UserID
        WHERE sharing.ShareWithUserID = $userID";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filmes Partilhados</title>
    
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
        <h2>Filmes Partilhados</h2>

        <?php
        if ($result->num_rows > 0) {
            // Exiba os filmes partilhados em uma tabela
            echo '<table class="table table-striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Título</th>';
            echo '<th>Descrição</th>';
            echo '<th>Data de Lançamento</th>';
            echo '<th>Género</th>';
            echo '<th>Partilhado por</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['Title'] . '</td>';
                echo '<td>' . $row['Description'] . '</td>';
                echo '<td>' . $row['releaseDate'] . '</td>';
                echo '<td>' . $row['GenreName'] . '</td>';
                echo '<td>' . $row['SharedBy'] . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>Não foram encontrados filmes partilhados.</p>';
        }
        ?>
    </div>


    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
