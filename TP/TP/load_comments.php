<?php
// load_comments.php

// Inclua a conexão com o banco de dados e outras configurações necessárias
include("db_connection.php");

if (isset($_GET['mediaID'])) {
    $mediaID = $_GET['mediaID'];

    // Consulta SQL para obter os comentários de um filme específico
    $sql = "SELECT * FROM comments WHERE MediaID = $mediaID ORDER BY CreatedAt DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Exibir os comentários
        while ($row = $result->fetch_assoc()) {
            echo '<div class="comment">';
            echo '<p><strong>' . $row['UserID'] . ':</strong> ' . $row['Comment'] . '</p>';
            echo '<small>Em ' . $row['CreatedAt'] . '</small>';
            echo '</div>';
        }
    } else {
        echo '<p>Sem comentários.</p>';
    }
} else {
    echo '<p>Parâmetro mediaID não fornecido.</p>';
}
?>
