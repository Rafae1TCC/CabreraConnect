<?php
include 'connection.php';
include 'functions.php'; // Asegúrate de tener la función decryptData disponible

$data = json_decode(file_get_contents('php://input'), true);

// Crear consulta base
$query = "SELECT sn.folio, nc.client_name, sn.date, sn.note_name 
          FROM sell_notes sn 
          JOIN note_client nc ON sn.folio = nc.folio 
          WHERE 1"; // Consulta básica

$result = mysqli_query($con, $query);
$notes = [];

// Desencriptar y filtrar las notas de venta
while ($row = mysqli_fetch_assoc($result)) {
    // Desencriptar los datos
    $decrypted_client_name = decryptData($row['client_name']);
    $decrypted_note_name = decryptData($row['note_name']);
    $decrypted_date = decryptData($row['date']);
    $folio = $row['folio']; // El folio no está encriptado

    // Filtrar según los criterios de búsqueda
    $client_matches = empty($data['client_name']) || stripos($decrypted_client_name, $data['client_name']) !== false;
    $folio_matches = empty($data['folio']) || stripos($folio, $data['folio']) !== false;
    $note_matches = empty($data['note_name']) || stripos($decrypted_note_name, $data['note_name']) !== false;
    $date_matches = empty($data['date']) || stripos($decrypted_date, $data['date']) !== false;

    // Si todas las condiciones de búsqueda se cumplen
    if ($client_matches && $folio_matches && $note_matches && $date_matches) {
        $notes[] = [
            'folio' => $folio,
            'client_name' => $decrypted_client_name,
            'date' => $decrypted_date,
            'note_name' => $decrypted_note_name // Agregar el nombre de la nota desencriptado
        ];
    }
}

// Retornar los datos desencriptados en formato JSON
header('Content-Type: application/json'); // Asegúrate de establecer el encabezado
echo json_encode($notes);
exit; // Termina el script después de enviar la respuesta
