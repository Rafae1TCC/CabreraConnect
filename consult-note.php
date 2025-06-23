<?php
session_start();

include "connection.php";
include "functions.php";

$user_data = check_login($con);
$user_perm = check_perm($con);
if ($user_perm['user_perm'] == 0 || $user_perm['isverified'] == 0) {
    header('Location: index.php');
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Registro de Ventas - Baja Solutions</title>
</head>

<body>

    <!-- Header -->
    <header class="bg-dark text-white p-4">
        <div class="container">
            <div class="row">
                <!-- Logo section -->
                <div class="col-md-4 d-flex justify-content-center align-items-center">
                    <img src="resources/bslogobl.png" alt="Logo" class="img-fluid rounded">
                </div>

                <!-- Title and slogan section -->
                <div class="col-md-4 d-flex flex-column justify-content-center align-items-center text-center">
                    <h1 class="text-white mt-3">BAJA SOLUTONS</h1>
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

    <!-- Main -->
    <main class="container my-5">

        <!-- Formulario de registro -->
        <div class="card mb-5">
            <div class="card-body">
                <h2 class="mb-4">Buscar nota de venta</h2>

                <div class="card mb-3">
                    <div class="card-body">
                        <span id="productError" class="text-danger h4"></span>

                        <div class="mb-3 d-flex align-items-center">
                            <input  class="form-control" type="text" id="noteName" placeholder="Buscar por nombre de la nota">  <!-- Filtro por nombre de la nota -->
                            <input style="margin-left: 10px" class="form-control" type="text" id="clientName" placeholder="Buscar por cliente">
                            <input style="margin-left: 10px" class="form-control" type="text" id="folio" placeholder="Buscar por folio">
                            <input style="margin-left: 10px" class="form-control" type="text" id="date" placeholder="Buscar por fecha">
    
                            <button id="searchBtn" class="btn btn-primary" style="margin-left: 10px;">Buscar</button>
                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre de la nota de venta</th>
                                    <th>Nombre del cliente</th>
                                    <th>Folio</th>
                                    <th>Fecha (A-M-D)</th>
                                    <th>Seguimiento</th>
                                </tr>
                            </thead>
                            <tbody id="notesTableBody">
                                <!-- Aquí se insertarán las notas de venta -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="./scripts/consultNota.js"></script>

</body>

</html>