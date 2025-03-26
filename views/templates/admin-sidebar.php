<aside class="dashboard__sidebar">
    <nav class="dashboard__menu">
        <a href="/">
            <img src="/build/img/logoMI.svg" width="160" height="72" alt="Logotipo de Mundo Imperial" class="dashboard__logo">
        </a>

        <?php if(is_admin()): ?>
        <a href="/admin/dashboard" class="dashboard__enlace <?php echo pagina_actual('/dashboard') ? 'dashboard__enlace--actual' : ''; ?> ">
            <i class="fa-solid fa-house dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Inicio
            </span>    
        </a>
        <?php endif; ?>

        <?php if(is_admin()): ?>
        <a href="/admin/areas" class="dashboard__enlace <?php echo pagina_actual('/areas') ? 'dashboard__enlace--actual' : ''; ?> ">
            <i class="fa-solid fa-building dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Departamentos
            </span>    
        </a>
        <?php endif; ?>

        <?php if(is_admin()): ?>
        <a href="/admin/progreso" class="dashboard__enlace <?php echo pagina_actual('/progreso') ? 'dashboard__enlace--actual' : ''; ?> ">
            <!-- <i class="fa-solid fa-bars-progress dashboard__icono"></i> -->
            <i class="fa-solid fa-spinner dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Progreso
            </span>    
        </a>
        <?php endif; ?>
        
        <?php if(is_admin()): ?>
        <a href="/admin/resultados" class="dashboard__enlace <?php echo pagina_actual('/resultados') ? 'dashboard__enlace--actual' : ''; ?> ">
            <i class="fa-solid fa-square-poll-horizontal dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Resultados
            </span>    
        </a>
        <?php endif; ?>
        
        <?php if(is_master()): ?>
        <a href="/admin/administradores" class="dashboard__enlace <?php echo pagina_actual('/administradores') ? 'dashboard__enlace--actual' : ''; ?> ">
            <i class="fa-solid fa-users dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Administradores
            </span>    
        </a>
        <?php endif; ?>

    </nav>

    <form method="POST" action="/logout" class="dashboard__form">
        <input type="submit" value="Cerrar Sesión" class="dashboard__submit--logout">
        <!-- <i class="fa-solid fa-right-from-bracket"></i> -->
    </form>
</aside>