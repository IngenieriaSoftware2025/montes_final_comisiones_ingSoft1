<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h5 class="text-center mb-2">¡Bienvenido al Sistema de Gestión de Personal - Brigada de Comunicación!</h5>
                    <h4 class="text-center mb-2 text-primary">Registro y Administración de Usuarios</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">
                    <form id="FormUsuarios">
                        <input type="hidden" id="usuario_id" name="usuario_id">

                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2"><i class="bi bi-person-fill me-2"></i>Información Personal</h6>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="usuario_nom1" class="form-label">Primer Nombre *</label>
                                <input type="text" class="form-control" id="usuario_nom1" name="usuario_nom1" placeholder="Ingrese el primer nombre" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="usuario_ape1" class="form-label">Primer Apellido *</label>
                                <input type="text" class="form-control" id="usuario_ape1" name="usuario_ape1" placeholder="Ingrese el primer apellido" required>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="usuario_tel" class="form-label">Teléfono *</label>
                                <input type="text" class="form-control" id="usuario_tel" name="usuario_tel" placeholder="Ingrese número de teléfono (8 dígitos)" maxlength="8" pattern="[0-9]{8}" required>
                                <div class="form-text">Formato: 12345678 (sin +502)</div>
                            </div>
                            <div class="col-lg-6">
                                <label for="usuario_dpi" class="form-label">DPI *</label>
                                <input type="text" class="form-control" id="usuario_dpi" name="usuario_dpi" placeholder="Ingrese DPI (13 dígitos)" maxlength="13" pattern="[0-9]{13}" required>
                                <div class="form-text">Formato: 1234567890123</div>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-12">
                                <label for="usuario_direc" class="form-label">Dirección *</label>
                                <input type="text" class="form-control" id="usuario_direc" name="usuario_direc" placeholder="Ingrese dirección completa" required>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="usuario_email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="usuario_email" name="usuario_email" placeholder="correo@ejemplo.com">
                                <div class="form-text">Opcional - Para notificaciones del sistema</div>
                            </div>
                            <div class="col-lg-6">
                                <label for="usuario_fotografia" class="form-label">Fotografía</label>
                                <input type="file" class="form-control" id="usuario_fotografia" name="usuario_fotografia" accept="image/jpeg,image/jpg,image/png">
                                <div class="form-text">Formatos: JPG, JPEG, PNG. Máximo 2MB</div>
                            </div>
                        </div>

                        
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2"><i class="bi bi-award-fill me-2"></i>Información Militar</h6>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="usuario_grado" class="form-label">Grado Militar</label>
                                <select class="form-select" id="usuario_grado" name="usuario_grado">
                                    <option value="">Seleccione el grado</option>
                                    <option value="Soldado">Soldado</option>
                                    <option value="Cabo">Cabo</option>
                                    <option value="Sargento Segundo">Sargento Segundo</option>
                                    <option value="Sargento Primero">Sargento Primero</option>
                                    <option value="Sargento Mayor">Sargento Mayor</option>
                                    <option value="Subteniente">Subteniente/Alférez de Fragata</option>
                                    <option value="Teniente">Teniente/Alférez de Navío</option>
                                    <option value="Capitán">Capitán Segundo/Teniente de Fragata</option>
                                    <option value="Capitán">Capitán Primero/Teniente de Navio</option>
                                    <option value="Mayor">Mayor/Capitan de Corbeta</option>
                                    <option value="Teniente Coronel">Teniente Coronel/Capitan de Fragata</option>
                                    <option value="Coronel">Coronel/Capitan de Navio</option>
                                    <option value="General de Brigada">General de Brigada</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="usuario_especialidad" class="form-label">Especialidad *</label>
                                <select class="form-select" id="usuario_especialidad" name="usuario_especialidad" required>
                                    <option value="">Seleccione la especialidad</option>
                                    <option value="transmisiones">Transmisiones</option>
                                    <option value="informatica">Informática</option>
                                </select>
                                <div class="form-text">Área de especialización para asignación de comisiones</div>
                            </div>
                        </div>

                        
                        <div class="row mb-4 mt-4" id="seccion-acceso">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2"><i class="bi bi-key-fill me-2"></i>Información de Acceso al Sistema</h6>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center" id="campos-password">
                            <div class="col-lg-6">
                                <label for="usuario_password" class="form-label">Contraseña *</label>
                                <input type="password" class="form-control" id="usuario_password" name="usuario_password" placeholder="Mínimo 8 caracteres" required>
                                <div class="form-text">Debe contener al menos 8 caracteres</div>
                            </div>
                            <div class="col-lg-6">
                                <label for="confirmar_password" class="form-label">Confirmar Contraseña *</label>
                                <input type="password" class="form-control" id="confirmar_password" name="confirmar_password" placeholder="Repita la contraseña" required>
                                <div class="form-text">Debe coincidir con la contraseña anterior</div>
                            </div>
                        </div>

                        
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
                                <button class="btn btn-secondary" type="button" id="BtnLimpiar">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Limpiar Formulario
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-info d-none" type="button" id="BtnCambiarPassword">
                                    <i class="bi bi-shield-lock me-1"></i>Cambiar Contraseña
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row justify-content-center p-3">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <h3 class="text-center mb-3">
                    <i class="bi bi-people-fill me-2"></i>Personal Registrado - Brigada de Comunicación
                </h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableUsuarios">
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/usuarios/index.js') ?>"></script>