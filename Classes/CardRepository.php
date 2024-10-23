<?php

// This class is focussed on dealing with queries for one type of data
// That allows for easier re-using and it's rather easy to find all your queries
// This technique is called the repository pattern
class CardRepository
{
    private DatabaseManager $databaseManager;

    // This class needs a database connection to function
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function create(array $cardData): void
    
    {
      $sql="INSERT INTO books (title, author, genre, publisher, rating, status) VALUES(:title, :author, :genre, :publisher, :rating. :status)";

      $statement=$this->databaseManager->connection->prepare(($sql));
      $statement->execute(
        [
            ':title'=> $cardData['title'],
            ':author'=>$cardData['author'],
            ':genre'=>$cardData['genre'],
            ':publisher'=>$cardData['publisher'],
            ':rating'=>$cardData['rating'],
            ':status'=>$cardData['status'],
        ]
        );
    }

    // Get one
    public function find(int $id): array
    {
    $sql="SELECT * FROM books WHERE id =:id";
    $statement=$this->databaseManager->connection->prepare($sql);
    $statement->execute([
        ':id'=> $id],
    );
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result === false ? [] : $result;
    }

    // Get all
    public function get(): array
    {
        // TODO: Create an SQL query
        $statement=$this->databaseManager->connection->query("SELECT * FROM books");
        // TODO: Use your database connection (see $databaseManager) and send your query to your database.
        // TODO: fetch your data at the end of that action.
        // TODO: replace dummy data by real one
        // return [
        //     ['name' => 'dummy one'],
        //     ['name' => 'dummy two'],
        // ];


        return $statement->fetchAll(PDO::FETCH_ASSOC);
        // We get the database connection first, so we can apply our queries with it
        // return $this->databaseManager->connection-> (runYourQueryHere)
    }

    public function update( int $id, array $cardData): void
    {
     $sql = "UPDATE books SET title = :title, author = :author, genre = :genre, publisher =:publisher, rating = :rating, status= :status  WHERE id =:id";
     $statement = $this->databaseManager->connection->prepare($sql);
     
     $statement-> execute([
        ':title' => $cardData['title'],
        ':author' => $cardData["author"],
        ':genre'=> $cardData["genre"],
        ':publishe'=>$cardData["publishe"],
        ':rating'=>$cardData["rating"],
        ':status'=>$cardData["status"],
        ':id'=>$id,
     ]);
    }

    public function delete(int $id): void
    {
      $sql = "DELETE FROM books WHERE id = :id";
      $statement = $this->databaseManager->connection->prepare($sql);
      $statement->execute([':id' => $id]);
    }

}