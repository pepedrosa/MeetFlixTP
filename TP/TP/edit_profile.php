<?php
session_start();

include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST["editUsername"];
    $newEmail = $_POST["editEmail"];
    $newFullName = $_POST["editFullName"];

    $sql = "UPDATE users SET Username = '$newUsername', Email = '$newEmail', FullName = '$newFullName' WHERE UserID = {$_SESSION['UserID']}";

    if ($conn->query($sql) === TRUE) {
        // Atualização bem-sucedida
        $_SESSION["edit_profile_success"] = "Perfil atualizado com sucesso.";
        header("Location: profile.php");
        exit();
    } else {
        // Atualização falhou
        $_SESSION["edit_profile_error"] = "Erro na atualização do perfil. Por favor, tente novamente.";
        header("Location: profile.php");
        exit();
    }
}

$conn->close();
?>
