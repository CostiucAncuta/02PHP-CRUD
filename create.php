<?php
declare(strict_types=1);

// Show errors so we get helpful information
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Load Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Load your classes
require_once 'classes/DatabaseManager.php';
require_once 'classes/CardRepository.php';

$databaseManager = new DatabaseManager(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASSWORD'],
    $_ENV['DB_NAME']
);
$databaseManager->connect();

$cardRepository = new CardRepository($databaseManager);

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $publisher = trim($_POST['publisher'] ?? '');
    $rating = !empty($_POST['rating']) ? filter_var($_POST['rating'], FILTER_VALIDATE_FLOAT) : null;
    $status = trim($_POST['status'] ?? '');

    // Validation
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (empty($author)) {
        $errors[] = "Author is required";
    }
    if ($rating !== false && ($rating < 0 || $rating > 10)) {
        $errors[] = "Rating must be between 0 and 10";
    }

    if (empty($errors)) {
        try {
            $cardRepository->create([
                'title' => $title,
                'author' => $author,
                'genre' => $genre,
                'publisher' => $publisher,
                'rating' => $rating,
                'status' => $status
            ]);
            $success = true;
            // Redirect to index page after successful creation
            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            $errors[] = "An error occurred while creating the book: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1 class="mb-4">Add New Book</h1>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        Book added successfully!
                    </div>
                <?php endif; ?>

                <form method="POST" action="create.php" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required 
                               value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Author *</label>
                        <input type="text" class="form-control" id="author" name="author" required
                               value="<?php echo htmlspecialchars($_POST['author'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <input type="text" class="form-control" id="genre" name="genre"
                               value="<?php echo htmlspecialchars($_POST['genre'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="publisher" class="form-label">Publisher</label>
                        <input type="text" class="form-control" id="publisher" name="publisher"
                               value="<?php echo htmlspecialchars($_POST['publisher'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating (0-10)</label>
                        <input type="number" class="form-control" id="rating" name="rating" 
                               min="0" max="10" step="0.1"
                               value="<?php echo htmlspecialchars($_POST['rating'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="Available" <?php echo (isset($_POST['status']) && $_POST['status'] === 'Available') ? 'selected' : ''; ?>>Available</option>
                            <option value="Borrowed" <?php echo (isset($_POST['status']) && $_POST['status'] === 'Borrowed') ? 'selected' : ''; ?>>Borrowed</option>
                            <option value="Reserved" <?php echo (isset($_POST['status']) && $_POST['status'] === 'Reserved') ? 'selected' : ''; ?>>Reserved</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Add Book</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>
