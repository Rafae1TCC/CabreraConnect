function valid() {
    var fi = document.getElementById('file');

    // VALIDAR SI SE HAN SELECCIONADO MENOS DE 4 IMÁGENES
    if (fi.files.length < 4) {
        document.getElementById('fp').innerHTML = 'Por favor ingrese al menos 4 imágenes.';
        return false;
    }

    if (trim(document.getElementById('commentsSeg').value) === '') {
        document.getElementById('commentsSeg').value = 'Sin comentarios adicionales...';
    }
    
    if (trim(document.getElementById('instLoc').value) === '') {
        document.getElementById('fp').innerHTML = 'Inserte una dirección válida.';
        return false;
    }
    if (trim(document.getElementById('commentsSeg').value) === '') {
        document.getElementById('fp').innerHTML = 'Inserte una dirección válida.';
        return false;
    }

    return true;
}

document.getElementById('file').addEventListener('change', function(event) {
    const imagePreview = document.getElementById('imagePreview');
    imagePreview.innerHTML = '';  // Limpia las imágenes previas
    const files = event.target.files;
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        if (file.type.startsWith('image/')) {  // Verifica que sea un archivo de imagen
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = file.name;
                img.style.width = '150px';  // Ajusta el tamaño de las imágenes
                img.style.height = '150px';
                img.style.objectFit = 'cover';  // Opcional para ajustar cómo se muestra la imagen
                img.classList.add('me-2', 'mb-2', 'rounded');  // Estilos de Bootstrap para espaciado y bordes redondeados
                imagePreview.appendChild(img);
            };
            
            reader.readAsDataURL(file);
        }
    }
});

document.getElementById('followupForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    fetch('save_followup.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json(),
    window.location.href = 'genpdf_seg.php?folio=' + document.getElementById('folio').value)
});
