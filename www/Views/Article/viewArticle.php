<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <title>View Article</title>
</head>
<body>
<div class="container">
  <h1><?= htmlspecialchars($article->getTitle()) ?></h1>
  <p><?= nl2br(htmlspecialchars($article->getContent())) ?></p>
  <p><strong>Description:</strong> <?= htmlspecialchars($article->getDescription()) ?></p>
  <a href="/dashboard" class="btn">Back to Dashboard</a>
  <a href="/add-comment?article_id=<?php echo $article->getId(); ?>" class="btn">Ajouter un Commentaire</a>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
