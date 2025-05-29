<?php

// Show errors so we get helpful information
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Load Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Load DatabaseManager
require_once 'Classes/DatabaseManager.php';

try {
    echo "Testing database connection...\n";
    echo "----------------------------------------\n";
    echo "Database Host: " . $_ENV['DB_HOST'] . "\n";
    echo "Database Name: " . $_ENV['DB_NAME'] . "\n";
    echo "Database User: " . $_ENV['DB_USER'] . "\n";
    echo "----------------------------------------\n\n";

    // Create database connection
    $databaseManager = new DatabaseManager(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD'],
        $_ENV['DB_NAME']
    );
    
    // Attempt to connect
    $databaseManager->connect();
    
    // Check if connection is established
    if ($databaseManager->connection === null) {
        throw new Exception("Connection object is null");
    }
    
    // If we get here, connection was successful
    echo "✅ Database connection successful!\n";
    
    // Test if we can query the database
    $result = $databaseManager->connection->query("SELECT version()");
    $version = $result->fetch(PDO::FETCH_ASSOC);
    echo "\nPostgreSQL Version: " . $version['version'] . "\n";
    
    // List all tables
    $result = $databaseManager->connection->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    echo "\nTables in database:\n";
    $tables = $result->fetchAll(PDO::FETCH_ASSOC);
    if (empty($tables)) {
        echo "No tables found in the database.\n";
    } else {
        foreach ($tables as $table) {
            echo "- " . $table['table_name'] . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    
    // Additional debugging information
    echo "\nDebug Information:\n";
    echo "PHP Version: " . PHP_VERSION . "\n";
    echo "PDO Drivers: " . implode(', ', PDO::getAvailableDrivers()) . "\n";
    echo "----------------------------------------\n";
} 