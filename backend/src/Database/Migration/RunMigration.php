<?php

namespace App\Database\Migration;

require_once __DIR__ . '/../../../vendor/autoload.php';

$migration = new CreateTables();
$migration->create();
