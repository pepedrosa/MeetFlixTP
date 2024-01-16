<?php
// library.php

session_start();

// Verificar a autenticação do utilizador
if (!isset($_SESSION["UserID"])) {
    header("Location: index.php"); // Redireciona se não estiver autenticado
    exit();
}

// Incluir a conexão com o banco de dados e outras configurações necessárias
include("db_connection.php");

// Obter o UserID da sessão
$userID = $_SESSION["UserID"];

// Consulta para obter a biblioteca do utilizador
$sql = "SELECT media.MediaID, media.Title,media.thumbnail, media.Description, genre.Name AS GenreName, user_library.AddedAt
        FROM user_library
        INNER JOIN media ON user_library.MediaID = media.MediaID
        INNER JOIN genre ON media.GenreID = genre.GenreID
        WHERE user_library.UserID = $userID";

$result = $conn->query($sql);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meetflix - Sua Biblioteca</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="library.css">
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

        <!-- Biblioteca do utilizador -->
        <h1>Sua Biblioteca</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Thumbnail</th>
                    <th scope="col">Título</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Género</th>
                    <th scope="col">Adicionado em</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td><img src="thumbnails/' . $row['thumbnail'] . '" alt="Thumbnail" style="width: 50px; height: 50px;"></td>';
                    echo '<td>' . $row['Title'] . '</td>';
                    echo '<td>' . $row['Description'] . '</td>';
                    echo '<td>' . $row['GenreName'] . '</td>';
                    echo '<td>' . $row['AddedAt'] . '</td>';
                    echo '<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEventModal' . $row['MediaID'] . '">Adicionar Evento</button></td>';
                    echo '<td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#viewEventsModal' . $row['MediaID'] . '">Ver Eventos</button></td>';
                    echo '<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNoteModal' . $row['MediaID'] .'">Adicionar Nota</button></td>';
                    echo '<td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#viewNotesModal' . $row['MediaID'] .'">Ver Notas</button></td>';
                    echo '</tr>';
            // Modal para adicionar evento
            echo '<div class="modal fade" id="addEventModal' . $row['MediaID'] . '" tabindex="-1" role="dialog" aria-labelledby="addEventModalLabel" aria-hidden="true">';
            echo '<div class="modal-dialog" role="document">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="addEventModalLabel">Adicionar Evento para ' . $row['Title'] . '</h5>';
            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
            echo '<div class="modal-body">';
            // Formulário para adicionar evento
            echo '<form action="add_event.php" method="post">';
            echo '<input type="hidden" name="mediaID" value="' . $row['MediaID'] . '">';
            echo '<div class="form-group">';
            echo '<label for="eventTitle">Nome do Evento:</label>';
            echo '<input type="text" class="form-control" id="eventTitle" name="eventTitle" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="eventDateTime">Data e Hora do Evento:</label>';
            echo '<input type="datetime-local" class="form-control" id="eventDateTime" name="eventDateTime" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="eventNotes">Descrição do Evento:</label>';
            echo '<textarea class="form-control" id="eventDescription" name="eventDescription" rows="3"></textarea>';
            echo '</div>';
            echo '<button type="submit" class="btn btn-primary">Adicionar Evento</button>';
            echo '</form>';
            echo '</div>';
            echo '<div class="modal-footer">';
            echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            // Modal para ver eventos
            echo '<div class="modal fade" id="viewEventsModal' . $row['MediaID'] . '" tabindex="-1" role="dialog" aria-labelledby="viewEventsModalLabel" aria-hidden="true">';
            echo '<div class="modal-dialog" role="document">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="viewEventsModalLabel">Eventos para ' . $row['Title'] . '</h5>';
            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
            echo '<div class="modal-body">';

            // Consulta para obter eventos associados ao item da biblioteca
            $mediaID = $row['MediaID'];
            $userID = $_SESSION["UserID"];
            $eventsQuery = "SELECT * FROM events WHERE MediaID = $mediaID AND UserID = $userID";
            $eventsResult = $conn->query($eventsQuery);


            if ($eventsResult->num_rows > 0) {
                echo '<ul>';
                while ($event = $eventsResult->fetch_assoc()) {
                    echo '<li>' . $event['EventTitle'] . ' - ' . $event['EventDateTime'] . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>Sem eventos associados.</p>';
            }

            echo '</div>';
            echo '<div class="modal-footer">';
            echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            // Modal para adicionar nota
echo '<div class="modal fade" id="addNoteModal' . $row['MediaID'] . '" tabindex="-1" role="dialog" aria-labelledby="addNoteModalLabel" aria-hidden="true">';
echo '<div class="modal-dialog" role="document">';
echo '<div class="modal-content">';
echo '<div class="modal-header">';
echo '<h5 class="modal-title" id="addNoteModalLabel">Adicionar Nota para ' . $row['Title'] . '</h5>';
echo '<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">';
echo '<span aria-hidden="true">&times;</span>';
echo '</button>';
echo '</div>';
echo '<div class="modal-body">';
// Formulário para adicionar nota
echo '<form action="add_note.php" method="post">';
echo '<input type="hidden" name="mediaID" value="' . $row['MediaID'] . '">';
echo '<div class="form-group">';
echo '<label for="note">Nota:</label>';
echo '<textarea class="form-control" id="note" name="note" rows="3" required></textarea>';
echo '</div>';
echo '<button type="submit" class="btn btn-primary">Adicionar Nota</button>';
echo '</form>';
echo '</div>';
echo '<div class="modal-footer">';
echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';

// Modal para ver notas
echo '<div class="modal fade" id="viewNotesModal' . $row['MediaID'] . '" tabindex="-1" role="dialog" aria-labelledby="viewNotesModalLabel" aria-hidden="true">';
echo '<div class="modal-dialog" role="document">';
echo '<div class="modal-content">';
echo '<div class="modal-header">';
echo '<h5 class="modal-title" id="viewNotesModalLabel">Notas para ' . $row['Title'] . '</h5>';
echo '<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">';
echo '<span aria-hidden="true">&times;</span>';
echo '</button>';
echo '</div>';
echo '<div class="modal-body">';

// Consulta para obter notas associadas ao item da biblioteca
$notesQuery = "SELECT * FROM notes WHERE MediaID = {$row['MediaID']} AND UserID = $userID";
$notesResult = $conn->query($notesQuery);

if ($notesResult->num_rows > 0) {
    echo '<ul>';
    while ($note = $notesResult->fetch_assoc()) {
        echo '<li>' . $note['note'] . ' - ' . $note['CreatedAt'] . '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>Sem notas associadas.</p>';
}

echo '</div>';
echo '<div class="modal-footer">';
echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';

    
        
        }
        ?>
            </tbody>
        </table>
    </div>

    


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
