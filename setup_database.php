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
    // Create database connection
    $databaseManager = new DatabaseManager(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD'],
        $_ENV['DB_NAME']
    );
    
    // Connect to the database
    $databaseManager->connect();
    
    // Read the SQL file
    $sql = file_get_contents(__DIR__ . '/database.sql');
    
    // Execute the SQL
    $databaseManager->connection->exec($sql);
    
    echo "✅ Database tables created successfully!\n";
    
    // Verify the tables were created
    $result = $databaseManager->connection->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    echo "\nTables in database:\n";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $row['table_name'] . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 