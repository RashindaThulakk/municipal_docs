<?php
// includes/auth.php
session_start();

function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function require_role($roles = []) {
    require_login();
    if (!in_array($_SESSION['role'], (array)$roles)) {
        http_response_code(403);
        echo "Access denied.";
        exit;
    }
}
