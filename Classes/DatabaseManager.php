<?php

// This class will manage the connection to the database
// It will be passed on the other classes who need it
class DatabaseManager
{
    // These are private: only this class needs them
    private string $host;
    private string $user;
    private string $password;
    private string $dbname;

    public PDO $connection;

    public function __construct(string $host, string $user, string $password, string $dbname)
    {
     $this->host=$host;
     $this->user=$user;
     $this->password=$password;
     $this->dbname=$dbname;
    }

    public function connect(): void
    {
        // TODO: make the connection to the database using a PDO bject
        try{
            $dsn="mysql:host=$this->host;dbname=$this->dbname";
            $this->connection= new PDO($dsn,$this->user,$this->password);
            $this->connection->setAttribute(PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}

