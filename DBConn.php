<?php
// ============================================================
//  COSUP — config/DBConn.php
//  Database Connection File
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
//  Used by: Website (PHP) + API endpoints (Android app)
//  Project Folder: COSUP_V2
// ============================================================

define('DB_HOST',     'localhost');
define('DB_USER',     'root');
define('DB_PASS',     '');
define('DB_NAME',     'cosup_db');
define('DB_CHARSET',  'utf8mb4');

// Base URL — used for redirects and asset paths
define('BASE_URL',    'http://localhost/COSUP_V2');
define('IMAGES_PATH', BASE_URL . '/IMAGES/');

// ============================================================
//  MySQLi CONNECTION
//  Used by: All PHP website pages
// ============================================================
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        error_log('[COSUP_V2 DB ERROR] ' . $conn->connect_error);
        http_response_code(500);
        die(json_encode([
            'status'  => 'error',
            'message' => 'Database connection failed. Please try again later.'
        ]));
    }

    $conn->set_charset(DB_CHARSET);
    return $conn;
}

// ============================================================
//  PDO CONNECTION
//  Used by: API endpoints called by Android mobile app
// ============================================================
function getPDOConnection() {
    $dsn = 'mysql:host=' . DB_HOST .
           ';dbname='    . DB_NAME .
           ';charset='   . DB_CHARSET;

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log('[COSUP_V2 PDO ERROR] ' . $e->getMessage());
        http_response_code(500);
        die(json_encode([
            'status'  => 'error',
            'message' => 'Database connection failed. Please try again later.'
        ]));
    }
}

// ============================================================
//  CORS HEADERS
//  Required so Android app (Kotlin/Retrofit) can call
//  PHP API endpoints from COSUP_V2/api/
// ============================================================
function cosupHeaders() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Content-Type: application/json; charset=utf-8');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}