$(document).ready(function() {
    $('#subirarchivo').on('click', function() {
        const tipoarchivo = document.querySelector('input[name="tipoarchivo"]:checked');
        const textarea = document.getElementById('descripcion');
        const descripcion = textarea.value;
        if(!tipoarchivo){
            alertify.error('Seleccione el tipo de archivo');
            return;
        }
        tipo = tipoarchivo.value;
        $(".carga").waitMe({text: 'Validando datos'});
        var formData = new FormData();
        var fileInput = document.getElementById('archivoec');
        if (fileInput.files.length > 0) {
            if(descripcion == ''){
                $(".carga").waitMe('hide');
                alertify.error('Ingrese una descripción del archivo.');
                return;
            }
            var file = fileInput.files[0];
            formData.append('archivo', file);
            formData.append('tipoarchivo', tipo);
            formData.append('descripcion', descripcion);
            fetch(url + 'subirdata/validar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                $(".carga").waitMe('hide');
                document.getElementById('resultado').innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        } else {
             $(".carga").waitMe('hide');
            alertify.error('Por favor seleccione un archivo.');
        }
    });
});

function confirmarexcel(){
    alertify.confirm('Confirmar','Estas seguro de registrar el archivo, esta acción no se puede cancelar', function() {
        $(".carga").waitMe({text: 'Cargando datos'});
        const tipoarchivo = document.querySelector('input[name="tipoarchivo"]:checked');
        const textarea = document.getElementById('descripcion');
        const descripcion = textarea.value;
        tipo = tipoarchivo.value;
        var formData = new FormData();
        var fileInput = document.getElementById('archivoec');
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0];
            formData.append('archivo', file);
            formData.append('tipoarchivo', tipo);
            formData.append('descripcion', descripcion);
            fetch(url + 'subirdata/guardararchivo', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if(data == 'ok'){
                    $(".carga").waitMe('hide');
                    document.getElementById('resultado').innerHTML = 'El archivo fue subido correctamente';
                    var redirige = url + 'subirdata';
                    window.setTimeout($(location).attr('href',redirige), 3000 );
                }else{
                    $(".carga").waitMe('hide');
                    document.getElementById('resultado').innerHTML = data;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        } else {
             $(".carga").waitMe('hide');
            alertify.error('Por favor seleccione un archivo.');
        }
    }, function() {
        console.log('Cancelado');
    }).set('labels', {
        ok: 'Confirmar',
        cancel: 'Cancelar'
    }).set('buttonReverse', true).set('confirmButtonText', 'Aceptar').set('cancelButtonText', 'Cancelar').set('defaultFocus', 'ok');

    // Modificar el estilo del cuadro de diálogo de confirmación
    $('.ajs-header').css('background-color', '#0000ff ');
    $('.ajs-button.ajs-ok').css('background-color', '#4CAF50');
    $('.ajs-button.ajs-cancel').css('background-color', '#f44336');
    $('.ajs-button').css('color', 'white');
    $('.ajs-dialog').css('width', '320px');
    $('.ajs-header').css('padding', '10px');
    $('.ajs-content').css('padding', '10px');
}