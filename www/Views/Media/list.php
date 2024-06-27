<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <title>Media</title>
</head>
<body>
<div class="container">
  <h1>Media</h1>
  <a href="/media/upload" class="btn">Upload New Image</a>
  <div class="row">
      <?php foreach ($images as $image): ?>
        <div class="col s12 m4 l3">
          <div class="card">
            <div class="card-image">
              <img src="<?= htmlspecialchars($image->getLien()) ?>" alt="<?= htmlspecialchars($image->getTitle()) ?>">
            </div>
            <div class="card-content">
              <span class="card-title"><?= htmlspecialchars($image->getTitle()) ?></span>
              <p><?= htmlspecialchars($image->getDescription()) ?></p>
            </div>
            <div class="card-action">
              <form action="/media/delete" method="post" onsubmit="return confirm('Are you sure you want to delete this image?');">
                <input type="hidden" name="id" value="<?= $image->getId() ?>">
                <button type="submit" class="btn red">Delete</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
