<?php
// ============================================================
//  COSUP V2 — auth/update-referral.php
//  Update Referral — Called by user/my-referrals.php
//  Also callable by Android app via Retrofit POST
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

require_once __DIR__ . '/../config/DBConn.php';

// Set CORS headers so Android app can call this too
cosupHeaders();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
//  AUTH CHECK
//  Accepts both PHP session (website) and
//  JSON body user_id (Android app)
// ============================================================
$body   = file_get_contents('php://input');
$data   = json_decode($body, true);

// Get user ID from session (website) or JSON body (Android)
$userId = $_SESSION['cosup_user_id']
          ?? $data['user_id']
          ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Unauthorised. Please sign in.'
    ]);
    exit;
}

// ============================================================
//  GET POST DATA
//  Supports both form POST (website) and JSON (Android)
// ============================================================
$refId = intval(
    $_POST['referral_id'] ?? $data['referral_id'] ?? 0
);
$contactNumber = trim(
    $_POST['contact_number'] ?? $data['contact_number'] ?? ''
);
$area = trim(
    $_POST['preferred_area'] ?? $data['preferred_area'] ?? ''
);
$serviceNeeded = trim(
    $_POST['service_needed'] ?? $data['service_needed'] ?? ''
);
$message = trim(
    $_POST['message'] ?? $data['message'] ?? ''
);

// ============================================================
//  VALIDATE
// ============================================================
if ($refId <= 0) {
    http_response_code(400);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid referral ID.'
    ]);
    exit;
}

if (empty($area) || empty($serviceNeeded)) {
    http_response_code(400);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Area and service needed are required.'
    ]);
    exit;
}

// ============================================================
//  UPDATE IN DATABASE
// ============================================================
$conn = getDBConnection();

$contactNumber = $conn->real_escape_string($contactNumber);
$area          = $conn->real_escape_string($area);
$serviceNeeded = $conn->real_escape_string($serviceNeeded);
$message       = $conn->real_escape_string($message);
$userId        = intval($userId);

// Check referral belongs to this user and is still pending
$check = $conn->query(
    "SELECT referral_id, status
     FROM referrals
     WHERE referral_id = $refId
     AND user_id = $userId
     LIMIT 1"
);

if (!$check || $check->num_rows === 0) {
    $conn->close();
    http_response_code(404);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Referral not found or access denied.'
    ]);
    exit;
}

$existing = $check->fetch_assoc();

if ($existing['status'] !== 'pending') {
    $conn->close();
    http_response_code(403);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Only pending referrals can be edited.'
    ]);
    exit;
}

// Perform update
$conn->query(
    "UPDATE referrals SET
        contact_number = '$contactNumber',
        preferred_area = '$area',
        service_needed = '$serviceNeeded',
        message        = '$message',
        updated_at     = NOW()
     WHERE referral_id = $refId
     AND user_id = $userId"
);

if ($conn->affected_rows >= 0) {
    // Fetch updated referral to return to Android app
    $updated = $conn->query(
        "SELECT * FROM referrals
         WHERE referral_id = $refId LIMIT 1"
    )->fetch_assoc();

    $conn->close();

    echo json_encode([
        'status'   => 'success',
        'message'  => 'Referral updated successfully.',
        'referral' => $updated
    ]);
} else {
    $conn->close();
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Failed to update referral. Please try again.'
    ]);
}