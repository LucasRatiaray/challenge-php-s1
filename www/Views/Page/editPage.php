<!-- editPage.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <title>Édition de Page</title>
</head>
<body>
  <div class="container">
    <h1>Édition de Page</h1>
    <form action="/update-page" method="POST">
      <input type="hidden" name="id" value="<?= $page->getId() ?>">
      <div class="input-field">
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($page->getTitle()) ?>" required>
        <label for="title">Titre</label>
      </div>
      <div class="input-field">
        <textarea id="content" name="content" class="materialize-textarea" required><?= htmlspecialchars($page->getContent()) ?></textarea>
        <label for="content">Contenu</label>
      </div>
      <div class="input-field">
        <textarea id="description" name="description" class="materialize-textarea"><?= htmlspecialchars($page->getDescription()) ?></textarea>
        <label for="description">Description</label>
      </div>
      <button type="submit" class="btn">Enregistrer les modifications</button>
    </form>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
