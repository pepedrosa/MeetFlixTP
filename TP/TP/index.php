<?php
session_start();

include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["Username"];
    $password = $_POST["Password"];

    $sql = "SELECT UserID FROM users WHERE Username = '$username' AND Password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login bem-sucedido
        $row = $result->fetch_assoc();
        $_SESSION["UserID"] = $row["UserID"];
        $_SESSION["Username"] = $username;
        if ($username === "admin" && $password === "admin") {
          header("Location: backoffice.php");
          exit();
      } else {
          header("Location: home.php");
          exit();
      }
    } else {
        // Login falhou
        $_SESSION["login_error"] = "Nome de utilizador ou palavra-passe incorretos.";
    }
}

$conn->close();
?>









<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meetflix</title>
  <link rel="icon" href="./imgs/m-solid.svg" type="image/svg+xml">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
  <link rel="stylesheet" href="index.css">
</head>

<body>
  <!-- Container fluid -->
  <div class="container-fluid">
    
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-transparent container-fluid">
  <a class="navbar-brand mr-auto ml-5 mt-2" href="#"><img src="./imgs/logoMf.png" alt="MeetFlix Logo" class="img-fluid"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto ml-lg-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="idiomaDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-globe"></i>Português
        </a>
        <div class="dropdown-menu" aria-labelledby="idiomaDropdown">
          <a class="dropdown-item" href="#">Inglês</a>
          <a class="dropdown-item" href="#">Espanhol</a>
        </div>
      </li>
      <li class="nav-item iniciar-sessao">
        <button type="button" class="btn btn-iniciar-sessao" data-toggle="modal" data-target="#loginModal">Iniciar Sessão</button>
      </li>
    </ul>
  </div>
</nav>
    <!-- Row 1 -->
    <div class="row banner">
      <div class="col">
        <div class="banner-text-container">
          <h1 class="banner-text">Bem-vindo ao Meetflix</h1>
          <h2 class="banner-subtext">Uma plataforma envolvente e fácil de usar, proporcionando controlo total sobre suas preferências audiovisuais.</h2>
        </div>
        <img src="./imgs/banner5.png" alt="Imagem de banner" class="img-fluid imgbanner w-100">
      </div>
    </div>

    <!-- Row 2 -->
    <div class="row share">
        <div class="col-md-6 col-sm-12 text-center">
          <img src="./imgs/share.png" alt="Imagem" class="img-fluid share-main-image">
        </div>
        <div class="col-md-6 col-sm-12">
          <h1>Partilhe e colabore</h1>
          <p>Compartilhe as suas descobertas cinematográficas com outros utilizadores.
            Colabore em listas de reprodução, sugestões e recomendações para uma comunidade de amantes de cinema.</p>
        </div>
    </div>

    <!-- Row 3 -->
    <div class="row calendar">
      <div class="col-md-6 col-sm-12">
        <h1>Calendarize e notas</h1>
        <p>Calendarize as suas próximas sessões.
          Adicione notas personalizadas para registar as suas opiniões e pensamentos sobre cada filme ou série.</p>
      </div>
      <div class="col-md-6 col-sm-12 text-center">
        <img src="./imgs/calendar.png" alt="Imagem" class="img-fluid calendar-main-image">
      </div>
    </div>

    <!-- Row 4 -->
    <div class="row avalie">
      <div class="col-md-6 col-sm-12 text-center">
        <img src="./imgs/avalie.png" alt="Imagem" class="img-fluid avalie-main-image">
      </div>
      <div class="col-md-6 col-sm-12">
        <h1>Avalie, comente e anexe</h1>
        <p>Deixe a sua marca na comunidade com avaliações e comentários detalhados.
          Anexe imagens, vídeos ou outros arquivos para enriquecer suas opiniões.</p>
      </div>
    </div>
     <!-- Row 5 -->
     <div class="row sobrenos">
        <div class="col-md-12 text-center">
            <h1>Sobre Nós</h1>
        </div>
        <div class="col-md-4 col-sm-12 text-center">
            <h3>Plataforma dedicada à gestão de filmes e séries</h3>
            <p>Missão de proporcionar uma experiência única para os amantes do entretenimento.
                Objetivo de permitir que os utilizadores organizem, descubram e desfrutem de uma ampla variedade de conteúdos audiovisuais.</p>
        </div>
        <div class="col-md-4 col-sm-12 text-center">
            <h3>Recursos avançados de organização</h3>
            <p>Controlo total sobre o que foi assistido, o que está por vir e os favoritos.
                Ferramentas para uma gestão eficiente do histórico de visualizações e preferências.</p>
        </div>
        <div class="col-md-4 col-sm-12 text-center">
            <h3>Ampla biblioteca de conteúdos audiovisuais</h3>
            <p>Inclusão dos melhores filmes do momento.
                Destaque para séries populares.
                Presença de clássicos atemporais para uma variedade de opções.</p>
        </div>
     </div>

    <!-- Rodapé -->
  <footer class="bg-dark text-light text-center py-4">
    <p>&copy; 2023 Meetflix | Realizado por Francisco Correia & Pedro Pedrosa</p>
    </footer>
  </div>


