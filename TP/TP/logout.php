<?php
// logout.php

session_start();
session_destroy(); // Destroi a sessão

header("Location: index.php"); // Redireciona para o index.php
exit();
?>
