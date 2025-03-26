<div class="contenedor-select">
	<!-- Select para el estado -->
	<select name="posicion" id="posicion">
	    <option value="" selected>-- Estado Anfitrión --</option>
		<?php foreach($estados as $estado) { ?>
		        <option value="<?php echo $estado->id; ?>"><?php echo $estado->estado; ?></option>
	    <?php } ?>
	</select>

	<!-- Select para elegir la propiedad -->
	<select name="posicion" id="posicion">
	    <option value="" selected>-- Buscar Unidad de Negocio --</option>
		<?php foreach($propiedades as $propiedad) { ?>
		        <option value="<?php echo $propiedad->id; ?>"><?php echo $propiedad->nombrePropiedad; ?></option>
	    <?php } ?>
	</select>

   <!-- Select para elegir el área -->
	<select name="area" id="area">
	    <option value="" selected>-- Buscar Área --</option>
	        <?php foreach($areas as $area) { ?>
		        <option value="<?php echo $area->id; ?>"><?php echo $area->nombreArea; ?></option>
		    <?php } ?>
	</select>

	<!-- Select para elegir la posición -->
	<select name="posicion" id="posicion">
	    <option value="" selected>-- Buscar Posición --</option>
		<?php foreach($posiciones as $posicion) { ?>
		        <option value="<?php echo $posicion->id; ?>"><?php echo $posicion->nombrePosicion; ?></option>
	    <?php } ?>
	</select>

</div>
