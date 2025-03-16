<?php
namespace App\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Exception;

class DB {
    private static ?Connection $conn = null;

    public static function getConnection(): Connection {
        if (self::$conn === null) {
            $config = require __DIR__ . '/../../config/env.php';
            try {
                self::$conn = DriverManager::getConnection([
                    'dbname'   => $config['db']['name'],
                    'user'     => $config['db']['user'],
                    'password' => $config['db']['pass'],
                    'host'     => $config['db']['host'],
                    'driver'   => 'pdo_mysql',
                ]);
            } catch (Exception $e) {
                die("Database connection error: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
