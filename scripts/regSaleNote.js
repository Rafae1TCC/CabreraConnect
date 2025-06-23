
let sales = [];
let productId = 0;
let products = [];

document.getElementById('addProductBtn').addEventListener('click', addProduct);

function addProduct() {
    const table = document.getElementById('productTable');
    const row = table.insertRow();

    row.innerHTML = `
        <td><input id="prodName" type="text" class="form-control prodName" placeholder="Producto"></td>
        <td><input id="quant" type="number" class="form-control quant" placeholder="Cantidad" min="1"></td>
        <td><input id="disc" type="number" class="form-control disc" placeholder="Descuento (%)" min="0" max="100"></td>
        <td><input id="price" type="number" class="form-control price" placeholder="Precio" min="0"></td>
        <td><button class="btn btn-danger" onclick="removeProduct(this)">Eliminar</button></td>
    `;

    row.querySelector('.quant').addEventListener('input', calcularTotales);
    row.querySelector('.disc').addEventListener('input', calcularTotales);
    row.querySelector('.price').addEventListener('input', calcularTotales);
}

function removeProduct(button) {
    const row = button.parentElement.parentElement;
    row.remove();
    calcularTotales(); // Recalcular totales al eliminar un producto
}

function calcularTotales() {
    let subtotal = 0;
    let totalDiscount = 0;
    let total = 0;

    const rows = document.querySelectorAll('#productTable tr');
    const exchangeRate = parseFloat(document.getElementById('exchangeRate').value) || 1;
    const iva = parseFloat(document.getElementById('iva').value) || 0;
    const currency = document.getElementById('currency').value;

    rows.forEach(row => {
        const quant = parseFloat(row.querySelector('.quant').value) || 0;
        const discount = parseFloat(row.querySelector('.disc').value) || 0;
        const precio = parseFloat(row.querySelector('.price').value) || 0;

        const prodPrice = quant * precio;
        const prodDisc = prodPrice * (discount / 100);
        const precioConDescuento = prodPrice - prodDisc;

        subtotal += prodPrice;
        totalDiscount += prodDisc;
        total += precioConDescuento;
    });

    // Calcular IVA
    total += total * (iva / 100);

    // Si la moneda es USD, convertir todos los valores usando el tipo de cambio
    if (currency == "USD") {
        subtotal = subtotal / exchangeRate;
        totalDiscount = totalDiscount / exchangeRate;
        total = total / exchangeRate;
    }

    // Actualizar los valores en la interfaz
    document.getElementById('totalDiscount').value = totalDiscount.toFixed(2);
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);
}

document.getElementById('iva').addEventListener('input', calcularTotales);
document.getElementById('currency').addEventListener('change', calcularTotales);
document.getElementById('exchangeRate').addEventListener('input', calcularTotales);
