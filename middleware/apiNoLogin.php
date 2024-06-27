<?php

function apiNoLogin() {
    if (!isset($_SESSION['display_name'])) {
        header("Location: /login");
        exit();
    }
}
