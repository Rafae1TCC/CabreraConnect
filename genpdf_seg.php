<?php
session_start();

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
    sn.folio = '$latest_folio'; ";

$result = mysqli_query($con, $query);
$notes = mysqli_fetch_assoc($result);

if ($notes) {
    // Desencriptar los datos
    $noteName = decryptData($notes["note_name"]);
    $cusName = decryptData($notes["client_name"]);
    $cusPhone = decryptData($notes["client_phone"]);
    $cusEmail = decryptData($notes["client_email"]);
    $date = decryptData($notes["date"]);
    $subtotal = decryptData($notes["subtotal"]);
    $totaldisc = decryptData($notes["disc"]);
    $iva = decryptData($notes["iva"]);
    $total = decryptData($notes["total"]);
    $currency = decryptData($notes["currency"]);
    $comm = decryptData($notes["com"]);
    $sellName = decryptData($notes["sell_name"]);
    $sellPhone = decryptData($notes["sell_phone"]);
    $sellEmail = decryptData($notes["sell_email"]);
    $mtd_pay = decryptData($notes['mtd_pay']);
    $garantia = decryptData($notes['garantia']);
    $exRate = decryptData($notes['ex_rate']);
}

// Nueva consulta para recuperar imágenes
$query_images = "SELECT image_path FROM note_images WHERE folio = '$latest_folio'";
$result_images = mysqli_query($con, $query_images);
$images = [];
while ($row = mysqli_fetch_assoc($result_images)) {
    $images[] = $row['image_path'];
}

// Nueva consulta para recuperar datos de seguimiento
$query_followup = "SELECT inst_comments, inst_loc, inst_hrs FROM note_followup WHERE folio = '$latest_folio'";
$result_followup = mysqli_query($con, $query_followup);
$followup = mysqli_fetch_assoc($result_followup);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="./styles/genpdf.css" rel="stylesheet">
    <title>Nota de Venta - Cabrera Connect</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

</head>

