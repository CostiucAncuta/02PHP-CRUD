<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Books - track your collection of [Books]</title>
</head>
<body>

<h1>Ancuta's Library </h1>

<ul>
    <?php foreach ($books as $book) : ?>
        <li><?= $book['name'] ?></li>
    <?php endforeach; ?>
</ul>

</body>
</html>