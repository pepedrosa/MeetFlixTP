<?php
$servername = "localhost";
$username = "id21606817_sir"; 
$password = "n77ASe@/E)82MkB9"; 
$database = "id21606817_sir_bd";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}
?>