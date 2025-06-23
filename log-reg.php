<?php
session_start();

include("connection.php");
include("functions.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $form_type = $_POST['form_type']; // Identificar si es login o register

    // Registro de usuario
    if ($form_type == 'register') {
        $user_name = $_POST['user_name'];
        $user_name_hash = encryptData($user_name);

        $user_email = $_POST['user_email'];
        $user_email_hash = md5($user_email);

        str_contains($user_email, '@bajasolution.com.mx') ? $user_perm = 1 : $user_perm = 0;
        $user_password = md5($_POST['password']);
        $user_lastname = md5($_POST['user_lastname']);

        $query = "SELECT user_email FROM users WHERE user_email = '$user_email_hash' LIMIT 1";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $_SESSION['status'] = "Ya existe una cuenta con ese correo electronico";
        } else {
            if (!empty($user_email) && !empty($user_password) && !empty($user_name) && !empty($user_lastname)) {

                $user_date = date("y-m-d");
                $user_id = random_num(5);
                $verify_token = md5(rand());
                $query = "INSERT INTO users (user_id, user_name, user_lastname, user_email, password, user_perm, date,veriftok) 
                          VALUES ('$user_id', '$user_name_hash', '$user_lastname', '$user_email_hash', '$user_password','$user_perm','$user_date','$verify_token')";

                $query_run = mysqli_query($con, $query);
                if ($query_run) {
                    sendemail_verify($user_email, $user_name, $verify_token);
                    $_SESSION["status"] = "Revisa tu correo para confirmar el registro.";
                }
            } else {
                $_SESSION['status'] = "Por favor, ¡rellena todos los campos!";
            }
        }
    }
    // Inicio de sesión
    if ($form_type == 'login') {
        $user_email = md5($_POST['user_email']);
        $user_password = md5($_POST['password']);

        if (!empty($user_email) && !empty($user_password)) {

            $query = "SELECT * FROM users WHERE user_email = '$user_email' LIMIT 1";
            $result = mysqli_query($con, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);

                if ($user_data['password'] === $user_password) {
                    $_SESSION['user_id'] = $user_data['user_id'];
                    header('Location: index.php');
                    die;
                } else {
                    $_SESSION['status'] = "Contraseña o correo incorrectos.";
                }
            } else {
                $_SESSION['status'] = "Contraseña o correo incorrectos.";
            }
        } else {
            $_SESSION['status'] = "Por favor, ¡rellena todos los campos!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabrera Connect | Registro/Inicio de sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/transicion.css">
</head>

<body>
    <div>

        <header class="bg-dark text-white p-4">
            <div class="container">
                <div class="row">
                    <!-- Logo section -->
                    <div class="col-md-4 d-flex justify-content-center align-items-center">
                        <img src="resources/bslogobl.png" alt="Logo" class="img-fluid rounded">
                    </div>

                    <!-- Title and slogan section -->
                    <div class="col-md-4 d-flex flex-column justify-content-center align-items-center text-center">
                        <h1 class="text-white mt-3">Cabrera Connect</h1>
                        <h2 class="text-white-50">Soluciones inteligentes a tu alcance</h2>
                    </div>

                    <div class="col-md-4 d-flex justify-content-center align-items-center text-center">
                        <nav class="d-grid">
                            <a href="index.php" class="service-card text-center text-black">Regresar</a>
                        </nav>
                    </div>
                </div>
            </div>
        </header>


        <main class="container d-flex justify-content-center align-items-center" style="min-height: 50vh;">

            <!----------------------------- Form box ----------------------------------->
            <div class="form-box d-flex justify-content-center align-items-center">

                <div class="col-md-6">
                    <!------------------- login form -------------------------->
                    <div class="login-container card p-4 rounded" id="login">
                        <form method="POST" action="log-reg.php">
                            <input type="hidden" name="form_type" value="login"> <!-- Campo oculto -->
                            <div class="top text-center mb-4">
                                <span>¿No tienes una cuenta? <a href="#" onclick="register()">Regístrate</a></span>
                                <h3>Iniciar sesión</h3>
                            </div>
                            <div class="alert text-center">
                                <?php
    
                                    if (isset($_SESSION['status'])) {
                                        echo "<h4>".$_SESSION['status']."</h4>";
                                        unset($_SESSION["status"]);
                                    }
                                ?>
                            </div>
                            <div class="input-box mb-3">
                                <input required name="user_email" type="email" class="input-field form-control" placeholder="Email">
                            </div>
                            <div class="input-box mb-3">
                                <input required name="password" type="password" class="input-field form-control" placeholder="Contraseña">
                            </div>
                            <div class="input-box mb-3">
                                <input id="button" type="submit" class="submit btn btn-primary w-100" value="Iniciar sesión">
                            </div>
                            <div class="two-col d-flex justify-content-between">
                                <div class="one">
                                    <input type="checkbox" id="login-check">
                                    <label for="login-check"> Recuérdame</label>
                                </div>
                                <div class="two">
                                    <label><a href="#">Olvidé mi contraseña</a></label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6 center">
                    <!------------------- registration form -------------------------->
                    <div class="register-container card p-4 " id="register">
                        <form method="POST" action="log-reg.php">
                            <input type="hidden" name="form_type" value="register"> <!-- Campo oculto -->
                            <div class="top text-center mb-4">
                                <span>¿Ya tienes una cuenta? <a href="#" onclick="login()">Iniciar sesión</a></span>
                                <h3>Registrarse</h3>
                            </div>
                            <div class="alert">
                                <?php
    
                                    if (isset($_SESSION['status'])) {
                                        echo "<h4>".$_SESSION['status']."</h4>";
                                        unset($_SESSION["status"]);
                                    }
                                ?>
                            </div>
                            <div class="two-forms d-flex justify-content-between mb-3">
                                <div class="input-box w-48">
                                    <input required name="user_name" type="text" class="input-field form-control" placeholder="Nombres">
                                </div>
                                <div class="input-box w-48">
                                    <input required name="user_lastname" type="text" class="input-field form-control" placeholder="Apellidos">
                                </div>
                            </div>
                            <div class="input-box mb-3">
                                <input required name="user_email" type="email" class="input-field form-control" placeholder="Email">
                            </div>
                            <div class="input-box mb-3">
                                <input required name="password" type="password" class="input-field form-control" placeholder="Contraseña">
                            </div>
                            <div class="input-box mb-3">
                                <input name="register_btn" id="button" type="submit" class="submit btn btn-primary w-100" value="Registrarse" onclick="">
                            </div>
                            <div class="two-col d-flex justify-content-between">
                                <div class="one">
                                    <input type="checkbox" id="register-check">
                                    <label for="register-check">Acepto los <a href="#">términos y condiciones</a></label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </main>

    </div>

    <footer class="bg-dark text-white p-4 mt-5">
        <div class="row g-4">
            <div class="col-md-4">
                <h3>Contáctanos</h3>
                <div class="social-icons d-flex flex-column">
                    <a href="https://wa.me/526644076062"><i class="bi bi-whatsapp"></i> Whatsapp +52 664 407 6062</a>
                    <a href="#"><i class="bi bi-google"></i> direccion@bajasolutions.com.mx</a>
                </div>
            </div>
            <div class="col-md-4">
                <h3>Redes sociales</h3>
                <div class="social-icons d-flex flex-column">
                    <a href="https://www.tiktok.com/@bajasolutions?is_from_webapp=1&sender_device=pc"><i class="bi bi-tiktok"></i> Tiktok </a>
                    <a href="https://www.facebook.com/profile.php?id=61556399522883&mibextid=ZbWKwL"><i class="bi bi-facebook"></i> Facebook </a>
                    <a href="https://www.instagram.com/baja.solutions?igsh=MXJqcm5xOGI0aXRyYQ=="><i class="bi bi-instagram"></i> Instagram </a>
                    <a href="http://www.youtube.com/@BajaSolution"><i class="bi bi-youtube"></i> Youtube </a>
                </div>
            </div>
            <div class="col-md-4">
                <h3>Garantías</h3>
                <p>Todos los artículos e instalaciones cuentan con garantías para ofrecerles la mejor experiencia a nuestros clientes, ¡no dudes en preguntar por la tuya!</p>
            </div>
        </div>
        <div class="text-left mt-4" style="text-align: left;">
            <p>&copy; 2024 Cabrera Connect. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="./scripts/logReg.js"></script>
</body>

</html>