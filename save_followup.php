<?php
session_start();
include "connection.php";
include "functions.php";

$folio = $_POST['folio']; // Asume que este campo se envía desde el formulario
$commentsSeg = encryptData($_POST['commentsSeg']);
$instLoc = encryptData($_POST['instLoc']);
$instHrs = encryptData($_POST['instHrs']);

// Verifica si se subieron imágenes
if (!empty($_FILES['images']['name'][0])) {
    // Carpeta para guardar imágenes
    $targetDir = "uploads/installation_images/";
    $uploadedImages = [];

    foreach ($_FILES['images']['name'] as $key => $fileName) {
        $fileTmpName = $_FILES['images']['tmp_name'][$key];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            // Genera un nombre único para la imagen
            $newFileName = uniqid() . "." . $fileExtension;
            $uploadPath = $targetDir . $newFileName;

            // Mueve el archivo a la carpeta de imágenes
            if (move_uploaded_file($fileTmpName, $uploadPath)) {
                // Encripta la ruta de la imagen antes de almacenarla
                $uploadedImages[] = encryptData($uploadPath);
            } else {
                echo json_encode("Error al mover la imagen: $fileName");
            }
        } else {
            echo json_encode("Formato de archivo no permitido: $fileName");
        }
    }

    // Guarda los datos de seguimiento en la tabla note_followup
    $query = "INSERT INTO note_followup (folio, inst_comments, inst_loc, inst_hrs) 
              VALUES ('$folio', '$commentsSeg', '$instLoc', '$instHrs')";

    if (mysqli_query($con, $query)) {
        // Una vez que se inserten los datos de seguimiento, inserta las imágenes en la tabla note_images
        $latestFolio = mysqli_insert_id($con); // Obtén el último folio insertado
        foreach ($uploadedImages as $imagePath) {
            $imageQuery = "INSERT INTO note_images (folio, image_path) 
                           VALUES ('$folio', '$imagePath')";

            if (!mysqli_query($con, $imageQuery)) {
                echo json_encode("Error al guardar la imagen en la base de datos.");
            }
        }
        echo json_encode("Datos e imágenes guardados correctamente.");
    } else {
        echo json_encode("Error al guardar los datos: " . mysqli_error($con));
    }
} else {
    // Si no se subieron imágenes, guarda solo los datos de seguimiento
    $query = "INSERT INTO note_followup (folio, inst_comments, inst_loc, inst_hrs) 
              VALUES ('$folio', '$commentsSeg', '$instLoc', '$instHrs')";

    if (mysqli_query($con, $query)) {
        echo json_encode("Datos guardados correctamente.");
    } else {
        echo json_encode("Error al guardar los datos: " . mysqli_error($con));
    }
}
