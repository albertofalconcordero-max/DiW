<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>

        h1{
            margin-top: 15%;
            margin-left: 41%;
        }

        table{
            margin-left: 40%;
        }

        label{
            display: inline-block;
            width: 100px;
        }

        td.boton{
            text-align: center;
        }

        td.alerta{
            text-align: center;
        }

    </style>
</head>
<body>

    <?php session_start(); ?>
    
    <h1>Login Pagina Web</h1>

    <table border="1" cellpadding="10" cellspacing="0">

            <form method="POST" action="intentoInicioSesion.php" id="login">
                
                <tr>
                    <td colspan="2">
                        <label>Email:</label>
                        <input type="text" name="email" id="email" required>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <label>Contraseña:</label>
                        <input type="password" name="contrasena_cifrada" id="contrasena_cifrada" required>
                    </td>
                </tr>

                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lon" id="lon">

                <?php if (!empty($_SESSION['mensaje'])) { ?>

                <tr>
                    <td colspan="2" class="alerta"><?php echo $_SESSION['mensaje']; ?></td>
                </tr>
                    
                <?php unset($_SESSION['mensaje']); } ?> 

                <tr>
                    <td colspan="2" class="boton">
                        <input type="submit" value="Iniciar Sesion">
                    </td>
                </tr>

            </form>
        
    </table>
</body>

    <script>
        document.getElementById('login').addEventListener('submit',function (e){
            e.preventDefault();
            const form = this;
            navigator.geolocation.getCurrentPosition(
                function(pos){
                    document.getElementById('lat').value = pos.coords.latitude;
                    document.getElementById('lon').value = pos.coords.longitude;
                    form.submit();
                },
                function(){
                    alert('Debes permitir la ubicacion para iniciar sesion');
                }
            )
        })
    </script>

</html>