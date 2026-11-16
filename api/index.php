<?php

/*
 * Pintu masuk aplikasi di Vercel (serverless).
 * Di Vercel hanya /tmp yang bisa ditulis, jadi seluruh jalur tulis Laravel
 * (storage, cache bootstrap) diarahkan ke sana sebelum aplikasi dimuat.
 */

$dirs = [
    '/tmp/storage/app',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

$env = [
    'LARAVEL_STORAGE_PATH' => '/tmp/storage',
    'APP_SERVICES_CACHE'   => '/tmp/bootstrap/cache/services.php',
    'APP_PACKAGES_CACHE'   => '/tmp/bootstrap/cache/packages.php',
    'APP_CONFIG_CACHE'     => '/tmp/bootstrap/cache/config.php',
    'APP_ROUTES_CACHE'     => '/tmp/bootstrap/cache/routes.php',
    'APP_EVENTS_CACHE'     => '/tmp/bootstrap/cache/events.php',
];

foreach ($env as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

// Log ke stderr agar muncul di tab Logs Vercel (bila belum diatur).
if (getenv('LOG_CHANNEL') === false) {
    putenv('LOG_CHANNEL=stderr');
    $_ENV['LOG_CHANNEL'] = 'stderr';
}

require __DIR__ . '/../public/index.php';
