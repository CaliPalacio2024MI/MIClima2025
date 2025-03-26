<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<h1>Resultados Evaluación MI Clima</h1>
<div class="resultados" id="filtro_resultados">
    <div class="resultados__genero">
        <?php if (!empty($conteoGeneros)) { ?>
            <?php foreach ($conteoGeneros as $conteoGenero): ?>
                <p><?php echo htmlspecialchars($conteoGenero['genero']) ?? ''; ?> - <span><?php echo htmlspecialchars($conteoGenero['porcentaje']) ?? ''; ?>%</span></p>
            <?php endforeach; ?>
        <?php } ?>
    </div>
    <div class="resultados__filtros">
        <select id="selectPeriodo" name="periodo">
            <option selected value="">-- Periodo --</option>
            <?php foreach ($periodos as $periodo) { ?> 
                <option value="<?php echo s($periodo->id); ?>" data-propiedad="<?php echo s($periodo->propiedades_id); ?>">
                    <?php echo s($periodo->periodo); ?>
                </option>
            <?php } ?>
        </select>
        <input type="hidden"  name="periodos1" id="per1">

        <select id="selectDepartamento" name="departamento">
        <option selected value="">-- Departamento --</option>
        <?php foreach ($areas as $area) { ?> 
           
            </option>
        <?php } ?>
        <option value="RG">Resultados Generales</option>
    </select>
        <input type="hidden" name="departamentos1" id="dep1">
       
        <input type="hidden"  id="propiedadActual" value="<?php echo $_SESSION['propiedad']; ?>">

        <input type="hidden" id="planAC" name="planAC" readonly>

        <div class="exportacion__excel">
            <a class="dashboard__boton" id="btnExportar">
                <i class="fas fa-file-excel"></i>
                Exportar
            </a>
        </div>
    </div>
</div>

<div class="dashboard__contenedor">
    <?php if (!empty($globales)) { ?>
        <table class="table" id="tabla_resultados">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th" id="primer-encabezado">Pregunta</th>
                    <th scope="col" class="table__th">Resultado Actual</th>
                    <th scope="col" class="table__th">Diferencia</th>
                    <th scope="col" class="table__th">Resultado Anterior</th>
                </tr>
            </thead>
            <tbody class="table__tbody">
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">Aún No Hay Calificaciones</p>
    <?php } ?>
</div>

<!-- Div oculto para usarlo en el JavaScript -->
<div id="preguntas-container" 

></div>

<div id="departamentos-container" 

></div>

<div class="dashboard__contenedor" id="dashboardContenedor" >
    <table class="table">
        <thead class="table__thead">
            <tr>
                <th aling="center">DESCARGA</th>
                <th>SEGUIMIENTO</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody class="table__tbody">
            <tr>
            <th>
    <button class="dashboard__boton" id="btnDescargar">
        <i class="fa-solid fa-file-excel"></i> Plan de Acción
    </button>
</th>
                <th>
                    <form class="boton-contenedor" action="/upload" method="post" enctype="multipart/form-data">
                        <input class="formulario__input formulario__input--file" type="file" id="planAccion" accept="image/jpeg, image/png" name="planAccion">
                        <input type="hidden" name="departamento" id="dep">
                        <input type="hidden" name="periodo" id="per">
                        <input type="hidden" name="periodopropiedad" id="perpro">
                        <button class="dashboard__boton" type="submit" id="submitBtn">
                            <i class="fa fa-upload"></i> Enviar
                        </button>
                    </form>
                </th>
            </tr>
        </tbody>
    </table>
</div>

<div class="dashboard__contenedor" id="dashboardContenedor1">
    <form class="boton_contenedor1" action="/planupload" method="post" enctype="multipart/form-data">
        <input type="hidden"  name="departamento" id="depar">
        <input type="hidden" name="periodo" id="perio">
        <button class="dashboard__boton" type="submit" id="submitBtnPlan">
            <i class="fa-solid fa-eye"></i> Ver Plan de Acción
        </button>
    </form>
</div>

