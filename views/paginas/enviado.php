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
            <p>¡Respuestas enviadas correctamente!</p>
            <p>Te recordamos que estos datos son confidenciales y solo se usarán con fines estadísticos</p>
            <p>Gracias por tu participación</p>
            <a href="/" class="boton-volver">Inicio</a>

           

        </div>
    </div>


</main>



