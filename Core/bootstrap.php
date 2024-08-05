<?php

// populate environment variables
if (file_exists(base_path('/.env'))) {
    $lines = file(base_path('/.env'));
    foreach ($lines as $line) {
        if (trim($line) === '' || str_starts_with(trim($line), '#')) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        putenv(sprintf('%s=%s', trim($name), trim($value)));
    }
}
