$(document).ready(function() {
    $('#subirarchivo').on('click', function() {
        alertify.dismissAll();
        $(".carga").waitMe({text: 'Validando datos'});
        var formData = new FormData();
        var fileInput = document.getElementById('archivo');
        var dominio = document.getElementById('dominio');
        var tipopersona = document.getElementById('tipopersona');
        var generarcon = document.querySelector('input[name="generarcon"]:checked');
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0];
            formData.append('archivo', file);
            formData.append('dominio', dominio.value);
            formData.append('tipopersona', tipopersona.value);
            formData.append('generarcon', generarcon.value);
            fetch(url + 'generardata/preview', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if(data != ''){
                    $(".carga").waitMe('hide');
                    $("#btnprocesar").removeAttr("hidden");
                    document.getElementById('tablapreview').innerHTML = data;
                    $('#tablapreview').DataTable().destroy();
                    tabla("tablapreview");
                }else{
                    alertify.error('Ocurrio un error al mostrar los datos del archivo');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        } else {
             $(".carga").waitMe('hide');
            alertify.error('Por favor seleccione un archivo.');
        }
    });

    $('#btnprocesar').on('click', function() {
        alertify.dismissAll();
        document.getElementById('datosprocesados').innerHTML = '';
        $(".carga").waitMe({text: 'Procesando información'});
        var formData = new FormData();
        var fileInput = document.getElementById('archivo');
        var dominio = document.getElementById('dominio');
        var generarcon = document.querySelector('input[name="generarcon"]:checked');
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0];
            formData.append('archivo', file);
            formData.append('dominio', dominio.value);
            formData.append('generarcon', generarcon.value);
            fetch(url + 'generardata/procesar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if(data != ''){
                    $(".carga").waitMe('hide');
                    document.getElementById('datosprocesados').innerHTML = data;
                    $('#procesados').DataTable().destroy();
                    $("#btnconfirmar").removeAttr("hidden");
                    tabla("procesados");
                }else{
                    alertify.error('Ocurrio un error al procesar la información');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        } else {
             $(".carga").waitMe('hide');
            alertify.error('Por favor seleccione un archivo.');
        }
    });

    $('#btnconfirmar').on('click', function() {
        alertify.dismissAll();
        alertify.confirm('Confirmar','Estas seguro de registrar el archivo, esta acción no se puede cancelar', function() {
            $(".carga").waitMe({text: 'Guardando información'});
            var formData = new FormData();
            var fileInput = document.getElementById('archivo');
            var dominio = document.getElementById('dominio');
            var tipopersona = document.getElementById('tipopersona');
            var tipoarchivo = document.getElementById('tipoarchivo');
            var generarcon = document.querySelector('input[name="generarcon"]:checked');
            if (fileInput.files.length > 0) {
                var file = fileInput.files[0];
                formData.append('archivo', file);
                formData.append('dominio', dominio.value);
                formData.append('tipopersona', tipopersona.value);
                formData.append('tipoarchivo', tipoarchivo.value);
                formData.append('generarcon', generarcon.value);
                fetch(url + 'generardata/guardararchivo', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if(data == 'ok'){
                        $(".carga").waitMe('hide');
                        alertify.success('El archivo fue subido correctamente');;
                        var redirige = url + 'generardata';
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
    });
});