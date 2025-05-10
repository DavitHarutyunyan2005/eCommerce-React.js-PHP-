<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Seeder;

try {
    $seeder = new Seeder();
    $seeder->run();
    echo "Seeding completed successfully.\n";
    return 0;
} catch (Throwable $e) {
    fwrite(STDERR, "Seeding failed: " . $e->getMessage() . "\n");
    return 1;
}
