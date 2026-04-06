<html>

<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .btn-editar {
            background-color: #a8d8ea;
            color: #2c5f6e;
            border: 1px solid #85c1d4;
        }
        .btn-editar:hover {
            background-color: #85c1d4;
            color: #1a3d47;
        }

        .btn-eliminar {
            background-color: #f4a7a7;
            color: #7a2020;
            border: 1px solid #e88080;
        }
        .btn-eliminar:hover {
            background-color: #e88080;
            color: #5a1515;
        }

        .tabla-contenedor {
            max-width: 1100px;
            margin: 0 auto;
        }

        .table td, .table th {
            padding: 6px 10px;
            font-size: 0.9rem;
        }

        .col-acciones {
            width: auto;
            white-space: nowrap;
        }
    </style>
</head>

<body class="p-4">

    <div class="tabla-contenedor">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Lista de Estudiantes</h4>
            <button name="btnInsertar" id="btnInsertar" class="btn btn-success">Insertar</button>
        </div>

        <div id="resultado"></div>

    </div>

    <!-- ===== MODAL INSERTAR / EDITAR ===== -->
    <div class="modal fade" id="modalEstudiante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEstudianteTitulo">Insertar Estudiante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modoEdicion" value="insertar">

                    <div class="form-group mb-2">
                        <label for="txtCedula">Cédula</label>
                        <input type="text" class="form-control" id="txtCedula" name="txtCedula">
                    </div>
                    <div class="form-group mb-2">
                        <label>Nombre</label>
                        <input type="text" class="form-control" id="txtNombre" name="txtNombre">
                    </div>
                    <div class="form-group mb-2">
                        <label>Apellido</label>
                        <input type="text" class="form-control" id="txtApellido" name="txtApellido">
                    </div>
                    <div class="form-group mb-2">
                        <label>Teléfono</label>
                        <input type="text" class="form-control" id="txtTelefono" name="txtTelefono">
                    </div>
                    <div class="form-group mb-2">
                        <label>Dirección</label>
                        <input type="text" class="form-control" id="txtDireccion" name="txtDireccion">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">⚠ Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1">¿Está seguro de que desea eliminar al siguiente estudiante?</p>
                    <div class="alert alert-warning mt-2 mb-0">
                        <strong>Cédula:</strong> <span id="eliminarCedula"></span><br>
                        <strong>Nombre:</strong> <span id="eliminarNombre"></span>
                    </div>
                    <p class="text-muted mt-2 mb-0"><small>Esta acción no se puede deshacer.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" id="txtBuscarCedula" class="form-control" placeholder="Buscar por cédula">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary" id="btnBuscar">
                <i class="bi bi-search"></i> Buscar
            </button>
        </div>
    </div>

    <script>

        const apiURL = '{{ url("/api/estudiantes") }}';

        let cedulaAEliminar = '';

        $(document).ready(function () {
            cargarTabla();
        });

        // ──────────────────────────────────────────
        // CARGAR TABLA
        // ──────────────────────────────────────────
        function cargarTabla() {
            $.ajax({
                url: apiURL,
                type: 'GET',
                dataType: 'json',
                success: function (response) {

                    let tabla = `
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Cédula</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th class="text-center col-acciones">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    response.forEach(est => {
                        tabla += `
                            <tr>
                                <td>${est.cedula}</td>
                                <td>${est.nombre}</td>
                                <td>${est.apellido}</td>
                                <td>${est.telefono}</td>
                                <td>${est.direccion}</td>
                                <td class="text-center col-acciones">
                                    <button class="btn btn-sm btn-editar me-1 btnEditar"
                                        title="Editar"
                                        data-cedula="${est.cedula}"
                                        data-nombre="${est.nombre}"
                                        data-apellido="${est.apellido}"
                                        data-telefono="${est.telefono}"
                                        data-direccion="${est.direccion}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-eliminar btnEliminar"
                                        title="Eliminar"
                                        data-cedula="${est.cedula}"
                                        data-nombre="${est.nombre} ${est.apellido}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    tabla += `</tbody></table>`;
                    $("#resultado").html(tabla);

                    asignarEventosBotones();
                },
                error: function () {
                    $("#resultado").html('<div class="alert alert-info">No hay estudiantes registrados.</div>');
                }
            });
        }

        // ──────────────────────────────────────────
        // EVENTOS BOTONES DE LA TABLA
        // ──────────────────────────────────────────
        function asignarEventosBotones() {

            $(".btnEditar").click(function () {
                const btn = $(this);

                $("#modalEstudianteTitulo").text("Editar Estudiante");
                $("#modoEdicion").val("editar");

                $("#txtCedula").val(btn.data("cedula")).prop("readonly", true);
                $("#txtNombre").val(btn.data("nombre"));
                $("#txtApellido").val(btn.data("apellido"));
                $("#txtTelefono").val(btn.data("telefono"));
                $("#txtDireccion").val(btn.data("direccion"));

                const modal = new bootstrap.Modal(document.getElementById('modalEstudiante'));
                modal.show();
            });

            $(".btnEliminar").click(function () {
                cedulaAEliminar = $(this).data("cedula");
                const nombreCompleto = $(this).data("nombre");

                $("#eliminarCedula").text(cedulaAEliminar);
                $("#eliminarNombre").text(nombreCompleto);

                const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
                modal.show();
            });
        }

        // ──────────────────────────────────────────
        // BOTÓN INSERTAR
        // ──────────────────────────────────────────
        $("#btnInsertar").click(function () {
            $("#modalEstudianteTitulo").text("Insertar Estudiante");
            $("#modoEdicion").val("insertar");

            $("#txtCedula").val("").prop("readonly", false);
            $("#txtNombre").val("");
            $("#txtApellido").val("");
            $("#txtTelefono").val("");
            $("#txtDireccion").val("");

            const modal = new bootstrap.Modal(document.getElementById('modalEstudiante'));
            modal.show();
        });

        // ──────────────────────────────────────────
        // BOTÓN GUARDAR (Insertar o Actualizar)
        // ──────────────────────────────────────────
        $("#btnGuardar").click(function () {
            const modo = $("#modoEdicion").val();

            if (modo === "insertar") {
                $.ajax({
                    url: apiURL,
                    type: 'POST',
                    data: {
                        txtCedula: $('#txtCedula').val(),
                        txtNombre: $('#txtNombre').val(),
                        txtApellido: $('#txtApellido').val(),
                        txtTelefono: $('#txtTelefono').val(),
                        txtDireccion: $('#txtDireccion').val()
                    },
                    dataType: 'json',
                    success: function (response) {
                        bootstrap.Modal.getInstance(document.getElementById('modalEstudiante')).hide();
                        cargarTabla();
                    },
                    error: function () {
                        alert("No se pudo insertar el estudiante.");
                    }
                });

            } else {
    $.ajax({
        url: apiURL + '/' + $('#txtCedula').val(),
        type: 'PUT',
        data: {
            txtCedula: $('#txtCedula').val(),
            txtNombre: $('#txtNombre').val(),
            txtApellido: $('#txtApellido').val(),
            txtTelefono: $('#txtTelefono').val(),
            txtDireccion: $('#txtDireccion').val()
        },
        dataType: 'json',
        success: function (response) {
            bootstrap.Modal.getInstance(document.getElementById('modalEstudiante')).hide();
            cargarTabla();
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            alert("No se pudo actualizar el estudiante.");
        }
    });
}
        });

        // ──────────────────────────────────────────
        // BOTÓN CONFIRMAR ELIMINACIÓN
        // ──────────────────────────────────────────
        $("#btnConfirmarEliminar").click(function () {
            $.ajax({
                url: apiURL + '/' + cedulaAEliminar,
                type: 'DELETE',
                dataType: 'json',
                success: function (response) {
                    bootstrap.Modal.getInstance(document.getElementById('modalEliminar')).hide();
                    cargarTabla();
                },
                error: function () {
                    alert("No se pudo eliminar el estudiante.");
                }
            });
        });

        $("#btnBuscar").click(function () {

            let cedula = $("#txtBuscarCedula").val();

            $.ajax({
                url: apiURL + '?txtCedula=' + cedula,
                type: 'GET',
                dataType: 'json',
                success: function (response) {

                    let tabla = `
                        <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th class="text-center col-acciones">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                    `;

                    response.forEach(est => {
                        tabla += `
                        <tr>
                            <td>${est.cedula}</td>
                            <td>${est.nombre}</td>
                            <td>${est.apellido}</td>
                            <td>${est.telefono}</td>
                            <td>${est.direccion}</td>
                            <td class="text-center col-acciones">
                                <button class="btn btn-sm btn-editar me-1 btnEditar"
                                    data-cedula="${est.cedula}"
                                    data-nombre="${est.nombre}"
                                    data-apellido="${est.apellido}"
                                    data-telefono="${est.telefono}"
                                    data-direccion="${est.direccion}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button class="btn btn-sm btn-eliminar btnEliminar"
                                    data-cedula="${est.cedula}"
                                    data-nombre="${est.nombre} ${est.apellido}">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                        `;
                    });

                    tabla += `</tbody></table>`;

                    $("#resultado").html(tabla);

                    asignarEventosBotones();

                },
                error: function () {
                    alert("No se encontró el estudiante.");
                }
            });

});



    </script>

</body>

</html>