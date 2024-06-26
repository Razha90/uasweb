<?php

function authMiddleware() {
    if ($_SESSION['role'] == 'admin') {
        header("Location: /");
        exit();
    }
}
