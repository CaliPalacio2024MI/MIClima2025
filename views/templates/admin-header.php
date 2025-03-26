<header class="dashboard__header">
    <div class="dashboard__header-encabezado">
        <!-- Contenedor para la bienvenida a la izquierda -->
        <div class="dashboard__header-left">
            <p>¡Bienvenido! <?php echo $_SESSION['nombre'] ?? 'Administrador' ?></p>
        </div>
        
        <!-- Contenedor para el logo y el icono de la tuerca a la derecha -->
        <div class="dashboard__header-right">
            <!-- Logo -->
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.webp'; ?>" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.png'; ?>" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima.png'; ?>" alt="Logo MI Clima">
            </picture>
            
            <!-- Icono de la tuerca -->
            <div class="dashboard__settings">
                <i class="fa-solid fa-gear" id="settingsIcon"></i> <!-- Icono de tuerca -->
                <div class="dashboard__properties" id="propertiesDropdown" style="display: none;">
                    <!-- Aquí se mostrarán las propiedades del usuario -->
                </div>
            </div>
        </div>
    </div>
</header>