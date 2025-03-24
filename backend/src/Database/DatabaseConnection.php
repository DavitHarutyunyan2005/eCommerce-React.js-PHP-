<?php

namespace Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Exception;

class DatabaseConnection
{
    private static ?Connection $conn = null;

    public static function getConnection(): Connection
    {
        if (self::$conn === null) {
            try {
                $config = require __DIR__ . '/../../config/env.php';
                $dbConfig = $config['db'] ?? [];

                if (!isset($dbConfig['host'], $dbConfig['name'], $dbConfig['user'], $dbConfig['pass'])) {
                    throw new Exception('Database configuration is incomplete.');
                }

                self::$conn = DriverManager::getConnection([
                    'dbname'   => $dbConfig['name'],
                    'user'     => $dbConfig['user'],
                    'password' => $dbConfig['pass'],
                    'host'     => $dbConfig['host'],
                    'driver'   => 'mysqli',
                ]);
            } catch (Exception $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }

        return self::$conn;
    }
}