<!-- Modal de Iniciar Sessão -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Iniciar Sessão</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<form id="loginForm" action="index.php" method="post">
    <!-- Campos do formulário -->
    <div class="form-group">
        <label for="Username">Nome de utilizador:</label>
        <input type="text" class="form-control" id="Username" name="Username" placeholder="Digite o seu nome de utilizador">
    </div>
    <div class="form-group">
        <label for="Password">Palavra-passe:</label>
        <input type="password" class="form-control" id="Password" name="Password" placeholder="Digite a sua palavra-passe">
    </div>
    <!-- Exibir mensagem de erro, se houver -->
    <?php if (isset($_SESSION["login_error"])) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION["login_error"]; ?>
        </div>
        <?php unset($_SESSION["login_error"]); ?>
    <?php endif; ?>
    <!-- Botão de login -->
    <button type="submit" class="btn btn-iniciar-sessao">Entrar</button>
    <p class="mt-2 text-center">Ainda não tens conta? <a href="#" data-toggle="modal" data-target="#registerModal" data-dismiss="modal">Registe-se</a>.</p>
</form>

      </div>
    </div>
  </div>
</div>


<!-- Modal de Registo -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registerModalLabel">Registar Conta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <form id="registerForm" action="register.php" method="post">
          <!-- Campos do formulário de registo -->
          <div class="form-group">
            <label for="registerUsername">Nome de utilizador:</label>
            <input type="text" class="form-control" id="RUsername" name="RUsername" placeholder="Digite o seu nome de utilizador" required>
          </div>
          <div class="form-group">
            <label for="Email">Email:</label>
            <input type="email" class="form-control" id="REmail" name="REmail" placeholder="Digite o seu email" required>
          </div>
          <div class="form-group">
            <label for="FullName">Nome completo:</label>
            <input type="text" class="form-control" id="RFullName" name="RFullName" placeholder="Digite o seu nome completo" required>
          </div>
          <div class="form-group">
            <label for="registerPassword">Palavra-passe:</label>
            <input type="password" class="form-control" id="RPassword" name="RPassword" placeholder="Digite a sua palavra-passe" required>
          </div>
          <!-- Mensagem de Sucesso -->
          <?php if (isset($_SESSION["registration_success"])) : ?>
            <div class="alert alert-success" role="alert">
              <?php echo $_SESSION["registration_success"]; ?>
            </div>
            <?php unset($_SESSION["registration_success"]); ?>
          <?php endif; ?>
          <!-- Mensagem de Erro -->
          <?php if (isset($_SESSION["registration_error"])) : ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $_SESSION["registration_error"]; ?>
            </div>
            <?php unset($_SESSION["registration_error"]); ?>
          <?php endif; ?>
          <!-- Botão de registo -->
          <button type="submit" class="btn btn-iniciar-sessao">Registar</button>
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
