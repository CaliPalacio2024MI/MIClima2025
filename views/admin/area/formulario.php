<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información General</legend>

    <div class="formulario__campo">
        <label for="nombreDepartamento" class="formulario__label">Nombre Departamento:</label>
        <select name="nombreDepartamento" id="nombreDepartamento" class="formulario__select" 
            <?php echo isset($area->id) ? 'disabled' : ''; // Deshabilitar si estamos editando ?>
        >
            <option value="">-- Seleccione --</option>
            <?php foreach ($departamentos as $departamento): ?>
                <option 
                    value="<?php echo s($departamento->nombreDepartamento); ?>"
                    data-clave="<?php echo s($departamento->claveDepto); ?>"
                    <?php echo $area->nombreDepartamento === $departamento->nombreDepartamento ? 'selected' : ''; ?>>
                    <?php echo s($departamento->nombreDepartamento); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="formulario__campo">
        <label for="claveDepartamento" class="formulario__label">Clave Departamento:</label>
        <input class="formulario__input" type="text" id="claveDepto" name="claveDepto" placeholder="Clave Departamento" 
            value="<?php echo s($area->claveDepto); ?>" readonly>
    </div>

    <div class="formulario__campo">
        <label for="propiedad" class="formulario__label">Unidad de Negocio</label>
        <select name="propiedades_id" id="propiedad" class="formulario__select">
            <option selected value="">-- Seleccione --</option>
            <?php foreach($propiedades as $propiedad) { ?> 
                <option 
                    <?php echo $area->propiedades_id === $propiedad->id ? 'selected' : '' ?> 
                    value="<?php echo s($propiedad->id); ?>">
                    <?php echo s($propiedad->nombrePropiedad); ?>
                </option>
            <?php  } ?>
        </select>  
    </div>

    <div class="formulario__campo">
        <label for="cantidad" class="formulario__label">Número de Anfitriones:</label>
        <input class="formulario__input" type="text" id="cantidad" name="cantidad" placeholder="Número de Anfitriones" value="<?php echo s($area->cantidad); ?>">
    </div>
</fieldset>