<body>
    <!-- Datos del cliente y de la venta -->
    <main class="card" id="pdf-content">
        <header class="d-flex justify-content-between align-items-center">
            <img src="resources/cc.png" alt="Logo" class="img-fluid">
            <div class="header-right">
                <h4>Cabrera Connect</h4>
                <p>Tijuana, Baja California</p>
                <p>Contacto: 664 312 3031</p>
            </div>
        </header>
        <h2 class="section-title">Datos del Cliente y la Venta</h2>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nombre de la Nota:</strong> <?php echo htmlspecialchars($noteName); ?></p>
                <p><strong>Cliente:</strong> <?php echo htmlspecialchars($cusName); ?></p>
                <p><strong>Correo:</strong> <?php echo htmlspecialchars($cusEmail); ?></p>
            </div>
            <div class="col-md-6 text-end">
                <p><strong>Folio:</strong> <?php echo $latest_folio; ?></p>
                <p><strong>Fecha:</strong> <?php echo htmlspecialchars($date); ?></p>
                <p><strong>Garantía:</strong> <?php echo htmlspecialchars($garantia); ?> Mes(es)</p>
            </div>
        </div>
        <div class="row">
            <div>
                <h2 class="section-title">Comentarios Adicionales</h2>
                <p><?php echo htmlspecialchars($comm); ?></p>
            </div>
            <!-- Detalle de Productos -->
            <?php
            $productNames = [];
            $productPrices = [];
            $productQuantities = [];
            $query =
                "SELECT 
                np.prod_name, 
                np.quant, 
                np.prod_price 
                FROM note_prod np 
                WHERE np.folio = '$latest_folio'";
            $result = mysqli_query($con, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $productNames[] = $row['prod_name'];
                $productQuantities[] = $row['quant'];
                $productPrices[] = $row['prod_price'];
            }

            $totalProducts = count($productNames);
            $maxProducts = 20; // Siempre mostrar 20 filas

            echo '<div class="page-break">
                <h2 class="section-title">Detalle de Productos</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio Unitario</th>
                            <th>Unidades</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>';

            // Mostrar los productos existentes
            for ($i = 0; $i < $totalProducts; $i++) {
                $prodTotal = decryptData($productPrices[$i]) * decryptData($productQuantities[$i]);
                echo "<tr>
                    <td>" . htmlspecialchars(decryptData($productNames[$i])) . "</td>
                    <td>" . htmlspecialchars(decryptData($productPrices[$i])) . "</td>
                    <td>" . htmlspecialchars(decryptData($productQuantities[$i])) . "</td>
                    <td>" . htmlspecialchars($prodTotal) . "</td>
                </tr>";
            }

            // Agregar filas vacías si hay menos de 20 productos
            for ($i = $totalProducts; $i < $maxProducts; $i++) {
                echo "<tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>";
            }

            echo '</tbody>
                </table>
            </div>';
            ?>
            <!-- Resumen Financiero -->
            <footer>
                <h2 class="section-title">Resumen Financiero</h2>
                <div class="row">
                    <div class="col-md-6">
                        <?php if ($currency === "USD"): ?>
                            <p><strong>Tipo de Cambio:</strong> <?php echo htmlspecialchars($exRate); ?></p>
                        <?php endif; ?>
                        <p><strong>Moneda:</strong> <?php echo htmlspecialchars($currency); ?></p>

                    </div>
                    <div class="col-md-6 text-end">
                        <p><strong>Subtotal:</strong> <?php echo htmlspecialchars($subtotal); ?> <?php echo htmlspecialchars($currency); ?></p>
                        <p><strong>Descuento:</strong> <?php echo htmlspecialchars($totaldisc); ?> <?php echo htmlspecialchars($currency); ?></p>
                        <p><strong>IVA:</strong> <?php echo htmlspecialchars($iva); ?> %</p>
                        <p><strong>Total:</strong> <?php echo htmlspecialchars($total); ?> <?php echo htmlspecialchars($currency); ?></p>
                    </div>
                </div>
            </footer>


            <!-- <?php

                    // Definir el total de imágenes y el número máximo de imágenes por página
                    $maxImagesPerPage = 9;

                    // Iniciar bucle para crear una sección de imágenes cada 9 imágenes
                    for ($i = 0; $i < count($images); $i += $maxImagesPerPage) {
                        echo '<div class="card page-break">
                    <div class="card-body">
                    <h2 class="section-title">Imágenes del Seguimiento</h2>
                    <div class="image-grid" style="display: flex; flex-wrap: wrap; gap: 10px;">'; // Flexbox para alinear imágenes en la cuadrícula

                        // Mostrar hasta 9 imágenes por página
                        for ($j = $i; $j < min($i + $maxImagesPerPage, count($images)); $j++) {
                            echo '<div class="image-container" style="flex: 1 1 calc(33.33% - 20px); max-width: calc(33.33% - 20px); height: 300px; overflow: hidden;">
                        <img src="' . htmlspecialchars(decryptData($images[$j])) . '" style="width: 100%; height: 100%; object-fit: cover;" alt="Imagen ' . ($j + 1) . '">
                    </div>';
                        }

                        echo '      </div>
                    </div>
                </div>
                <br>';
                    }

                    ?>

            <?php
            if (!empty($followup)) {
            ?>
                <div class="card">
                    <div class="card-body ">
                        <h2 class="section-title">Información de instalación</h2>
                        <p><strong>Comentarios de la instalación:</strong> <?php echo nl2br(htmlspecialchars(decryptData($followup['inst_comments']))); ?></p>
                        <p><strong>Lugar de Instalación:</strong> <?php echo htmlspecialchars(decryptData($followup['inst_loc'])); ?></p>
                        <p><strong>Horas de Instalación:</strong> <?php echo htmlspecialchars(decryptData($followup['inst_hrs'])); ?></p>
                        <p>La cotización es válida durante los primeros 15 días desde su creación.</p>
                    </div>
                </div>
            <?php
            }
            ?> -->


    </main>

    <script>
        var filenamepdf = <?php echo json_encode(htmlspecialchars($noteName) . ' #' . $latest_folio . '-' . $date); ?> + '.pdf';
        window.onload = function() {
            var element = document.getElementById('pdf-content');
            var opt = {
                margin: 1,
                filename: filenamepdf,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    useCORS: true, // Permite el uso de imágenes de diferentes orígenes
                    backgroundColor: null // Mantiene fondo transparente
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(opt).from(element).toPdf().get('pdf').then(function(pdf) {
                var pdfBlob = pdf.output('blob');
                //sendPDFToServer(pdfBlob);
                pdf.save(filenamepdf);
            });
        };

        // Función para enviar el PDF al servidor
        function sendPDFToServer(pdfBlob) {
            var formData = new FormData();
            formData.append('pdf', pdfBlob, filenamepdf);
            formData.append('email_client', <?php echo json_encode(decryptData($notes['client_email'])); ?>);
            formData.append('name_client', <?php echo json_encode(decryptData($notes['client_name'])); ?>);
            formData.append('email_seller', <?php echo json_encode(decryptData($notes['sell_email'])); ?>);
            formData.append('name_seller', <?php echo json_encode(decryptData($notes['sell_name'])); ?>);


            fetch('sendpdf.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    console.log(result); // Manejar la respuesta del servidor
                })
                .catch(error => {
                    console.error('Error al enviar el PDF:', error);
                });
        }
    </script>
</body>

</html>