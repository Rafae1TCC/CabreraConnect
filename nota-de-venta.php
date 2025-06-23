<?php
session_start();

include "connection.php";
include "functions.php";

$user_data = check_login($con);
$user_perm = check_perm($con);
if ($user_perm['user_perm'] == 0 || $user_perm['isverified'] == 0) {
    header('Location: index.php');
}

// Consulta para obtener el último folio
$query_folio = "SELECT MAX(folio) as latest_folio FROM sell_notes";
$result = mysqli_query($con, $query_folio);
$row = mysqli_fetch_assoc($result);
$latest_folio = $row['latest_folio'] ? $row['latest_folio'] + 1 : 1; // Si no hay folios, empezamos con 1
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Registro de Ventas - Cabrera Connect</title>
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
                        <h1 class="text-white mt-3">Cabrera Connect</h1>
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
                <h2 class="mb-4">Nueva Cotización</h2>

                <!-- Sección de datos del producto -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="mb-3">Datos del producto</h3>
                        <span id="productError" class="text-danger h4"></span>

                        <div class="mb-3 d-flex align-items-center">
                            <label for="txtSaleTitle" class="form-label">Título de la Cotización:</label>
                            <input type="text" id="txtSaleTitle" class="form-control" required style="margin-left: 10px;">

                            <label for="folio" class="form-label" style="margin-left: 20px;">Folio:</label>
                            <input type="text" id="folio" class="form-control" readonly value="<?php echo $latest_folio; ?>" style="width: 100px; margin-left: 10px;">
                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre del Producto</th>
                                    <th>Cantidad</th>
                                    <th>Descuento (%)</th>
                                    <th>Precio</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="productTable">
                                <!-- Aquí se agregarán los productos -->
                            </tbody>
                        </table>
                        <button id="addProductBtn" class="btn btn-primary">Agregar producto</button>
                    </div>
                </div>

                <!-- Sección de datos del cliente -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="mb-3">Datos del cliente</h3>
                        <span id="customerError" class="text-danger h4"></span>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="customerName" class="form-label">Nombre:</label>
                                <input type="text" id="customerName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="customerEmail" class="form-label">Correo electrónico:</label>
                                <input type="email" id="customerEmail" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="customerPhone" class="form-label">Teléfono:</label>
                                <input type="text" id="customerPhone" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="purchaseDate" class="form-label">Fecha de compra:</label>
                                <input type="date" id="purchaseDate" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label for="comments" class="form-label">Comentarios adicionales:</label>
                                <textarea id="comments" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de datos del vendedor -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="mb-3">Datos del vendedor</h3>
                        <span id="sellerError" class="text-danger h4"></span>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="sellerName" class="form-label">Nombre del Vendedor:</label>
                                <input type="text" id="sellerName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="sellerEmail" class="form-label">Correo electrónico:</label>
                                <input type="email" id="sellerEmail" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="sellerPhone" class="form-label">Teléfono:</label>
                                <input type="text" id="sellerPhone" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de pagos -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="mb-3">Datos de pago</h3>
                        <span id="paymentError" class="text-danger h4"></span>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="subtotal" class="form-label">Subtotal:</label>
                                <input type="number" id="subtotal" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="currency" class="form-label">Moneda:</label>
                                <select id="currency" class="form-control" required>
                                    <option value="USD">Dólares</option>
                                    <option value="MXN">Pesos Mexicanos</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="totalDiscount" class="form-label">Descuento Total:</label>
                                <input type="number" id="totalDiscount" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="paymentMethod" class="form-label">Método de Pago:</label>
                                <select id="paymentMethod" class="form-control" required>
                                    <option value="No fue especificado">Selecciona el método de pago</option>
                                    <option value="Tarjeta de Débito">Tarjeta de Débito</option>
                                    <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                                    <option value="Depósito">Depósito</option>
                                    <option value="Efectivo">Efectivo</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="iva" class="form-label">IVA (%):</label>
                                <input type="number" id="iva" class="form-control" value="0" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label for="exchangeRate" class="form-label">Tipo de Cambio:</label>
                                <input type="number" id="exchangeRate" class="form-control" value="18.00" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label for="total" class="form-label">Total:</label>
                                <input type="number" id="total" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="garantia" class="form-label">Garantía (Meses):</label>
                                <input type="number" id="garantia" class="form-control" min="1" max="20">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón de envío -->
                <div class="text-center">
                    <button id="btnSubmit" class="btn btn-success" onclick="">Enviar y Guardar</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.14/jspdf.plugin.autotable.min.js"></script>

    <script src="./scripts/valid.js"></script>
    <script src="./scripts/regSaleNote.js"></script>
</body>

</html>