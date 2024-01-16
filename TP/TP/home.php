<?php
// home.php

session_start();

// Verifique se o utilizador está autenticado
if (!isset($_SESSION["Username"])) {
    header("Location: index.php"); // Redireciona se não estiver autenticado
    exit();
}

// Inclua a conexão com o banco de dados ou outras configurações necessárias
include("db_connection.php");



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meetflix - Página Inicial</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="home.css">
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

        <!-- Informações do utilizador -->
        <div class="row mb-5">
            <div class="col">
                <h1>Bem-vindo ao Meetflix, <?php echo $_SESSION["Username"]; ?>!</h1>
                
            </div>
        </div>

        <!-- Carrossel de Thumbnails -->
        <div id="carouselMovies" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner"> 
                <?php
                // Consulta para obter as informações dos filmes
                $queryMovies = "SELECT Title, thumbnail FROM media WHERE thumbnail IS NOT NULL";
                $resultMovies = $conn->query($queryMovies);

                $active = true; // Define o primeiro item como ativo

                // Loop através dos resultados
                while ($rowMovie = $resultMovies->fetch_assoc()) {
                    echo '<div class="carousel-item' . ($active ? ' active' : '') . '">';
                    echo '<img src="thumbnails/' . $rowMovie['thumbnail'] . '" class="d-block w-100 img-fluid" alt="' . $rowMovie['Title'] . '" style="max-height: 400px; width: auto; margin: auto; object-fit: contain;">';
                    echo '</div>';

                    $active = false; // Desativa a flag após o primeiro item
                }
                ?>
            </div>
            <a class="carousel-control-prev" href="#carouselMovies" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Anterior</span>
            </a>
            <a class="carousel-control-next" href="#carouselMovies" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Próximo</span>
            </a>
        </div>
    </div>

    

    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
