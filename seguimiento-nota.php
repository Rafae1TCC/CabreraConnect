<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "connection.php";
include "functions.php";

$user_data = check_login($con);
$user_perm = check_perm($con);
if ($user_perm['user_perm'] == 0 || $user_perm['isverified'] == 0) {
    header('Location: index.php');
}
$latest_folio = $_GET['folio'];

$query =
    "SELECT 
    sn.folio,
    sn.note_name,
    sn.sell_name,
    sn.sell_phone,
    sn.sell_email,
    sn.date,
    sn.currency,
    sn.mtd_pay,
    sn.iva,
    sn.subtotal,
    sn.disc,
    sn.total,
    sn.com,
    sn.garantia,
    sn.ex_rate,
    nc.client_name,
    nc.client_email,
    nc.client_phone,
    np.prod_name,
    np.quant,
    np.prod_price
FROM 
    sell_notes sn
LEFT JOIN 
    note_client nc ON sn.folio = nc.folio
LEFT JOIN 
    note_prod np ON sn.folio = np.folio
WHERE 
    sn.folio = '$latest_folio';
";

$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    // Inicializamos arrays para los productos
    $productNames = [];
    $productQuantities = [];
    $productPrices = [];

    // Almacenamos los datos generales de la nota
    while ($row = mysqli_fetch_assoc($result)) {
        $noteName = decryptData($row["note_name"]);
        $cusName = decryptData($row["client_name"]);
        $cusPhone = decryptData($row["client_phone"]);
        $cusEmail = decryptData($row["client_email"]);
        $date = decryptData($row["date"]);
        $subtotal = decryptData($row["subtotal"]);
        $totaldisc = decryptData($row["disc"]);
        $iva = decryptData($row["iva"]);
        $total = decryptData($row["total"]);
        $currency = decryptData($row["currency"]);
        $comm = decryptData($row["com"]);
        $sellName = decryptData($row["sell_name"]);
        $sellPhone = decryptData($row["sell_phone"]);
        $sellEmail = decryptData($row["sell_email"]);
        $mtd_pay = decryptData($row['mtd_pay']);
        $garantia = decryptData($row['garantia']);
        $exRate = decryptData($row['ex_rate']);

        // Almacenamos los datos del producto
        $productNames[] = decryptData($row["prod_name"]);
        $productQuantities[] = decryptData($row["quant"]);
        $productPrices[] = decryptData($row["prod_price"]);
    }
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
                <div class="col-md-4 d-flex justify-content-center align-items-center">
                    <img src="resources/bslogobl.png" alt="Logo" class="img-fluid rounded">
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-center align-items-center text-center">
                    <h1 class="text-white mt-3">BAJA SOLUTIONS</h1>
                    <h2 class="text-white-50">Soluciones inteligentes a tu alcance</h2>
                </div>

                <div class="col-md-4 d-flex justify-content-center align-items-center text-center">
                    <nav class="d-grid">
                        <a href="consult-note.php" class="service-card text-center text-black">Regresar</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="container my-5">

        <div class="card mb-5">
            <div class="card-body">
                <h2 class="mb-4">Seguimiento de nota de venta</h2>

                <!-- Sección de datos del producto -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="mb-3">Datos del producto</h3>
                        <div class="mb-3 d-flex align-items-center">
                            <label for="txtSaleTitle" class="form-label">Título de la Cotización:</label>
                            <input type="text" id="txtSaleTitle" class="form-control" readonly value="<?php echo $noteName; ?>" style="margin-left: 10px;">

                            <label for="folio" class="form-label" style="margin-left: 20px;">Folio:</label>
                            <input type="text" id="folio" class="form-control" readonly value="<?php echo $latest_folio; ?>" style="width: 100px; margin-left: 10px;">

                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre del Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody id="productTable">
                                <?php
                                for ($i = 0; $i < count($productNames); $i++) {
                                    echo "<tr>
                                            <td>" . htmlspecialchars($productNames[$i]) . "</td>
                                            <td>" . htmlspecialchars($productQuantities[$i]) . "</td>
                                            <td>" . htmlspecialchars($productPrices[$i]) . "</td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Sección de datos del cliente -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="mb-3">Datos del cliente</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="customerName" class="form-label">Nombre:</label>
                                <input type="text" id="customerName" class="form-control" readonly value="<?php echo htmlspecialchars($cusName); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="customerEmail" class="form-label">Correo electrónico:</label>
                                <input type="text" id="customerEmail" class="form-control" readonly value="<?php echo htmlspecialchars($cusEmail); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="customerPhone" class="form-label">Teléfono:</label>
                                <input type="text" id="customerPhone" class="form-control" readonly value="<?php echo htmlspecialchars($cusPhone); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="purchaseDate" class="form-label">Fecha de compra:</label>
                                <input type="text" id="purchaseDate" class="form-control" readonly value="<?php echo htmlspecialchars($date); ?>">
                            </div>
                            <div class="col-12">
                                <label for="comments" class="form-label">Comentarios adicionales:</label>
                                <input id="comments" class="form-control" readonly value="<?php echo htmlspecialchars($comm); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de datos del vendedor -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="mb-3">Datos del vendedor</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="sellerName" class="form-label">Nombre del Vendedor:</label>
                                <input type="text" id="sellerName" class="form-control" readonly value="<?php echo htmlspecialchars($sellName); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="sellerEmail" class="form-label">Correo electrónico:</label>
                                <input type="text" id="sellerEmail" class="form-control" readonly value="<?php echo htmlspecialchars($sellEmail); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="sellerPhone" class="form-label">Teléfono:</label>
                                <input type="text" id="sellerPhone" class="form-control" readonly value="<?php echo htmlspecialchars($sellPhone); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de pagos -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="mb-3">Datos de pago</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="subtotal" class="form-label">Subtotal:</label>
                                <input type="text" id="subtotal" class="form-control" readonly value="<?php echo htmlspecialchars($subtotal); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="currency" class="form-label">Moneda:</label>
                                <input type="text" id="currency" class="form-control" readonly value="<?php echo htmlspecialchars($currency); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="totalDiscount" class="form-label">Descuentos:</label>
                                <input type="text" id="totalDiscount" class="form-control" readonly value="<?php echo htmlspecialchars($totaldisc); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="mtd_pay" class="form-label">Método de Pago:</label>
                                <input type="text" id="mtd_pay" class="form-control" readonly value="<?php echo htmlspecialchars($mtd_pay); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="iva" class="form-label">IVA:</label>
                                <input type="text" id="iva" class="form-control" readonly value="<?php echo htmlspecialchars($iva); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="exchangeRate" class="form-label">Tipo de Cambio:</label>
                                <input type="number" id="exchangeRate" class="form-control" readonly value="<?php echo htmlspecialchars($exRate); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="total" class="form-label">Total:</label>
                                <input type="text" id="total" class="form-control" readonly value="<?php echo htmlspecialchars($total); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="garantia" class="form-label">Garantía:</label>
                                <input type="number" id="garantia" class="form-control" readonly value="<?php echo htmlspecialchars($garantia); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="mb-3">Seguimiento de instalación</h3>
                        <div class="row">
                            <form id="followupForm" enctype="multipart/form-data" method="POST">
                                <label for="file" class="form-label">Inserte imágenes del proceso de instalación/entrega:</label>
                                <input type="file" multiple id="file" name="images[]" class="form-control" accept="image/*">
                                <span id="fp" class="h4"></span>
                                <div id="imagePreview" class="mt-3 d-flex flex-wrap"></div> <!-- Contenedor para las imágenes -->

                                <div class="col-12">
                                    <label for="commentsSeg" class="form-label">Comentarios adicionales:</label>
                                    <textarea id="commentsSeg" name="commentsSeg" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="instLoc" class="form-label">Dirección de la instalación:</label>
                                    <input type="text" id="instLoc" name="instLoc" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="instHrs" class="form-label">Horas de instalación:</label>
                                    <input type="number" min="1" id="instHrs" name="instHrs" class="form-control">
                                </div>

                                <div class="text-center mt-3">
                                    <button id="submitButton" type="submit" class="btn btn-success">Continuar</button>
                                </div>
                                <input type="hidden" name="folio" value="<?php echo $latest_folio; ?>"> <!-- Campo oculto para el folio -->
                            </form>

                        </div>
                    </div>
                </div>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./scripts/noteFollowUp.js"></script> <!-- Este javascript controla el envío de datos a la base de datos y 
                                                      controla la validación de enviar imágenes y texto -->
</body>

</html>