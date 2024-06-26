<!-- createPage.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <title>Création de Page</title>
</head>
<body>
  <div class="container">
    <h1>Création de Page</h1>
    <form action="/store-page" method="POST">
      <div class="input-field">
        <input type="text" id="title" name="title" required>
        <label for="title">Titre</label>
      </div>
      <div class="input-field">
        <textarea id="content" name="content" class="materialize-textarea" required></textarea>
        <label for="content">Contenu</label>
      </div>
      <div class="input-field">
        <textarea id="description" name="description" class="materialize-textarea"></textarea>
        <label for="description">Description</label>
      </div>
      <button type="submit" class="btn">Créer</button>
    </form>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
