<?php
session_start();
include("connection.php");

if (isset($_GET['token'])) {

    $token = $_GET['token'];
    $query = "SELECT veriftok, isverified FROM users WHERE veriftok='$token' LIMIT 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        if ($row['isverified'] == 0) {
            $clicked_token = $row['veriftok'];
            $update_query = "UPDATE users SET isverified = '1' WHERE veriftok='$clicked_token' LIMIT 1";
            $verifuser = mysqli_query($con, $update_query);

            if ($verifuser) {
                $_SESSION['status'] = '¡La verificación fue completada!';
            } else {
                $_SESSION['status'] = 'La verificación falló, intentalo de nuevo más tarde.';
            }
        } else {
            $_SESSION['status'] = 'El correo ya está verificado. Inicia sesión.';
        }
    } else {
        $_SESSION['status'] = 'La verificación falló, intentalo de nuevo más tarde.';
    }
} else {
    $_SESSION['status'] = 'Acceso no autorizado.';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Baja Solutions</title>

</head>

<body>
    <div>

        <!-- Encabezado con logo y título -->
        <header class="bg-dark text-white p-4">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Logo section -->
                    <div class="col-md-4 text-md-start text-center mb-3 mb-md-0">
                        <img src="resources/bslogobl.png" alt="Logo" class="img-fluid rounded">
                    </div>

                    <!-- Title section -->
                    <div class="col-md-8 text-md-start text-center">
                        <h1 class="text-white">BAJA SOLUTIONS</h1>
                        <h2 class="text-white-50">Soluciones inteligentes a tu alcance</h2>
                    </div>
                </div>
            </div>
        </header>

        <!-- Mensaje de verificación -->
        <section class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="card-title">Verificación de cuenta</h3>
                            <p class="card-text">
                                <?php
                                    echo $_SESSION['status'];
                                    unset($_SESSION['status']);
                                ?>
                            </p>
                            <p>Puedes cerrar esta página.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pie de página -->
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
                <p>&copy; 2024 Baja Solutions. Todos los derechos reservados.</p>
            </div>
        </footer>

    </div>

    <script src="./scripts/logReg.js"></script>
</body>

</html>
