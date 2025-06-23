<?php
session_start();
include("connection.php");
include("functions.php");

$_SESSION;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Cabrera Connect</title>

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
                        <h1 class="text-white">Cabrera Connect</h1>
                        <h2 class="text-white-50">Soluciones inteligentes a tu alcance</h2>
                    </div>
                </div>
            </div>
        </header>

        <nav id="sticky-nav" class="navbar navbar-expand-lg bg-white sticky-top shadow">
            <div class="container">
                <div class="navbar-collapse justify-content-center">
                    <ul class="navbar-nav">
                        <li class="nav-item mx-3">
                            <a class="service-card text-center text-black" href="#about-us">Sobre nosotros</a>
                        </li>
                        <li class="nav-item mx-3">
                            <a class="service-card text-center text-black" href="#services">Servicios</a>
                        </li>
                        <li class="nav-item mx-3">
                            <a class="service-card text-center text-black" href="#articles">Artículos informativos</a>
                        </li>
                        <li class="nav-item mx-3">

                            <?php if (isset($_SESSION['user_id'])): /* echo "<h4>".$_SESSION['user_id']."</h4>" ;*/ ?>
                                <!-- Mostrar botón de "Salir de la cuenta" si el usuario está loggeado -->
                                <a class="service-card text-center text-black" href="logout.php">Salir de la cuenta</a>

                            <?php else: ?>
                                <!-- Mostrar botón de "Accede a tu cuenta" si el usuario no está loggeado -->
                                <a class="service-card text-center text-black" href="log-reg.php">Accede a tu cuenta</a>
                            <?php endif; ?>
                        </li>
                        <li class="nav-item mx-3">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php
                                $user_perm = check_perm($con);
                                if ($user_perm && isset($user_perm['user_perm']) && isset($user_perm['isverified'])):
                                    if ($user_perm['user_perm'] == 1 && $user_perm['isverified'] == 1):
                                ?>
                                        <a class="service-card text-center text-black" href="nota-de-venta.php">Notas de venta</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>

                        </li>
                        <li class="nav-item mx-3">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php
                                $user_perm = check_perm($con);
                                if ($user_perm && isset($user_perm['user_perm']) && isset($user_perm['isverified'])):
                                    if ($user_perm['user_perm'] == 1 && $user_perm['isverified'] == 1):
                                ?>
                                        <a class="service-card text-center text-black" href="consult-note.php">Consultar Notas de venta</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>



        <main class="container mt-5">

            <!-- Sección "Sobre Nosotros" -->
            <section id="about-us">
                <br>
                <br>
                <br>
                <br>
                <div class="container">
                    <h2 class="text-center mb-4">Sobre Nosotros</h2>
                    <div class="container">
                    <div class="service-card text-center mb-4">
                        <h3>¿Quienes somos?</h3>
                        <p>En Cabrera Connect, nos dedicamos a brindar soluciones tecnológicas que combinan seguridad, confort y sostenibilidad. Somos expertos en la instalación de sistemas de videovigilancia,, cercos eléctricos, alarmas, paneles solares y mucho más.Nuestro equipo está comprometido con ofrecer servicios personalizados, utilizando productos de última tecnología para asegurar la protección y comodidad de nuestros clientes. Con nuestro eslogan, "Soluciones inteligentes a tu alcance", reflejamos nuestra filosofía de estar siempre a la vanguardia, proporcionando soluciones accesibles y eficientes para hogares y negocios en Baja California.
                        </p>
                        <img src="resources/bslogo.png" alt="Sistemas de seguridad" class="img-fluid service-img mb-3">
                            
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="service-card text-center">
                                <h3>Visión</h3>
                                <p>Ser reconocidos como la empresa líder en Baja California por ofrecer soluciones tecnológicas de seguridad y confort, mejorando la calidad de vida de nuestros clientes a través de sistemas innovadores y sostenibles. Nos enfocamos en crear entornos más seguros y eficientes, contribuyendo al bienestar de las familias y negocios de la región.
                                </p>
                                <img src="resources/bslogo.png" alt="Sistemas de seguridad" class="img-fluid service-img mb-3">
                            
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-card text-center">
                                <h3>Misión</h3>
                                <p>Proveer soluciones inteligentes en videovigilancia, cercos eléctricos, alarmas y el sentimiento de seguridad, garantizando el confort de nuestros clientes. Nos comprometemos a ofrecer productos de alta calidad y servicios de instalación profesional que cumplan con las necesidades específicas de nuestros clientes, priorizando la confianza y la eficiencia en cada proyecto.</p>
                                <img src="resources/bslogo.png" alt="Sistemas de seguridad" class="img-fluid service-img mb-3">
                            
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sección "Servicios" -->
            <section id="services">
                <br>
                <br>
                <br>
                <br>
                <div class="container">
                    <h2 class="text-center mb-4">Nuestros Servicios</h2>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="service-card text-center">
                                <h3>Sistemas de seguridad</h3>
                                <p>Cámaras, DVR, Alarmas, Access Points, Videoporteros y mucho más!</p>
                                <img src="resources/bslogo.png" alt="Sistemas de seguridad" class="img-fluid service-img mb-3">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-card text-center">
                                <h3>Instalación y mantenimiento de minisplits</h3>
                                <p>Minisplit inverter y no inverter con conexión remota desde el celular!</p>
                                <img src="resources/bslogo.png" alt="Instalación y mantenimiento de minisplits" class="img-fluid service-img mb-3">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-card text-center">
                                <h3>Instalación y mantenimiento de paneles solares</h3>
                                <p>Baterías de Lítio, Módulos solares, Cargadores eléctricos para carro y mucho más!</p>
                                <img src="resources/bslogo.png" alt="Instalación y mantenimiento de paneles solares" class="img-fluid service-img mb-3">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-card text-center">
                                <h3>Rastreo GPS para vehículos</h3>
                                <p>Para todo tipo de vehículos particulares o de uso empresarial!</p>
                                <img src="resources/bslogo.png" alt="Rastreo GPS para vehículos" class="img-fluid service-img mb-3">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="articles">
                <br>
                <br>
                <br>
                <br>
                <div class="container">
                    <h2 class="text-center mb-4">Artículos informativos</h2>
                    <div class="service-card text-center mb-4">
                        <h3>Beneficios de las cámaras de seguridad</h3>
                        <p>En este artículo encontrarás información acerca de las cámaras de seguridad que ofrecemos y los beneficios de obtenerlas con nosotros</p>
                        <img src="resources/bslogo.png" alt="Beneficios de las cámaras de seguridad" class="img-fluid articles-img mb-3">
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="service-card text-center">
                                <h3>¿Cuál es el minisplit adecuado para mí?</h3>
                                <p>En este artículo encontrarás información acerca de los distintos minisplits que ofrecemos y te ayudaremos a encontrar el adecuado para ti/p>
                                <img src="resources/bslogo.png" alt="¿Cuál es el minisplit adecuado para mí?" class="img-fluid articles-img mb-3">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-card text-center">
                                <h3>¿Cómo funciona un panel solar?</h3>
                                <p>En este artículo encontrás información sobre el funcionamiento de un panel solar y te brindaremos ayuda para encontrar el panel correcto para ti</p>
                                <img src="resources/bslogo.png" alt="¿Cómo funciona un panel solar?" class="img-fluid articles-img mb-3">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-card text-center">
                                <h3>¿Qué es una cámara IP?</h3>
                                <p>En este artículo aprenderás lo que es una cámara IP y las diferencias de estas cámaras contra una cámara convencional</p>
                                <img src="resources/bslogo.png" alt="¿Qué es una cámara IP?" class="img-fluid articles-img mb-3">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-card text-center">
                                <h3>¿Cómo funciona el Rastreo GPS?</h3>
                                <p>En este artículo aprenderás cómo funciona el Rastreo GPS y los distintos servicios que ofrecemos de Rastreo GPS</p>
                                <img src="resources/bslogo.png" alt="¿Cómo funciona el rastreo GPS?" class="img-fluid articles-img mb-3">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

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