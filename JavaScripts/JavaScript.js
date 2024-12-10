$(document).ready(function () {
    $('#usuariosTable').DataTable({
        ajax: {
            url: '../Controlador/ControladorTabla.php', 
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'nombre' },
            { data: 'rut' },
            { data: 'correo' },
            { data: 'fecha_nacimiento' },
            {
                data: null,
                render: function (data, type, row) {
                    return `<button class="button is-small is-info btn-editar" data-id="${row.id}" data-nombre="${row.nombre}" data-rut="${row.rut}" data-correo="${row.correo}" data-fecha_nacimiento="${row.fecha_nacimiento}">Editar</button>`;
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `<button class="button is-small is-danger btn-eliminar" data-id="${row.id}">Eliminar</button>`;
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

    $('#usuariosTable').on('click', '.btn-eliminar', function () {
        const id = $(this).data('id');
        if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
            eliminarRegistro(id);
        }
    });

    $('#usuariosTable').on('click', '.btn-editar', function () {
        const data = {
            id: $(this).data('id'),
            nombre: $(this).data('nombre'),
            rut: $(this).data('rut'),
            correo: $(this).data('correo'),
            fecha_nacimiento: $(this).data('fecha_nacimiento')
        };
        abrirModal(data);
    });

    function eliminarRegistro(id) {
        fetch('../Controlador/ControladorTabla.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ accion: 'eliminar', id: id })
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.mensaje) {
                alert(data.mensaje);
                $('#usuariosTable').DataTable().ajax.reload();
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch((error) => console.error('Error:', error));
    }

    const modalIngresar = document.getElementById('ingresarModal');
    const guardarNuevo = document.getElementById('guardarNuevo');
    const cancelarNuevo = document.getElementById('cancelarNuevo');

    function abrirModalIngresar() {
        modalIngresar.classList.add('is-active');
    }

    function cerrarModalIngresar() {
        modalIngresar.classList.remove('is-active');
    }

    cancelarNuevo.addEventListener('click', cerrarModalIngresar);

    guardarNuevo.addEventListener('click', (e) => {
        e.preventDefault();
        const formData = new FormData(document.getElementById('formIngresar'));
        formData.append('accion', 'insertar');
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
                    location.reload();
                }, 3000);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Datos Guardados Correctamente.');
        });
    });

    const modalActualizar = document.getElementById('exampleModal');
    const guardarCambios = document.getElementById('guardarCambios');
    const cancelar = document.getElementById('cancelar');

    function abrirModal(data) {
        document.getElementById('idUsuario').value = data.id;
        document.getElementById('nombre').value = data.nombre;
        document.getElementById('rut').value = data.rut;
        document.getElementById('correo').value = data.correo;
        document.getElementById('fechaNacimiento').value = data.fecha_nacimiento;
        modalActualizar.classList.add('is-active');
    }

    function cerrarModal() {
        modalActualizar.classList.remove('is-active');
    }

    cancelar.addEventListener('click', cerrarModal);

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
                    $('#usuariosTable').DataTable().ajax.reload();
                }, 3000);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Datos Actualizados Correctamente.');
        });
    });

    document.getElementById('openIngresarModal').addEventListener('click', abrirModalIngresar);
});
