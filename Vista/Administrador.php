<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz API con Bulma</title>
    <!-- Framework CSS Bulma -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    

    <script src="../JavaScripts/JavaScript.js"></script>
</head>

<body>
    <section class="section">
        <div class="container">
            <h1 class="title has-text-centered">Interfaz de Pruebas para la API</h1>
            <div class="has-text-right">
                <button id="openIngresarModal" class="button is-primary">Nuevo Registro</button>
            </div>
            <h1>ㅤ</h1>
            <div class="table-container">
                <table id="usuariosTable" class="table is-striped is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>RUT</th>
                            <th>Correo</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se llenarán dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div id="exampleModal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Actualizar Datos</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form id="formActualizar">
                    <input type="hidden" id="idUsuario" name="id">
                    <div class="field">
                        <label class="label">Nombre</label>
                        <div class="control">
                            <input class="input" type="text" id="nombre" name="nombre" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">RUT</label>
                        <div class="control">
                            <input class="input" type="text" id="rut" name="rut" placeholder="RUT">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Correo</label>
                        <div class="control">
                            <input class="input" type="email" id="correo" name="correo" placeholder="Correo">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Fecha de Nacimiento</label>
                        <div class="control">
                            <input class="input" type="date" id="fechaNacimiento" name="fecha_nacimiento">
                        </div>
                    </div>
                </form>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success" id="guardarCambios">Guardar cambios</button>
                <button class="button" id="cancelar">Cancelar</button>
            </footer>
        </div>
    </div>

    <!-- Modal para ingresar nuevos datos -->
    <div id="ingresarModal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Ingresar Nuevos Datos</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form id="formIngresar">
                    <div class="field">
                        <label class="label">Nombre</label>
                        <div class="control">
                            <input class="input" type="text" name="nombre" id="nuevoNombre" placeholder="Nombre completo" required>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">RUT</label>
                        <div class="control">
                            <input class="input" type="text" name="rut" id="nuevoRut" placeholder="RUT" required>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Correo</label>
                        <div class="control">
                            <input class="input" type="email" name="correo" id="nuevoCorreo" placeholder="Correo electrónico" required>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Fecha de Nacimiento</label>
                        <div class="control">
                            <input class="input" type="date" name="fecha_nacimiento" id="nuevaFechaNacimiento" required>
                        </div>
                    </div>
                </form>
            </section>
            <footer class="modal-card-foot">
                <button id="guardarNuevo" class="button is-success">Guardar</button>
                <button id="cancelarNuevo" class="button">Cancelar</button>
            </footer>
        </div>
    </div>

    <!-- Notificación -->
    <div id="notificacion" class="notification is-success is-hidden">
        Usuario agregado correctamente.
    </div>

</body>
</html>