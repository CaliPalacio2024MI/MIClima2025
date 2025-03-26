<main class="contenedor seccion">
    
    <!-- Sección de imagen y formulario -->
    <div class="grid-contenedor">
        <picture>
            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.webp'; ?>" type="image/webp">
            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.png'; ?>" type="image/png">
            <img src="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.png'; ?>" alt="Logo Mi Clima">
        </picture>

        <section class="contenedor-encuesta">
            <form class="" method="POST" action="/encuesta" id="formulario-encuesta">
                <h3>Datos demográficos</h3>
                <p>Estimado anfitrión, le agradecemos su interés en participar en la evaluación MI Clima.</p>

                <div class="encuesta__formulario" id="filtroEncuesta">
                    <div class="encuesta__campo">
                        <label for="propiedad" class="encuesta__label">Propiedad</label>
                        <select name="periodos_id" id="propiedad" class="encuesta__select" required>
                            <option selected value="">-- Seleccione --</option>
                            <?php foreach($propiedades as $propiedad) { ?> 
                                <option <?php echo $resultado->periodos_id === $propiedad->id ? 'selected' : '' ?> value="<?php echo s($propiedad->id); ?>">
                                    <?php echo s($propiedad->nombrePropiedad); ?>
                                </option>
                            <?php  } ?>
                        </select>  
                    </div>

                    <div class="encuesta__campo">
                        <label for="departamento" class="encuesta__label">Departamento</label>
                        <select name="departamentos_id" id="departamento" class="encuesta__select" required>
                            <option selected value="">-- Seleccione --</option>
                            <!-- Las opciones se llenarán con JavaScript -->
                        </select>  
                    </div>

                    <div class="encuesta__campo">
                        <label for="genero" class="encuesta__label">Género</label>
                        <select name="genero" id="genero" class="encuesta__select" required>
                            <option selected value="">-- Seleccione --</option>
                            <?php foreach($generos as $genero) { ?> 
                                <option 
                                <?php echo $resultado->genero === $genero->genero ? 'selected' : '' ?> 
                                value="<?php echo s($genero->genero); ?>">
                                <?php echo s($genero->genero); ?>
                            <?php  } ?>
                        </select>  
                    </div>

                    <div class="encuesta__campo">
                        <label for="edad" class="encuesta__label">Edad</label>
                        <select name="edad" id="edad" class="encuesta__select" required>
                            <option selected value="">-- Seleccione --</option>
                            <?php foreach($edades as $edad) { ?> 
                                <option 
                                <?php echo $resultado->edad === $edad->edad ? 'selected' : '' ?> 
                                value="<?php echo s($edad->edad); ?>">
                                <?php echo s($edad->edad); ?>
                            <?php  } ?>
                        </select>  
                    </div>

                    <div class="encuesta__campo">
                        <label for="tipoPuesto" class="encuesta__label">Tipo de Puesto</label>
                        <select name="tipoPuesto" id="tipoPuesto" class="encuesta__select" required>
                            <option selected value="">-- Seleccione --</option>
                            <?php foreach($tipoPuestos as $tipoPuesto) { ?> 
                                <option 
                                <?php echo $resultado->tipoPuesto === $tipoPuesto->tipoPuesto ? 'selected' : '' ?> 
                                value="<?php echo s($tipoPuesto->tipoPuesto); ?>">
                                <?php echo s($tipoPuesto->tipoPuesto); ?>
                            <?php  } ?>
                        </select>  
                    </div>

                    <div class="encuesta__campo">
                        <label for="antiguedad" class="encuesta__label">Antigüedad</label>
                        <select name="antiguedad" id="antiguedad" class="encuesta__select" required>
                            <option selected value="">-- Seleccione --</option>
                            <?php foreach($antiguedades as $antiguedad) { ?> 
                                <option 
                                <?php echo $resultado->antiguedad === $antiguedad->antiguedad ? 'selected' : '' ?> 
                                value="<?php echo s($antiguedad->antiguedad); ?>">
                                <?php echo s($antiguedad->antiguedad); ?>
                            <?php  } ?>
                        </select>  
                    </div>
                    
                    

                    <input type="hidden" id="periodo" class="encuesta__input" readonly>  
 

                    <input type="hidden" id="propiedades" class="encuesta__input" readonly>  

                    
                    </div> <!--.encuesta__formulario -->    
    </div> <!--.grid-contenedor --> 
        </section>    
  
                
                   
                <div class="encuesta__preguntas" id="cuestionario"></div>
                
                

                <div class="encuesta__preguntas">
                    <input type="hidden" name="p1" id="p1" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p2" id="p2" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p3" id="p3" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p4" id="p4" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p5" id="p5" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p6" id="p6" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p7" id="p7" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p8" id="p8" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p9" id="p9" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p10" id="p10" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p11" id="p11" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p12" id="p12" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p13" id="p13" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p14" id="p14" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p15" id="p15" class="oculto">
                </div>
                <div class="encuesta__preguntas">
                    <input type="hidden" name="p16" id="p16" class="oculto">
                </div>
    
    <div class="enviar">
                    <input type="submit" value="Enviar" class="encuesta__submit">
                </div>

            </form>     
</main>         