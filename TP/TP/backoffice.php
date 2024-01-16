<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice de Gestão de Projeto</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="backoffice.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="container mt-4">
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
                    <a class="nav-link" href="logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </nav>

    <h1 class="mb-4">Estatísticas do Meetflix</h1>

    <div class="card-deck">
        <?php
        // Incluir o arquivo de conexão com o banco de dados
        include 'db_connection.php';

        try {
            // Consultas SQL para estatísticas
            $queries = [
                "Número de utilizadores registados" => "SELECT COUNT(*) AS total_utilizadores FROM users",
                "Número de filmes/séries registados" => "SELECT COUNT(*) AS total_filmes FROM media",
                "Filme/Série mais compartilhada" => "SELECT m.Title, COUNT(*) AS total_compartilhamentos FROM sharing s INNER JOIN media m ON s.MediaID = m.MediaID GROUP BY s.MediaID ORDER BY total_compartilhamentos DESC LIMIT 1",
                "Filme/Série mais comentada" => "SELECT m.Title, COUNT(*) AS total_comentarios FROM comments c INNER JOIN media m ON c.MediaID = m.MediaID GROUP BY c.MediaID ORDER BY total_comentarios DESC LIMIT 1",
                "Utilizador que adicionou mais filmes/séries à sua biblioteca" => "SELECT u.Username, COUNT(*) AS total_medias_adicionadas FROM user_library ul INNER JOIN users u ON ul.UserID = u.UserID GROUP BY ul.UserID ORDER BY total_medias_adicionadas DESC LIMIT 1",
                "Filme/Série com o melhor rating" => "SELECT m.Title, AVG(r.rating) AS media_avaliacao FROM ratings r INNER JOIN media m ON r.MediaID = m.MediaID GROUP BY r.MediaID ORDER BY media_avaliacao DESC LIMIT 1",
            ];

            foreach ($queries as $categoria => $sql) {
                // Executar a consulta
                $result = $conn->query($sql);

                // Obter o resultado
                $row = $result->fetch_assoc();

                // Exibir o resultado em um card
                echo '<div class="card">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $categoria . '</h5>';
                echo '<p class="card-text">';

                // Se a consulta retornar um resultado, exibir o valor
                if ($row) {
                    echo $row[array_key_first($row)];
                } else {
                    echo 'Sem dados';
                }

                echo '</p>';
                echo '</div>';
                echo '</div>';
            }

            // Consulta SQL para obter o número de filmes por género
            $queryGeneros = "SELECT g.Name AS Genre, COUNT(*) AS total_filmes 
                FROM media m
                INNER JOIN genre g ON m.GenreID = g.GenreID
                GROUP BY m.GenreID";

            $resultGeneros = $conn->query($queryGeneros);

            // Extrair os dados do resultado
            $generos = [];
            $quantidades = [];
            while ($rowGenero = $resultGeneros->fetch_assoc()) {
                $generos[] = $rowGenero['Genre'];
                $quantidades[] = $rowGenero['total_filmes'];
            }

        } catch (Exception $e) {
            echo '<div class="alert alert-danger" role="alert">Erro na execução das consultas: ' . $e->getMessage() . '</div>';
        }

        // Fechar a conexão
        $conn->close();
        ?>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Número de Filmes por Género</h5>
                    <canvas id="chartGeneros" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Script JavaScript para renderizar o gráfico -->
    <script>
        var ctx = document.getElementById("chartGeneros").getContext("2d");
        var chartGeneros = new Chart(ctx, {
            type: "bar",
            data: {
                labels: <?php echo json_encode($generos); ?>,
                datasets: [{
                    label: "Número de Filmes",
                    data: <?php echo json_encode($quantidades); ?>,
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
