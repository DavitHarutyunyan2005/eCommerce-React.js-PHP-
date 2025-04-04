<?php

namespace App\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Exception;

class DatabaseConnection
{
    private static ?Connection $conn = null;

    private static function loadConfig(): array
    {
        $configFile = __DIR__ . '/../../config/env.php';
        if (!file_exists($configFile)) {
            throw new Exception('Database configuration file not found.');
        }

        $config = require $configFile;
        $dbConfig = $config['db'] ?? [];

        if (!isset($dbConfig['host'], $dbConfig['name'], $dbConfig['user'], $dbConfig['pass'])) {
            throw new Exception('Database configuration is incomplete.');
        }

        return $dbConfig;
    }

    public static function getConnection(): Connection
    {
        if (self::$conn === null) {
            try {
                $dbConfig = self::loadConfig();

                self::$conn = DriverManager::getConnection([
                    'dbname'   => $dbConfig['name'],
                    'user'     => $dbConfig['user'],
                    'password' => $dbConfig['pass'],
                    'host'     => $dbConfig['host'],
                    'driver'   => 'mysqli',
                ]);
            } catch (Exception $e) {
                throw new Exception('Database connection failed: ' . $e->getMessage());
            }
        }

        return self::$conn;
    }
}
