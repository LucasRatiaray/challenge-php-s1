<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <title>Ajouter un Commentaire</title>
</head>
<body>
<div class="container">
  <h1>Poster un commentaire</h1>
  <form action="/store-comment?article_id=<?= htmlspecialchars($articleId) ?>" method="post">
      <?= $form ?>
    <button type="submit" class="btn">Envoyer</button>
  </form>
  <a href="/view-article?id=<?= htmlspecialchars($articleId) ?>" class="btn">Retour à l'article</a>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

</body>
</html>
