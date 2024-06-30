<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <title>View Page</title>
</head>
<body>
<div class="container">
  <h1><?= htmlspecialchars($page->getTitle()) ?></h1>
  <div><?= $page->getContent() ?></div> <!-- Allow HTML content -->
  <p><strong>Description:</strong> <?= htmlspecialchars($page->getDescription()) ?></p>
  <a href="/dashboard" class="btn">Back to Dashboard</a>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
