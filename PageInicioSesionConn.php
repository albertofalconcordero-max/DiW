<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conexion Base Datos</title>
</head>
<body>
    
    <?php

        if ($_SERVER["REQUEST_METHOD"]=="POST") {
            
            $conexion=mysqli_connect("localhost","root","root","inicio");

            if (!$conexion) {
                die("Error de conexion: " .mysqli_connect_error());
            }

            $id=$_POST['id'];
            $nombre=$_POST['nombre'];
            $primerApellido=$_POST['Primer_apellido'];
            $segundoApellido=$_POST['Segundo_apellido'];
            $email=$_POST['email'];
            $telefono=$_POST['telefono'];
            $pais=$_POST['pais'];
            $ciudad=$_POST['ciudad'];
            $contrasena_cifrada=password_hash($_POST['contrasena_cifrada'], PASSWORD_DEFAULT);

            $sql="INSERT INTO sesiones(id,nombre,Primer_apellido,Segundo_apellido,email,telefono,pais,ciudad,contrasena_cifrada)
                    VALUES ('$id','$nombre','$primerApellido','$segundoApellido','$email','$telefono','$pais','$ciudad','$contrasena_cifrada')";

            if (mysqli_query($conexion,$sql)) {
                echo "Registrado correctamente";
            }
            else {
                echo "Error al registrarse" .mysqli_error($conexion);
            }

            mysqli_close($conexion);

        }

    ?>

</body>
</html>