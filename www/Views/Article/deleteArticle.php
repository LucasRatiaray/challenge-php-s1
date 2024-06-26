<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <title>Delete Article - <?= htmlspecialchars($article->getTitle()) ?></title>
</head>
<body>

<div class="container">
    <h1>Delete Article - <?= htmlspecialchars($article->getTitle()) ?></h1>
    <p>Are you sure you want to delete this article?</p>
    <form method="post" action="/delete-article">
        <input type="hidden" name="id" value="<?= $article->getId() ?>">
        <button type="submit" class="btn red">Delete</button>
        <a href="/list-articles" class="btn">Cancel</a>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
