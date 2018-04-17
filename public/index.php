<?php

require_once __DIR__ . '/../bootstrap/autoload.php';

require_once __DIR__ . '/../bootstrap/app.php';

require_once __DIR__ . '/../bootstrap/helpers.php';

try {
    \WebTech\Hospital\Router::handle();
} catch (\Exception $exception) {
    $stackTrace = preg_replace('/\n/', '<br>', $exception->getTraceAsString());

    echo "
        <h1>Uncaught exception: " . get_class($exception) . "</h1>
        <h2>Message: {$exception->getMessage()}</h2>
        <h3>Stacktrace:</h3>
        <b>{$stackTrace}</b>
    ";
}
