<?php

function apiNoLogin($next) {
    if (!isset($_SESSION['display_name'])) {
        header("Location: /login");
        exit();
    }
    $next();
}
