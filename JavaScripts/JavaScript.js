$(document).ready(function() {
    // Configurar la tabla usando DataTables
    $('#usuariosTable').DataTable({
        ajax: {
            url: '../Controlador/Controlador.php/usuarios/2',
            dataSrc: function(json) {
                // Envolver el objeto en un array
                return [json];
            }
        },
        columns: [{
                data: 'id'
            },
            {
                data: 'nombre'
            },
            {
                data: 'rut'
            },
            {
                data: 'correo'
            },
            {
                data: 'fecha_nacimiento'
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-MX.json' // Traducción al español
        }
    });
});