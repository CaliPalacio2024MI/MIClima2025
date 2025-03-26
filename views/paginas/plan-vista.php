<main class="contenedor-enviado seccion">
    <div class="plan-accion-container">
        <h2>Plan de Acción</h2>
        <?php if (isset($rutaImagen) && $rutaImagen) : ?>
            <p>Ruta de la imagen: /uploads/<?php echo htmlspecialchars($rutaImagen); ?></p>
            <img src="/uploads/<?php echo htmlspecialchars($rutaImagen); ?>" alt="Plan de Acción" class="plan-accion-imagen">
        <?php else : ?>
            <p>No se ha encontrado una imagen para mostrar.</p>
        <?php endif; ?>
    </div>            
    <div>
        <a href="/admin/resultados" class="boton-volver">Volver</a>
        <a href="/uploads/<?php echo htmlspecialchars($rutaImagen); ?>" download="Plan_de_Accion.jpg" class="btn">Descargar</a>
    </div> 
</main>

<style>
.plan-accion-container {
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    height: 100vh;
}
.plan-accion-imagen {
    max-width: 80%; /* Ajusta el tamaño máximo al 80% del contenedor */
    height: auto; /* Mantiene la proporción de la imagen */
    margin: auto;
}
</style>