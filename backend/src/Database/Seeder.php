<?php
namespace App\Database;

use App\Database\DB;
use Exception;

class Seeder {
    public static function seed() {
        $conn = DB::getConnection();
        $jsonData = file_get_contents(__DIR__ . '/../../data/data.json');
        $data = json_decode($jsonData, true);

        if (!$data) {
            die("Error decoding JSON data.");
        }

        try {
            foreach ($data as $table => $entries) {
                foreach ($entries as $entry) {
                    $conn->insert($table, $entry);
                }
            }
            echo "Database seeded successfully.\n";
        } catch (Exception $e) {
            die("Seeding failed: " . $e->getMessage());
        }
    }
}

Seeder::seed();
