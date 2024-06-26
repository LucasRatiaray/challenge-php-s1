<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <title>Edit Article - <?= htmlspecialchars($article->getTitle()) ?></title>
</head>
<body>

<div class="container">
    <h1>Edit Article - <?= htmlspecialchars($article->getTitle()) ?></h1>
    <form method="post" action="/update-article">
        <input type="hidden" name="id" value="<?= $article->getId() ?>">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($article->getTitle()) ?>" required>
        <label for="content">Content</label>
        <textarea id="content" name="content" required><?= htmlspecialchars($article->getContent()) ?></textarea>
        <label for="description">Description</label>
        <textarea id="description" name="description"><?= htmlspecialchars($article->getDescription()) ?></textarea>
        <button type="submit" class="btn">Update Article</button>
        <a href="/list-articles" class="btn">Cancel</a>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
