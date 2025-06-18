<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h5 class="text-center mb-2">¡Bienvenido al Sistema de Gestión de Personal - Brigada de Comunicación!</h5>
                    <h4 class="text-center mb-2 text-primary">Registro y Administración de Usuarios</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">
                    <form id="formUsuario">
                        <input type="hidden" id="usuario_id" name="usuario_id">

                        <!-- Información Personal -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2"><i class="bi bi-person-fill me-2"></i>Información Personal</h6>
                            </div>
                        </div>

                        <!-- Nombres -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="usuario_nom1" class="form-label">Primer Nombre *</label>
                                <input type="text" class="form-control" id="usuario_nom1" name="usuario_nom1" placeholder="Ingrese el primer nombre" maxlength="50" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="usuario_nom2" class="form-label">Segundo Nombre *</label>
                                <input type="text" class="form-control" id="usuario_nom2" name="usuario_nom2" placeholder="Ingrese el segundo nombre" maxlength="50" required>
                            </div>
                        </div>

                        <!-- Apellidos -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="usuario_ape1" class="form-label">Primer Apellido *</label>
                                <input type="text" class="form-control" id="usuario_ape1" name="usuario_ape1" placeholder="Ingrese el primer apellido" maxlength="50" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="usuario_ape2" class="form-label">Segundo Apellido *</label>
                                <input type="text" class="form-control" id="usuario_ape2" name="usuario_ape2" placeholder="Ingrese el segundo apellido" maxlength="50" required>
                            </div>
                        </div>

                        <!-- DPI y Teléfono -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="usuario_dpi" class="form-label">DPI *</label>
                                <input type="text" class="form-control" id="usuario_dpi" name="usuario_dpi" placeholder="Ingrese DPI (13 dígitos)" maxlength="13" pattern="[0-9]{13}" required>
                                <div class="form-text">Formato: 1234567890123</div>
                            </div>
                            <div class="col-lg-6">
                                <label for="usuario_tel" class="form-label">Teléfono *</label>
                                <input type="number" class="form-control" id="usuario_tel" name="usuario_tel" placeholder="Ingrese número de teléfono" min="10000000" max="99999999" required>
                                <div class="form-text">Solo números, ejemplo: 12345678</div>
                            </div>
                        </div>

                        <!-- Correo y Dirección -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="usuario_correo" class="form-label">Correo Electrónico *</label>
                                <input type="email" class="form-control" id="usuario_correo" name="usuario_correo" placeholder="correo@ejemplo.com" maxlength="100" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="usuario_direc" class="form-label">Dirección *</label>
                                <input type="text" class="form-control" id="usuario_direc" name="usuario_direc" placeholder="Ingrese dirección completa" maxlength="150" required>
                            </div>
                        </div>

                        <!-- Fotografía -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="usuario_fotografia" class="form-label">Fotografía (Opcional)</label>
                                <input type="file" class="form-control" id="usuario_fotografia" name="usuario_fotografia" accept="image/jpeg,image/jpg,image/png,image/gif">
                                <div class="form-text">Formatos: JPG, JPEG, PNG, GIF. Máximo 2MB</div>
                            </div>
                            <div class="col-lg-6">
                                <label for="usuario_fecha_contra" class="form-label">Fecha de Contratación</label>
                                <input type="date" class="form-control" id="usuario_fecha_contra" name="usuario_fecha_contra">
                                <div class="form-text">Se asignará automáticamente si no se especifica</div>
                            </div>
                        </div>

                        <!-- Información de Acceso al Sistema -->
                        <div class="row mb-4 mt-4" id="seccion-acceso">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2"><i class="bi bi-key-fill me-2"></i>Información de Acceso al Sistema</h6>
                            </div>
                        </div>

                        <!-- Contraseñas -->
                        <div class="row mb-3 justify-content-center" id="campos-password">
                            <div class="col-lg-6">
                                <label for="usuario_contra" class="form-label">Contraseña *</label>
                                <input type="password" class="form-control" id="usuario_contra" name="usuario_contra" placeholder="Mínimo 8 caracteres" minlength="8" required>
                                <div class="form-text">Debe contener al menos 8 caracteres</div>
                            </div>
                            <div class="col-lg-6">
                                <label for="confirmar_contra" class="form-label">Confirmar Contraseña *</label>
                                <input type="password" class="form-control" id="confirmar_contra" name="confirmar_contra" placeholder="Repita la contraseña" minlength="8" required>
                                <div class="form-text">Debe coincidir con la contraseña anterior</div>
                            </div>
                        </div>

                        <!-- Campos ocultos -->
                        <input type="hidden" id="usuario_token" name="usuario_token">
                        <input type="hidden" id="usuario_fecha_creacion" name="usuario_fecha_creacion">

                        <!-- Estado del Usuario -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="usuario_situacion" class="form-label">Estado del Usuario</label>
                                <select class="form-select" id="usuario_situacion" name="usuario_situacion">
                                    <option value="1" selected>Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                                <div class="form-text">Solo usuarios activos pueden ser asignados a comisiones</div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row justify-content-center mt-5">
                            <div class="col-auto">
                                <button class="btn btn-success" type="submit" id="BtnGuardar">
                                    <i class="bi bi-floppy me-1"></i>Guardar Usuario
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-warning d-none" type="button" id="BtnModificar">
                                    <i class="bi bi-pencil me-1"></i>Modificar Usuario
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-info" type="button" id="BtnBuscar">
                                    <i class="bi bi-search me-1"></i>Buscar Usuarios
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-secondary" type="button" id="BtnLimpiar">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Limpiar Formulario
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección de la Tabla (Oculta inicialmente) -->
<div class="row d-none" id="seccionTabla">
    <div class="col-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-people me-2"></i>Personal Registrado - Brigada de Comunicación</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="TableUsuarios" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Foto</th>
                                <th>Primer Nombre</th>
                                <th>Segundo Nombre</th>
                                <th>Primer Apellido</th>
                                <th>Segundo Apellido</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>DPI</th>
                                <th>Dirección</th>
                                <th>Fecha Contratación</th>
                                <th>Situación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTable llenará automáticamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/registro/index.js') ?>"></script>