<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información General del Administrador de TH</legend>

    <div class="formulario__campo">
        <label class="formulario__label" for="id">RFC:</label>
        <input class="formulario__input" type="text" id="id" name="id" placeholder="RFC Administrador" 
            value="<?php echo htmlspecialchars($administrador->id ?? ''); ?>" 
            <?php echo empty($administrador->id) ? '' : 'disabled'; ?>>
        <?php if (!empty($administrador->id)) { ?>
            <small class="formulario__alerta">Este campo no puede ser modificado.</small>
        <?php } ?>
    </div>

    <div class="formulario__campo">
        <label class="formulario__label" for="nombreAnfitrion">Nombre completo:</label>
        <input class="formulario__input" type="text" id="nombreAnfitrion" name="nombreAnfitrion" placeholder="Nombre Administrador" value="<?php echo s($administrador->nombreAnfitrion); ?>">
    </div>

    <div class="formulario__campo">
        <label class="formulario__label" for="contraseña">Contraseña:</label>
        <input class="formulario__input" type="password" id="contraseña" name="contraseña" placeholder="Contraseña" value="<?php echo s($administrador->contraseña); ?>">
    </div>

    <div class="formulario__campo">
        <label for="propiedades" class="formulario__label">Propiedad:</label>
        <div class="formulario__checkboxes">
            <?php 
            // Asegúrate de que propiedades_id sea un array
            $propiedadesSeleccionadas = is_array($administrador->propiedades_id) ? $administrador->propiedades_id : [];
            
            foreach($propiedades as $propiedad) { 
            ?>
                <div class="formulario__checkbox">
                    <input 
                        type="checkbox" 
                        name="propiedades_id[]" 
                        value="<?php echo s($propiedad->id); ?>" 
                        <?php echo in_array($propiedad->id, $propiedadesSeleccionadas) ? 'checked' : ''; ?>
                        id="propiedad_<?php echo s($propiedad->id); ?>"
                    >
                    <label for="propiedad_<?php echo s($propiedad->id); ?>">
                        <?php echo s($propiedad->nombrePropiedad); ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>
</fieldset>