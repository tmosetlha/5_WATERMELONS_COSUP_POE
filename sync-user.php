<?php
// ============================================================
//  COSUP V2 — auth/sync-user.php
//  Firebase → MySQL User Sync
//  Called by firebase-auth.js after every login/register
//  Works for BOTH website and Android app
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

require_once __DIR__ . '/../config/DBConn.php';
require_once __DIR__ . '/../config/firebase.php';

// Set CORS + JSON headers so Android app can call this too
cosupHeaders();

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Method not allowed.'
    ]);
    exit;
}

// ============================================================
//  GET REQUEST BODY
// ============================================================
$body  = file_get_contents('php://input');
$data  = json_decode($body, true);

$idToken   = $data['idToken']  ?? '';
$extraData = $data['extra']    ?? [];

if (empty($idToken)) {
    http_response_code(400);
    echo json_encode([
        'status'  => 'error',
        'message' => 'No ID token provided.'
    ]);
    exit;
}

// ============================================================
//  VERIFY FIREBASE TOKEN
//  Calls Firebase REST API to validate the token
// ============================================================
$firebaseResult = verifyFirebaseToken($idToken);

if (!$firebaseResult['success']) {
    http_response_code(401);
    echo json_encode([
        'status'  => 'error',
        'message' => $firebaseResult['message']
    ]);
    exit;
}

// ============================================================
//  SYNC USER TO cosup_db
// ============================================================
$conn = getDBConnection();

$firebase_uid   = $conn->real_escape_string($firebaseResult['firebase_uid']);
$email          = $conn->real_escape_string($firebaseResult['email']);
$email_verified = $firebaseResult['email_verified'] ? 1 : 0;

// Use extra data from registration if provided
$first_name = $conn->real_escape_string(
    $extraData['first_name'] ?? ''
);
$last_name  = $conn->real_escape_string(
    $extraData['last_name']  ?? ''
);
$phone      = $conn->real_escape_string(
    $extraData['phone']      ?? ''
);
$lang_pref  = $conn->real_escape_string(
    $extraData['lang_pref']  ?? 'en'
);

// If no name in extra, parse from Firebase display name
if (empty($first_name)) {
    $displayName = $firebaseResult['display_name'] ?? '';
    $parts       = explode(' ', trim($displayName), 2);
    $first_name  = $conn->real_escape_string($parts[0] ?? 'User');
    $last_name   = $conn->real_escape_string($parts[1] ?? '');
}

// ============================================================
//  CHECK IF USER EXISTS
// ============================================================
$checkSQL = "SELECT user_id, role, first_name, last_name,
                    email_verified, lang_pref
             FROM users
             WHERE firebase_uid = '$firebase_uid'
             OR email = '$email'
             LIMIT 1";

$checkResult = $conn->query($checkSQL);

if ($checkResult && $checkResult->num_rows > 0) {
    // ---- UPDATE EXISTING USER ----
    $existing = $checkResult->fetch_assoc();

    $updateSQL = "UPDATE users SET
                    firebase_uid   = '$firebase_uid',
                    email_verified = $email_verified,
                    updated_at     = NOW()";

    // Only update phone/lang if provided
    if (!empty($phone)) {
        $updateSQL .= ", phone = '$phone'";
    }
    if (!empty($lang_pref) && $lang_pref !== 'en') {
        $updateSQL .= ", lang_pref = '$lang_pref'";
    }

    $updateSQL .= " WHERE user_id = {$existing['user_id']}";
    $conn->query($updateSQL);

    $userId    = $existing['user_id'];
    $role      = $existing['role'];
    $firstName = $existing['first_name'];
    $lastName  = $existing['last_name'];

} else {
    // ---- INSERT NEW USER ----
    $insertSQL = "INSERT INTO users
                    (firebase_uid, first_name, last_name,
                     email, phone, lang_pref,
                     email_verified, consent_given, role)
                  VALUES
                    ('$firebase_uid', '$first_name', '$last_name',
                     '$email', '$phone', '$lang_pref',
                     $email_verified, 1, 'user')";

    $conn->query($insertSQL);

    $userId    = $conn->insert_id;
    $role      = 'user';
    $firstName = $first_name;
    $lastName  = $last_name;
}

// ============================================================
//  START PHP SESSION — for website page access control
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['cosup_user_id']    = $userId;
$_SESSION['cosup_role']       = $role;
$_SESSION['cosup_email']      = $email;
$_SESSION['cosup_first_name'] = $firstName;
$_SESSION['cosup_firebase_uid'] = $firebase_uid;

$conn->close();

// ============================================================
//  RETURN SUCCESS RESPONSE
//  Used by firebase-auth.js to redirect to correct dashboard
//  Also used by Android app Retrofit to get user details
// ============================================================
echo json_encode([
    'status'     => 'success',
    'user_id'    => $userId,
    'role'       => $role,
    'first_name' => $firstName,
    'last_name'  => $lastName,
    'email'      => $email,
    'lang_pref'  => $lang_pref,
]);