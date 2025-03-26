
<div class="inicio-admin">
    <h1>RESULTADO GENERAL</h1>
    <h3><?php echo $namePropiedad->nombrePropiedad ?></h3>
</div>

<section class="resumen">
    <div class="resumen__iconos">
        <div class="resumen__iconos-icono">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Capitalizar.webp'; ?>" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Capitalizar.png'; ?>" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/build/img/Capitalizar.png'; ?>" alt="Logo Mi Clima">
            </picture>
            <div class="labels">
                <p>Capitalizar</p>
                <span>+ 90%</span>
            </div>
        </div>
        <div class="resumen__iconos-icono">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Optimizar.webp'; ?>" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Optimizar.png'; ?>" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/build/img/Optimizar.png'; ?>" alt="Logo Mi Clima">
            </picture>
            <div class="labels">
                <p>Optimizar</p>
                <span>85% - 89%</span>
            </div>
        </div>
        <div class="resumen__iconos-icono">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Mejorar.webp'; ?>" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Mejorar.png'; ?>" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/build/img/Mejorar.png'; ?>" alt="Logo Mi Clima">
            </picture>
            <div class="labels">
                <p>Mejorar</p>
                <span>80% - 84%</span>
            </div>
        </div>
        <div class="resumen__iconos-icono">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Corregir.webp'; ?>" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Corregir.png'; ?>" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/build/img/Corregir.png'; ?>" alt="Logo Mi Clima">
            </picture>
            <div class="labels">
                <p>Corregir</p>
                <span>- 80%</span>
            </div>
        </div>
    </div>



    <div class="resumen__grid">
        <?php foreach($totales as $total) { ?>
            <?php if($total->globales) { ?>
                <div class="resumen__cuadro">
                    <div class="resumen__bloque">
                        <p class="resumen__texto resumen__texto--numero"><span><?php echo $total->globales->cantidad . ' |'; ?></span> ANFITRIONES</p>
                        <picture>
                            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/' . $total->globales->icono . '.webp'; ?>" type="image/webp">
                            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/' . $total->globales->icono . '.png'; ?>" type="image/png">
                            <img src="<?php echo $_ENV['HOST'] . '/build/img/' . $total->globales->icono . '.png'; ?>" alt="Icono de <?php echo $total->globales->icono;?>">        
                        </picture>
                        <p class="resumen__texto resumen__texto--<?php echo $total->globales->icono;?>"><?php echo $total->globales->porcentaje . '%' ;?></p>
                    </div>
                    <p class="resumen__texto resumen__texto--periodo"><?php echo $total->periodo; ?></p>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</section>
