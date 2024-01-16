<?php
session_start();

include("db_connection.php");

// Verificar se o usuário está autenticado
if (!isset($_SESSION["UserID"])) {
    header("Location: index.php");
    exit();
}

// Recuperar informações do perfil do usuário
$userID = $_SESSION["UserID"];
$sql = "SELECT * FROM users WHERE UserID = $userID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row["Username"];
    $email = $row["Email"];
    $fullName = $row["FullName"];
    // ... outras informações do perfil

    // Agora você pode usar essas variáveis para exibir as informações no HTML
} else {
    // Usuário não encontrado, redirecione para a página de login
    header("Location: index.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="profile.css">
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

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Perfil do Utilizador</h5>
            <p class="card-text">Nome do Utilizador: <?php echo $username; ?></p>
            <p class="card-text">Email: <?php echo $email; ?></p>
            <p class="card-text">Nome Completo: <?php echo $fullName; ?></p>
            
            <!-- Botão para editar perfil -->
            <button class="btn btn-danger float-right" data-toggle="modal" data-target="#editProfileModal">Editar Perfil</button>
        </div>
    </div>
</div>

<!-- Modal para edição de perfil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Editar Perfil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulário para edição de perfil -->
                <form action="edit_profile.php" method="post">
                    <div class="form-group">
                        <label for="editUsername">Nome de Utilizador:</label>
                        <input type="text" class="form-control" id="editUsername" name="editUsername" value="<?php echo $username; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email:</label>
                        <input type="email" class="form-control" id="editEmail" name="editEmail" value="<?php echo $email; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="editFullName">Nome Completo:</label>
                        <input type="text" class="form-control" id="editFullName" name="editFullName" value="<?php echo $fullName; ?>" required>
                    </div>
                    

                    <button type="submit" class="btn btn-danger">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>









