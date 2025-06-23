<?php

include 'connection.php';
include 'functions.php';

if (isset($_POST)) {

    $data = file_get_contents("php://input");
    $note = json_decode($data, true);

    $noteName = encryptData($note["noteName"]);      
    $cusName = encryptData($note["cusName"]);      
    $cusPhone = encryptData($note["cusPhone"]);     
    $cusEmail = encryptData($note["cusEmail"]);    
    $date = encryptData($note["Date"]);           
    $prods = $note["table"];
    $subtotal = encryptData($note["subtotal"]);    
    $totaldisc = encryptData($note["totaldisc"]);   
    $iva = encryptData($note["iva"]);            
    $total = encryptData($note["total"]);            
    $currency = encryptData($note["currency"]);    
    $comm = encryptData($note["comm"]);           
    $sellName = encryptData($note["sellName"]);    
    $sellPhone = encryptData($note["sellPhone"]);   
    $sellEmail = encryptData($note["sellEmail"]);    
    $mtd_pay = encryptData($note['mtd_pay']);
    $garantia = encryptData($note['garantia']);       
    $exRate = encryptData($note['exRate']);       
    
    $query_note = "INSERT INTO sell_notes (note_name, sell_name, sell_phone, sell_email, date, currency, mtd_pay, iva, subtotal, disc, total, com, garantia, ex_rate)
                VALUES ('$noteName','$sellName','$sellPhone','$sellEmail','$date', '$currency', '$mtd_pay', '$iva', '$subtotal', '$totaldisc', '$total', '$comm', '$garantia', '$exRate')";

    if (mysqli_query($con, $query_note)) {
        // Obtener el último ID autoincremental (folio) insertado en sell_notes
        $folio = mysqli_insert_id($con);

        // Ahora usa ese folio para insertar en la tabla note_client
        $query_client = "INSERT INTO note_client (folio, client_name, client_email, client_phone) 
                    VALUES ('$folio', '$cusName', '$cusEmail', '$cusPhone')";
        mysqli_query($con, $query_client);

        // Inserta productos usando el folio recién generado
        foreach ($prods as $product) {
            $prodName = encryptData($product[1]);   // Nombre del producto en el índice 1
            $quant = encryptData($product[3]);      // Cantidad en el índice 3
            $price = encryptData($product[2]);      // Precio en el índice 2

            $query_prod = "INSERT INTO note_prod (folio, prod_name, quant, prod_price) 
                       VALUES ('$folio', '$prodName', '$quant', '$price')";
            mysqli_query($con, $query_prod);
        }
    } else {
        echo "Error: " . mysqli_error($con);
    }
}