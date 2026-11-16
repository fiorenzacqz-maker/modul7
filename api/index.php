<?php

// DIAGNOSTIK SEMENTARA: hapus setelah masalah ketemu
error_log('DIAG php=' . PHP_VERSION
    . ' pdo_pgsql=' . (extension_loaded('pdo_pgsql') ? 'yes' : 'NO')
    . ' vendor=' . (file_exists(__DIR__ . '/../vendor/autoload.php') ? 'yes' : 'NO')
    . ' env_APP_KEY=' . (getenv('APP_KEY') ? 'set' : 'EMPTY'));
register_shutdown_function(function () {
    $e = error_get_last();
    if ($e) {
        error_log('DIAG FATAL: ' . $e['message'] . ' @ ' . $e['file'] . ':' . $e['line']);
    }
});

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