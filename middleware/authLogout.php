<?php

function logoutMiddleware() {
    if (!isset($_SESSION['display_name'])) {
        header("Location: /");
        exit();
    }
}
