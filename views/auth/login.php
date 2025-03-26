<main class="auth">
  <h1>Iniciar Sesión</h1>

  <div class="dashboard__formulario">
    <?php 
      include_once __DIR__ . '/../templates/alertas.php'; 
    ?>

  <p class="auth__texto">Inicia sesión en MI Clima</p> 

    <form method="POST" class="formulario" action="/login">
      <div class="formulario__campo">
        <label class="formulario__label" for="id">Usuario</label>
        <input class="formulario__input" type="text" name="id" placeholder="Tu Usuario" id="id" >
      </div>

      <div class="formulario__campo">
        <label class="formulario__label" for="contraseña">Contraseña</label>
        <input class="formulario__input" type="password" name="contraseña" placeholder="Tu contraseña" id="contraseña">
      </div>

        <input type="submit" value="Iniciar Sesión" class="formulario__submit">
    </form>
  </div>
</main>