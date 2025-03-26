<main class="contenedor-enviado seccion">
    <div>
        <div class="enviado__imagen" align="center">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.webp'; ?>" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.png'; ?>" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.png'; ?>" alt="Logo Mi Clima">
            </picture>

            <br>
            <br>
            <h3>La encuesta es completamente anonima, los datos son confidenciales y solo se usarán para fines estadísticos.</h3>
            <br>
            <br>

            <div class="enviado__texto">
            <a href="/encuesta" class="boton-volver">Responder encuesta</a>

           
            </div>
        </div>
    </div>


</main>


