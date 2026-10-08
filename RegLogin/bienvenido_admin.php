<?php include "verificador.php";?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bienvenida</title>
</head>
<body>
    <h1>Bienvenido, admin</h1>
    <?php
        echo "Bienvenido " .$_SESSION['nombre']. " / " .$_SESSION['email'];
    ?>
</body>
</html>