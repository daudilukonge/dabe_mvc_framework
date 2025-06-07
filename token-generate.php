<?php

function generateSecretKey($length = 64) {
    return bin2hex(random_bytes($length / 2));
}

$accessKey = generateSecretKey();
$refreshKey = generateSecretKey();

// Path to your .env file
$envPath = __DIR__ . '/.env';

if (!file_exists($envPath)) {
    file_put_contents($envPath, '');
}

$envContent = file_get_contents($envPath);

// Replace or add keys
$envContent = preg_replace('/^JWT_SECRET=.*/m', "JWT_SECRET=$accessKey", $envContent);
$envContent = preg_replace('/^JWT_REFRESH_SECRET=.*/m', "JWT_REFRESH_SECRET=$refreshKey", $envContent);

// If keys were not present, append them
if (strpos($envContent, 'JWT_SECRET=') === false) {
    $envContent .= "\nJWT_SECRET=$accessKey";
}
if (strpos($envContent, 'JWT_REFRESH_SECRET=') === false) {
    $envContent .= "\nJWT_REFRESH_SECRET=$refreshKey";
}

// Save to .env
file_put_contents($envPath, $envContent);

echo "✅ JWT secrets successfully written to .env\n";
