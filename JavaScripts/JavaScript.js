$(document).ready(function () {
    $('#usuariosTable').DataTable({
        ajax: {
            url: '../Controlador/ControladorTabla.php', // Ruta al nuevo controlador
            dataSrc: 'data' // Ajustar al formato JSON proporcionado
        },
        columns: [
            { data: 'id' },
            { data: 'nombre' },
            { data: 'rut' },
            { data: 'correo' },
            { data: 'fecha_nacimiento' },
            {
                data: null, // Columna para botón "Editar"
                render: function (data, type, row) {
                    return `
                        <button class="button is-small is-info btn-editar" data-id="${row.id}" data-nombre="${row.nombre}" data-rut="${row.rut}" data-correo="${row.correo}" data-fecha_nacimiento="${row.fecha_nacimiento}">
                            Editar
                        </button>
                    `;
                }
            },
            {
                data: null, // Columna para botón "Eliminar"
                render: function (data, type, row) {
                    return `
                        <button class="button is-small is-danger btn-eliminar" data-id="${row.id}">
                            Eliminar
                        </button>
                    `;
                }
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-MX.json'
        },
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'excel',
                text: 'Exportar a Excel',
                className: 'btn btn-success'
            }
        ]
    });

    // Listener para el botón "Eliminar"
    $('#usuariosTable').on('click', '.btn-eliminar', function () {
        const id = $(this).data('id');
        if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
            eliminarRegistro(id);
        }
    });

    // Listener para el botón "Editar"
    $('#usuariosTable').on('click', '.btn-editar', function () {
        const data = {
            id: $(this).data('id'),
            nombre: $(this).data('nombre'),
            rut: $(this).data('rut'),
            correo: $(this).data('correo'),
            fecha_nacimiento: $(this).data('fecha_nacimiento')
        };
        abrirModal(data); // Abre el modal con los datos del registro
    });
});

function eliminarRegistro(id) {
    fetch('../Controlador/ControladorTabla.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ accion: 'eliminar', id: id })
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.mensaje) {
                alert(data.mensaje);
                $('#usuariosTable').DataTable().ajax.reload(); // Recargar los datos
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch((error) => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', () => {
    const modalIngresar = document.getElementById('ingresarModal');
    const guardarNuevo = document.getElementById('guardarNuevo');
    const cancelarNuevo = document.getElementById('cancelarNuevo');

    // Función para abrir el modal
    function abrirModalIngresar() {
        modalIngresar.classList.add('is-active');
    }

    // Función para cerrar el modal
    function cerrarModalIngresar() {
        modalIngresar.classList.remove('is-active');
    }

    // Listener para cerrar el modal
    cancelarNuevo.addEventListener('click', cerrarModalIngresar);

    // Enviar datos al controlador y cerrar el modal
    guardarNuevo.addEventListener('click', (e) => {
        e.preventDefault();

        const formData = new FormData(document.getElementById('formIngresar'));
        formData.append('accion', 'insertar');

        // Cerrar el modal inmediatamente después de presionar "Guardar"
        cerrarModalIngresar();

        fetch('../Controlador/ControladorTabla.php', {
            method: 'POST',
            body: formData,
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.mensaje || data.error) {
                alert(data.mensaje || data.error);
                setTimeout(() => {
                    location.reload(); // Recargar la tabla después de mostrar la notificación
                }, 3000);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Datos Guardados Correctamente.');
        });
    });

    // Botón para abrir el modal
    document.getElementById('openIngresarModal').addEventListener('click', abrirModalIngresar);
});

document.addEventListener('DOMContentLoaded', () => {
    const modalActualizar = document.getElementById('exampleModal');
    const guardarCambios = document.getElementById('guardarCambios');
    const cancelar = document.getElementById('cancelar');

    // Función para abrir el modal y llenar los campos con los datos del usuario
    function abrirModal(data) {
        document.getElementById('idUsuario').value = data.id;
        document.getElementById('nombre').value = data.nombre;
        document.getElementById('rut').value = data.rut;
        document.getElementById('correo').value = data.correo;
        document.getElementById('fechaNacimiento').value = data.fecha_nacimiento;
        modalActualizar.classList.add('is-active');
    }

    // Función para cerrar el modal
    function cerrarModal() {
        modalActualizar.classList.remove('is-active');
    }

    // Listener para cerrar el modal
    cancelar.addEventListener('click', cerrarModal);

    // Enviar datos al controlador y cerrar el modal
    guardarCambios.addEventListener('click', (e) => {
        e.preventDefault();

        const formData = new FormData(document.getElementById('formActualizar'));
        formData.append('accion', 'actualizar');

        cerrarModal();

        fetch('../Controlador/ControladorTabla.php', {
            method: 'POST',
            body: formData,
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.mensaje || data.error) {
                alert(data.mensaje || data.error);
                setTimeout(() => {
                    $('#usuariosTable').DataTable().ajax.reload(); // Recargar la tabla después de mostrar la notificación
                }, 3000);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Datos Actualizados Correctamente.');
        });
    });

    // Listener para el botón "Editar"
    $('#usuariosTable').on('click', '.btn-editar', function () {
        const data = {
            id: $(this).data('id'),
            nombre: $(this).data('nombre'),
            rut: $(this).data('rut'),
            correo: $(this).data('correo'),
            fecha_nacimiento: $(this).data('fecha_nacimiento')
        };
        abrirModal(data); // Abre el modal con los datos del registro
    });

    // Botón para abrir el modal
    document.getElementById('openIngresarModal').addEventListener('click', abrirModal);
});

