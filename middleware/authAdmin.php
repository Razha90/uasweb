<?php

function authAdmin($next) {
    if ($_SESSION['role'] !== 'admin') {
        header("Location: /");
        exit();
    }
    $next();
}
