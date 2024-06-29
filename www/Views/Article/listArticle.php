<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <title>Liste des articles</title>
</head>
<body>

<div class="container">
  <h1>Liste des articles</h1>
    <?php if (!empty($articles)): ?>
      <table class="highlight">
        <thead>
        <tr>
          <th>Titre</th>
          <th>Contenu</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($articles as $article): ?>
            <?php if ($article !== null): ?>
            <tr>
              <td><?= htmlspecialchars($article->getTitle()) ?></td>
              <td><?= htmlspecialchars($article->getContent()) ?></td>
              <td><?= htmlspecialchars($article->getDescription()) ?></td>
              <td>
                <a href="/view-article?id=<?= $article->getId() ?>" class="btn">View</a>
                <a href="/edit-article?id=<?= $article->getId() ?>" class="btn">Edit</a>
                <a href="/delete-article?id=<?= $article->getId() ?>" class="btn red" onclick="return confirm('Are you sure you want to delete this article?');">Delete</a>
              </td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No articles found.</p>
    <?php endif; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
