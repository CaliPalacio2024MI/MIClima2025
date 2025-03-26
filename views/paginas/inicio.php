<main class="contenedor">

    <!-- Encabezado de bienvenida -->
    <section class="dashboard__header-encabezado">
        <h1>¡Bienvenido, <?php echo $_SESSION['nombre'] ?? 'Administrador'; ?>!</h1>
        <picture>
            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/MI.webp'; ?>" type="image/webp">
            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/MI.png'; ?>" type="image/png">
            <img src="<?php echo $_ENV['HOST'] . '/build/img/MI.png'; ?>" alt="MI">
        </picture>
    </section>

    <!-- Sección principal de la página -->
    <section class="enviado__imagen" align="center">
        <picture>

        <br>
<br>

            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.webp'; ?>" type="image/webp">
            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.png'; ?>" type="image/png">
            <img src="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.png'; ?>" alt="Logo Mi Clima">
        </picture>
    </section>

    <!-- Generar botones para cada propiedad -->
    <section class="propiedades-botones">
        <?php if (!empty($botones)) : ?>
            <?php foreach ($botones as $boton) : ?>
                <a href="/propiedad/<?php echo $boton['id']; ?>" class="boton">
                    <?php echo $boton['nombre']; ?>
                </a>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No tienes propiedades asignadas.</p>
        <?php endif; ?>
    </section>

</main>
