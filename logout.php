<?php
// ============================================================
//  COSUP V2 — auth/logout.php
//  PHP Session Destroyer
//  Called by firebase-auth.js logoutCOSUP() function
//  Firebase sign out is handled by JS — this clears PHP session
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

require_once __DIR__ . '/../config/DBConn.php';

cosupHeaders();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all COSUP session variables
$_SESSION = [];

// Destroy the session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Destroy session
session_destroy();

echo json_encode([
    'status'  => 'success',
    'message' => 'Session cleared. See you soon!'
]);