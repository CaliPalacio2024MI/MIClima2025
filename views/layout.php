

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MI CLIMA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"/>
    <!-- <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="/build/css/app.css">
    
</head>

<header class="header <?php echo $inicio ? 'inicio' : ''; ?>">
    <div class="contenedor contenido-header">
        <?php if($_SERVER["REQUEST_URI"] === '/'): ?> 
            <div class="centro">
                <div class="logo-centro">
                    <picture>
                        <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima-blanco.webp'; ?>" type="image/webp">
                        <source srcset="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima-blanco.png'; ?>" type="image/png">
                        <img src="<?php echo $_ENV['HOST'] . '/build/img/Logo-mi-clima-blanco.png'; ?>" alt="Logo Mi Clima">
                    </picture>
                </div>

                <div class="boton-encuesta">
                    <a href="/anonimo" class="boton-layout">Empezar encuesta</a>
                    <a href="/admin/dashboard" class="boton-layout">Iniciar sesión</a>
                </div>
            </div>
        <?php endif; ?>
    

    </div>
</header>

<body>

    <?php echo $contenido; ?>
    
<footer class="footer">
    <div class="footer__logos">
        <a href="/">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/logoMIblanco.webp'; ?>" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/build/img/logoMIblanco.png'; ?>" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/build/img/logoMIblanco.png'; ?>" alt="Logo Mundo Imperial">
            </picture>
        </a>
    
        <picture>
            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/palacio.webp'; ?>" type="image/webp">
            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/palacio.png'; ?>" type="image/png">
            <img src="<?php echo $_ENV['HOST'] . '/build/img/palacio.png'; ?>" alt="Logo Palacio">
        </picture>

        <picture>
            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/princess.webp'; ?>" type="image/webp">
            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/princess.png'; ?>" type="image/png">
            <img src="<?php echo $_ENV['HOST'] . '/build/img/princess.png'; ?>" alt="Logo Princess">
        </picture>

        <picture>
            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/pierre.webp'; ?>" type="image/webp">
            <source srcset="<?php echo $_ENV['HOST'] . '/build/img/pierre.png'; ?>" type="image/png">
            <img src="<?php echo $_ENV['HOST'] . '/build/img/pierre.png'; ?>" alt="Logo Pierre">
        </picture>
    </div>
</footer>
 

<script src="/build/js/bundle.min.js" defer></script>

</body>
</html>