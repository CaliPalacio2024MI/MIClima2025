<main class="contenedor-enviado seccion">
    <div class="enviado">
        <div class="enviado__imagen">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.webp'; ?>" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.png'; ?>" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.png'; ?>" alt="Logo Mi Clima">
            </picture>
        </div>

        <div class="enviado__texto">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/comenta-alt-check.webp'; ?>" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/comenta-alt-check.png'; ?>" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/build/img/comenta-alt-check.png'; ?>" alt="Logo Mi Clima">
            </picture>
            <p>¡Plan de Acción añadido correctamente!</p>
            <a href="/admin/resultados" class="boton-volver">Volver</a>

           

        </div>
    </div>


</main>
