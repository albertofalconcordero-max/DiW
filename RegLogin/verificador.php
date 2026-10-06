<?php

    session_start();

    if (empty($_SESSION['autenticado'])) {
        header("Location: login.php");
        exit;
    }

?>