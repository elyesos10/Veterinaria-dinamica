<?php
    // * Importar la conexion
    // BASE DE DATOS
    require'../includes/config/database.php';
    $db = conectarDB();

    // * Escribir el Query
    $query = "SELECT correo FROM usuarios";

    // * Consultar la Base de Datos
    $resultado_correos = mysqli_query($db, $query);


    // Ejecucion del codigo despues de que el usuario envia el formulario
    $nombre = '';
    $apellido_pa = '';
    $apellido_ma = '';
    $fecha_nac = '';
    $correo = '';
    $contrasena = '';
    $id_rol = 1;
    
    // Variable para validar si un correo ya esta registrado
    $correo_existente = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = mysqli_real_escape_string( $db, $_POST['nombre'] );
        $apellido_pa = mysqli_real_escape_string( $db, $_POST['apellido_pa'] );
        $apellido_ma = mysqli_real_escape_string( $db, $_POST['apellido_ma'] );
        $fecha_nac = mysqli_real_escape_string( $db, $_POST['fecha_nac'] );
        $correo = mysqli_real_escape_string( $db, $_POST['correo'] );
        $contrasena = mysqli_real_escape_string( $db, $_POST['contrasena'] );


        while( $correos = mysqli_fetch_assoc($resultado_correos) ):
            if( $correos['correo'] === $correo ) {
                $correo_existente = true;
            }
        endwhile;
        
        if( !$correo_existente ) {
            // Insertar en la base de datos

            // Hash de la contraseña
            $passwordHash = password_hash($contrasena, PASSWORD_BCRYPT);

            $query = "INSERT INTO usuarios (nombre, apellido_pa, apellido_ma, fecha_nac, correo, contrasena, id_rol) 
            VALUES ( '${nombre}', '${apellido_pa}', '${apellido_ma}', '${fecha_nac}', '${correo}', '${passwordHash}', '${id_rol}' ); ";

            $resultado = mysqli_query($db, $query);


            if( $resultado ) {
                // Redireccionar al usuario
                header('Location: ./inicio_sesion.php');
            }
        } else {
            
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Regístrate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../src/styles/inicio_sesion.css">

    <script src="../src/js/login-signin.js" defer></script>
</head>
<body>
    
    <div class="container">
        <div class="formulario">
            <h1>Registro</h1>
            <form action="" method="POST">
                <div class="username">
                    <input id="input-nombre" name="nombre" type="text" value="<?php echo $nombre; ?>" required>
                    <label id="label-nombre" for="">Nombre</label>
                </div>
                
                <div class="username">
                    <input id="input-apepa" name="apellido_pa" type="text" value="<?php echo $apellido_pa; ?>" required>
                    <label id="label-apepa" for="">Apellido paterno</label>
                </div>
                
                <div class="username">
                    <input id="input-apema" name="apellido_ma" type="text" value="<?php echo $apellido_ma; ?>" required>
                    <label id="label-apema" for="">Apellido Materno</label>
                </div>

                <div class="username-date">
                    <label for="">Fecha de Nacimiento</label> <br>
                    <input type="date" name="fecha_nac" value="<?php echo $fecha_nac; ?>" required>
                </div>

                <div class="username">
                    <input id="input-email" class="<?php echo $correo_existente ? "input-error" : "" ; ?>" name="correo" type="email" value="<?php echo $correo; ?>" required>
                    <label id="label-email" class="" for="">Correo Electrónico</label>
                </div>

                <div>
                <?php 
                    if($correo_existente) {
                        ?>
                        <p class="label-error">Ese correo ya ha sido registrado... 🙀</p>
                        <?php
                    } 
                ?>
                </div>

                <div class="username">
                    <input id="input-password" name="contrasena" type="password" required>
                    <label id="label-password" for="">Crear Contraseña</label>
                </div>
                <div class="politicas-privacidad">
                    <input type="checkbox" name="terminos" required><a href="#"> Acepta Terminos & Condiciones </a></input>
                </div>
                
                <input type="submit" value="Registrarse"></input>
            </form>
        </div>

    </div>
</body>
</html>