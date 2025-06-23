document.getElementById("btnSubmit").addEventListener("click", function() {
    if (valid()) {
        sendObject().then(function() {
            // Después de enviar los datos a la base de datos, redirige a genpdf.php
            window.location.href = "genpdf_seg.php?folio=" + document.getElementById('folio').value;
        });
    }
});

// Nueva función para enviar los datos a la base de datos antes de generar el PDF
function sendObject() {
    return new Promise((resolve, reject) => {
        let note = {
            noteName: document.getElementById('txtSaleTitle').value || 'No disponible',
            cusName: document.getElementById('customerName').value || 'No disponible',
            cusPhone: document.getElementById('customerPhone').value || 'No disponible',
            cusEmail: document.getElementById('customerEmail').value || 'No disponible',
            Date: document.getElementById('purchaseDate').value || 'No disponible',
            table: Array.from(document.querySelectorAll('#productTable tr'), (row, index) => ([
                index + 1,
                row.querySelector('.prodName').value || '',
                row.querySelector('.price').value || 0,
                row.querySelector('.quant').value || 0,
                (row.querySelector('.price').value * row.querySelector('.quant').value).toFixed(2) || 0
            ])),
            subtotal: document.getElementById('subtotal').value || '0.00',
            totaldisc: document.getElementById('totalDiscount').value || '0.00',
            iva: document.getElementById('iva').value || '0.00',
            total: document.getElementById('total').value || '0.00',
            currency: document.getElementById('currency').value || 'MXN',
            comm: document.getElementById('comments').value || 'Sin comentarios adicionales.',
            sellName: document.getElementById('sellerName').value || 'No disponible',
            sellPhone: document.getElementById('sellerPhone').value || 'No disponible',
            sellEmail: document.getElementById('sellerEmail').value || 'No disponible',
            mtd_pay: document.getElementById('paymentMethod').value || 'No disponible',
            garantia: document.getElementById('garantia').value || 'No disponible',
            exRate: document.getElementById('exchangeRate').value || 'No disponible'
        };

        console.log("Se asignaron los valores a note");

        fetch("dbnote.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(note)
        })
        .then(response => response.text())
        .then(data => {
            console.log("Respuesta del servidor:", data);
            resolve(); // Resuelve la promesa si todo va bien
        })
        .catch(error => {
            console.error("Error:", error);
            reject(error); // Rechaza la promesa si hay un error
        });
    });
}
function valid() {
    // Limpiar los mensajes de error
    document.getElementById('productError').textContent = '';
    document.getElementById('customerError').textContent = '';
    document.getElementById('sellerError').textContent = '';
    document.getElementById('paymentError').textContent = '';

    let title = document.getElementById('txtSaleTitle').value.trim();
    let cusName = document.getElementById('customerName').value.trim();
    let cusPhone = document.getElementById('customerPhone').value.trim();
    let cusEmail = document.getElementById('customerEmail').value.trim();
    let date = document.getElementById('purchaseDate').value.trim();
    let table = Array.from(document.querySelectorAll('#productTable tr'), (row, index) => ([index + 1, row.querySelector('.prodName').value, row.querySelector('.price').value, row.querySelector('.quant').value]));
    let iva = document.getElementById('iva').value.trim();
    let sellName = document.getElementById('sellerName').value.trim();
    let sellPhone = document.getElementById('sellerPhone').value.trim();
    let sellEmail = document.getElementById('sellerEmail').value.trim();
    let mtd_pay = document.getElementById('paymentMethod').value.trim();
    let garantia = document.getElementById('garantia').value.trim();
    let exRate = document.getElementById('exchangeRate').value.trim();

    var emailreg = new RegExp(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
    var phonereg = new RegExp(/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im);

    // Validaciones con mensajes en HTML y desplazamiento a los errores
    if (title === '') {
        document.getElementById('productError').textContent = "Introduzca el título de la nota de venta";
        document.getElementById('productError').scrollIntoView({ behavior: 'instant' });
        return false;
    }

    if (!validateTableRows(table)) {
        document.getElementById('productError').textContent = "Debe agregar al menos un producto válido";
        document.getElementById('productError').scrollIntoView({ behavior: 'instant' });
        return false;
    }

    if (cusName === '') {
        document.getElementById('customerError').textContent = "Introduzca el nombre del cliente";
        document.getElementById('customerError').scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    if (!emailreg.test(cusEmail) && !phonereg.test(cusPhone)) {
        document.getElementById('customerError').textContent = "Introduzca al menos un número de teléfono o un correo electrónico válido";
        document.getElementById('customerError').scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    if (date === '') {
        document.getElementById('customerError').textContent = "Introduzca la fecha de compra";
        document.getElementById('customerError').scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    if (sellName === '') {
        document.getElementById('sellerError').textContent = "Introduzca el nombre del vendedor";
        document.getElementById('sellerError').scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    if (!emailreg.test(sellEmail) && !phonereg.test(sellPhone)) {
        document.getElementById('sellerError').textContent = "Introduzca al menos un número de teléfono o un correo electrónico válido";
        document.getElementById('sellerError').scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    if (mtd_pay === 'No fue especificado') {
        document.getElementById('paymentError').textContent = "Seleccione un método de pago válido";
        document.getElementById('paymentError').scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    if (exRate === '' || exRate < 0) {
        document.getElementById('paymentError').textContent = "Introduzca un tipo de cambio válido";
        document.getElementById('paymentError').scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    if (iva === '' || iva < 0 || iva > 100) {
        document.getElementById('paymentError').textContent = "Introduzca el IVA en un rango de 0% a 100%";
        document.getElementById('paymentError').scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    if (garantia === '' || garantia < 0 || garantia > 20) {
        document.getElementById('paymentError').textContent = "Introduzca una garantía de 0 a 20 años";
        document.getElementById('paymentError').scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    return true;
}

function validateTableRows(table) {
    if (table.length == 0) {
        document.getElementById('productError').textContent = "Debe agregar al menos un producto";
        document.getElementById('productError').scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    for (let index = 0; index < table.length; index++) {
        let prodName = table[index][1].trim();
        let price = table[index][2].trim();
        let quant = table[index][3].trim();

        if (!prodName || !price || !quant) {
            document.getElementById('productError').textContent = `Hay un campo vacío en la fila ${index + 1}. Por favor, completa todos los campos.`;
            document.getElementById('productError').scrollIntoView({ behavior: 'smooth' });
            return false;
        }
    }

    return true;
}
