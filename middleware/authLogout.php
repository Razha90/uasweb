<?php

function logoutMiddleware($next) {
    if (!isset($_SESSION['display_name'])) {
        header("Location: /");
        exit();
    }
    $next();
}
