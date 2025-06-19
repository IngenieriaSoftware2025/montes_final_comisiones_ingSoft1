
        <div class="row justify-content-center">
          <form class="col-lg-4 border rounded shadow p-4 bg-light" id="FormLogin" >
            <h3 class="text-center mb-4"><b>INICIO DE SESION</b></h3>
            <div class="text-center mb-4">
              <img src="<?= asset('./images/login.jpg') ?>" alt="Logo" width="200px" class="img-fluid rounded-circle">
            </div>


            <h4>user 2511729290101</h4>
    <h4>pass 12345678</h4>
            <div class="row mb-3">
              <div class="col">
                <label for="usuario_dpi" class="form-label">Ingrese su DPI</label>
                <input type="number" name="usuario_dpi" id="usuario_dpi" class="form-control" placeholder="Ingresa tu DPI">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col">
                <label for="usuario_contra" class="form-label">Contraseña</label>
                <input type="password" name="usuario_contra" id="usuario_contra" class="form-control" placeholder="Ingresa tu contraseña">
              </div>
            </div>
            <div class="row">
              <div class="col">
                <button type="submit" class="btn btn-primary w-100 btn-lg" id="BtnIniciar" >
                  Iniciar sesión
                </button>
              </div>
            </div>
          </form>
        </div>

        <script src="<?= asset('build/js/login/login.js') ?>"></script>