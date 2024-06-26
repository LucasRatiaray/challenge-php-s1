<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirmation de Suppression</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>
  <div class="container">
    <h1>Confirmation de Suppression</h1>
    <p>Êtes-vous sûr de vouloir supprimer cette page ? Cette action est irréversible.</p>
    <form action="/delete-page" method="POST">
      <input type="hidden" name="page_id" value="<?= $page->getId() ?>">
      <button type="submit" class="btn red">Confirmer la Suppression</button>
      <a href="/dashboard" class="btn">Annuler</a>
    </form>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
