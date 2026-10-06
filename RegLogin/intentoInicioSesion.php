<?php

        session_start();

        if ($_SERVER["REQUEST_METHOD"]=="POST") {
            
            $conexion=mysqli_connect("localhost","root","root","inicio");

            if (!$conexion) {
                die("Error de conexion: " .mysqli_connect_error());
            }

            $email=$_POST['email'];
            $contrasena_cifrada=$_POST['contrasena_cifrada'];

            $sql=mysqli_query($conexion,"SELECT contrasena_cifrada, perfil, intentos, usuario_bloqueado,
                TIMESTAMPDIFF(SECOND, NOW(), fecha_bloqueo + INTERVAL 3 MINUTE) AS segundos_restantes FROM SESIONES where email = '$email'");

            function distancia($lat, $lon, $lat2, $lon2){
                $radio_tierra = 6371000;
                $dlat = deg2rad($lat2-$lat);
                $dlon = deg2rad($lon2-$lon);
                $a = sin($dlat / 2) ** 2 + cos(deg2rad($lat)) * cos(deg2rad($lat2)) * sin($dlon / 2) ** 2;
                return $radio_tierra * 2 * atan2(sqrt($a), sqrt(1-$a));
            }

            $latPermitida = 36.866340;
            $lonPermitida = -6.178433;
            $radioMetros = 200;

            $lat = filter_input(INPUT_POST, 'lat', FILTER_VALIDATE_FLOAT);
            $lon = filter_input(INPUT_POST, 'lon', FILTER_VALIDATE_FLOAT);

            if ($lat === false || $lat === null || $lon === false || $lon === null) {
                exit('No se recibio la ubicacion');
            }

            if ($fila=mysqli_fetch_assoc($sql)) {
                
                if ($fila['segundos_restantes'] > 0) {
                    $_SESSION['mensaje'] = "Cuenta bloqueada. Intentalo de nuevo en " .ceil($fila['segundos_restantes']/60). " minutos(s)";
                }
                else if (password_verify($contrasena_cifrada,$fila['contrasena_cifrada'])) {
                    $_SESSION['autenticado']=true;
                    $_SESSION['perfil']=$fila['perfil'];
                    mysqli_query($conexion,"UPDATE sesiones SET intentos = 0, usuario_bloqueado = 0 WHERE email = '$email'");
                    if (distancia($lat, $lon, $latPermitida, $lonPermitida) <= $radioMetros) {
                        
                        if ($fila['perfil']=="admin") {
                            header("Location: bienvenido_admin.php");
                        }
                        else {
                            header("Location: bienvenido_usuario.php");
                        }

                        exit;
                    }else {
                        $_SESSION['mensaje'] = "Acceso denegado: estas fuera de la zona permitida";
                    }
                   
                }
                else {
                    $intentos = $fila['intentos'] + 1;
                    if ($intentos >= 3) {
                        mysqli_query($conexion, "UPDATE sesiones SET intentos = 0, usuario_bloqueado = 1, fecha_bloqueo = NOW() WHERE email = '$email'");

                        $_SESSION['mensaje'] = "Has fallado 3 veces. Cuenta bloqueada durante 3 minutos.";
                    }
                    else {
                        mysqli_query($conexion, "UPDATE sesiones SET intentos = $intentos WHERE email = '$email'");

                        $_SESSION['mensaje'] = "Contraseña incorrecta. Te quedan " .(3-$intentos). " intentos";
                    }
                }

            }
            else {
                $_SESSION['mensaje'] = "Correo no valido";
            }

            mysqli_close($conexion);

            header("Location: login.php");
            exit;

        }

?>