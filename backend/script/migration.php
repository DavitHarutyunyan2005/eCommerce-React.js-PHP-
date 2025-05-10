<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\CreateTables;

try {
    $migration = new CreateTables();
    $migration->run();
    echo "Migration completed successfully.\n";
    return 0;
} catch (Throwable $e) {
    fwrite(STDERR, "Migration failed: " . $e->getMessage() . "\n");
    return 1;
}
