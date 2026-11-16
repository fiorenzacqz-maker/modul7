<?php

// Vercel hanya mengizinkan penulisan ke /tmp.
$tmp = [
    'APP_CONFIG_CACHE'   => '/tmp/config.php',
    'APP_EVENTS_CACHE'   => '/tmp/events.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_ROUTES_CACHE'   => '/tmp/routes.php',
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'VIEW_COMPILED_PATH' => '/tmp/views',
    'LOG_CHANNEL'        => 'stderr',
    'CACHE_STORE'        => 'array',
];
foreach ($tmp as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}
@mkdir('/tmp/views', 0755, true);

require __DIR__ . '/../public/index.php';