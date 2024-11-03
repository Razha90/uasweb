<?php

function authLogin($next) {
    if (isset($_SESSION['display_name'])) {
        header("Location: /");
        exit();
    }
    $next();
}
