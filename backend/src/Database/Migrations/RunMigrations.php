<?php

namespace App\Database\Migrations;

require_once __DIR__ . '/../../../vendor/autoload.php';

$migration = new CreateTables();
$migration->create();
