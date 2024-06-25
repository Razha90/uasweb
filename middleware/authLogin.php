<?php

function authMiddleware() {
    if (isset($_SESSION['display_name'])) {
        header("Location: /");
        exit();
    }
}
