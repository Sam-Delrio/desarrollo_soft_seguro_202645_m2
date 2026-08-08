<?php
    ob_start();
    session_start();
    // session_destroy();
    
    // Constante para evitar duplicar el literal "Location:?" (SonarQube php:S1192)
    define('REDIRECT_DEFAULT', 'Location:?');

    require_once "models/DataBase.php";
    $allowed_controllers = [
        'Landing',
        'Login',
        'User',
        'Dashboard'
    ];

    $requested_controller = isset($_REQUEST['c']) ? $_REQUEST['c'] : 'Landing';

    if (in_array($requested_controller, $allowed_controllers, true)) {
        $controller = $requested_controller;
    } else {
        $controller = 'Landing'; // Redirección/fallback seguro
    }

    $route_controller = "controllers/" . $controller . ".php";

    if (file_exists($route_controller)) {
        $view = $controller;
        require_once $route_controller;
        $controller = new $controller;
        
        $action = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'main';

        if ($view === 'Landing' || $view === 'Login') {
            require_once "views/company/header.view.php";
            call_user_func(array($controller, $action));
            require_once "views/company/footer.view.php";
        } elseif (!empty($_SESSION['session'])) {
            require_once "models/User.php";
            $profile = unserialize($_SESSION['profile']);
            
            // 3. Sanitizar variable de sesión para inclusión segura de carpetas
            $session = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_SESSION['session']);
            
            $header_path = "views/roles/" . $session . "/header.view.php";
            $footer_path = "views/roles/" . $session . "/footer.view.php";

            if (file_exists($header_path) && file_exists($footer_path)) {
                require_once $header_path;
                call_user_func(array($controller, $action));
                require_once $footer_path;
            } else {
                header(REDIRECT_DEFAULT);
            }
        } else {
            header(REDIRECT_DEFAULT);
        }
    } else {
        header(REDIRECT_DEFAULT);
    }
    
    ob_end_flush();
?>