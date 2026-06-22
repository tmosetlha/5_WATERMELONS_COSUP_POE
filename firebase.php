<?php
// ============================================================
//  COSUP V2 — config/firebase.php
//  Firebase Configuration — LIVE KEYS
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

// ============================================================
//  FIREBASE WEB CONFIG — LIVE KEYS
// ============================================================
define('FIREBASE_API_KEY',              'AIzaSyAfUfJHTLti8mf4OvO1BHWLWLLNib8MVJo');
define('FIREBASE_AUTH_DOMAIN',          'cosup-5d9f6.firebaseapp.com');
define('FIREBASE_PROJECT_ID',           'cosup-5d9f6');
define('FIREBASE_STORAGE_BUCKET',       'cosup-5d9f6.firebasestorage.app');
define('FIREBASE_MESSAGING_SENDER_ID',  '859463184973');
define('FIREBASE_APP_ID',               '1:859463184973:web:a6ce976683b73fab50a204');
define('FIREBASE_MEASUREMENT_ID',       'G-K6P8NLR2N6');

// ============================================================
//  FIREBASE CONFIG ARRAY
//  Passed to JavaScript via PHP for website frontend
//  Also used by Android app (same project)
// ============================================================
$firebaseConfig = [
    'apiKey'            => FIREBASE_API_KEY,
    'authDomain'        => FIREBASE_AUTH_DOMAIN,
    'projectId'         => FIREBASE_PROJECT_ID,
    'storageBucket'     => FIREBASE_STORAGE_BUCKET,
    'messagingSenderId' => FIREBASE_MESSAGING_SENDER_ID,
    'appId'             => FIREBASE_APP_ID,
    'measurementId'     => FIREBASE_MEASUREMENT_ID,
];

// ============================================================
//  FIREBASE REST API — Token Verification URL
//  Used by PHP to verify tokens from website AND Android app
// ============================================================
define('FIREBASE_REST_URL',
    'https://identitytoolkit.googleapis.com/v1/accounts:lookup?key='
    . FIREBASE_API_KEY
);

// ============================================================
//  VERIFY FIREBASE ID TOKEN (PHP side)
//  Frontend/Android sends idToken → PHP verifies with Firebase
//  Returns user data if valid
// ============================================================
function verifyFirebaseToken($idToken) {
    if (empty($idToken)) {
        return ['success' => false, 'message' => 'No token provided.'];
    }

    $url  = FIREBASE_REST_URL;
    $data = json_encode(['idToken' => $idToken]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST,           true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,     $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return ['success' => false, 'message' => 'Invalid or expired token.'];
    }

    $decoded = json_decode($response, true);

    if (!isset($decoded['users'][0])) {
        return ['success' => false, 'message' => 'User not found in Firebase.'];
    }

    $firebaseUser = $decoded['users'][0];

    return [
        'success'        => true,
        'firebase_uid'   => $firebaseUser['localId']       ?? null,
        'email'          => $firebaseUser['email']         ?? null,
        'display_name'   => $firebaseUser['displayName']   ?? null,
        'email_verified' => $firebaseUser['emailVerified'] ?? false,
        'photo_url'      => $firebaseUser['photoUrl']      ?? null,
    ];
}

// ============================================================
//  SYNC FIREBASE USER TO cosup_db
//  Works for BOTH website login and Android app login
//  firebase_uid is the shared key between Firebase + MySQL
// ============================================================
function syncFirebaseUserToDB($firebaseData) {
    require_once __DIR__ . '/DBConn.php';
    $conn = getDBConnection();

    $firebase_uid    = $conn->real_escape_string($firebaseData['firebase_uid']);
    $email           = $conn->real_escape_string($firebaseData['email']);
    $email_verified  = $firebaseData['email_verified'] ? 1 : 0;

    $display_name    = $firebaseData['display_name'] ?? '';
    $name_parts      = explode(' ', trim($display_name), 2);
    $first_name      = $conn->real_escape_string($name_parts[0] ?? 'User');
    $last_name       = $conn->real_escape_string($name_parts[1] ?? '');

    // Check if user already exists by firebase_uid or email
    $check = $conn->query(
        "SELECT user_id, role FROM users
         WHERE firebase_uid = '$firebase_uid'
         OR email = '$email'
         LIMIT 1"
    );

    if ($check && $check->num_rows > 0) {
        $existing = $check->fetch_assoc();
        $conn->query(
            "UPDATE users SET
                firebase_uid   = '$firebase_uid',
                email_verified = $email_verified,
                updated_at     = NOW()
             WHERE user_id = {$existing['user_id']}"
        );
        $userId = $existing['user_id'];
        $role   = $existing['role'];
    } else {
        $conn->query(
            "INSERT INTO users
                (firebase_uid, first_name, last_name, email,
                 email_verified, consent_given, role)
             VALUES
                ('$firebase_uid', '$first_name', '$last_name',
                 '$email', $email_verified, 1, 'user')"
        );
        $userId = $conn->insert_id;
        $role   = 'user';
    }

    $conn->close();

    return [
        'user_id'    => $userId,
        'role'       => $role,
        'email'      => $email,
        'first_name' => $first_name,
        'last_name'  => $last_name,
    ];
}