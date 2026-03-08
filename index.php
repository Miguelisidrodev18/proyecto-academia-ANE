<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/laravel-core/vendor/autoload.php';

$app = require_once __DIR__.'/laravel-core/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

try {
    $response = $kernel->handle(
        $request = Request::capture()
    );
    $response->send();
    $kernel->terminate($request, $response);
} catch (Exception $e) {
    echo "<h1>Error específico:</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<h2>Archivo:</h2>";
    echo "<pre>" . $e->getFile() . ":" . $e->getLine() . "</pre>";
}