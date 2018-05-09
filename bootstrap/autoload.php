<?php

require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function ($class) {
    // Project specific namespace prefix.
    $prefix = 'WebTech\\Hospital\\';

    // Base directory for this namespace prefix.
    $baseDir = __DIR__ . '/../app/';

    // Checks if the class uses the namespace prefix.
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // If it doesn't, moving to the next registered autoloader.
        return;
    }

    // Get relative class name.
    $relativeClass = substr($class, $len);

    // Construct the full path to the file.
    $filePath = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    // If the file exists, require it.
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});
