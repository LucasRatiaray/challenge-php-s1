<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <title>View Article</title>
  <style>
    .comments-container {
      display: flex;
      overflow-x: auto;
      height: 20em;
      width: 100%;
      position: relative;
      scroll-behavior: smooth;
      padding: 20px 0; /* Add some padding for better scrolling experience */
      justify-content: center; /* Center the comments container */
    }
    .comments {
      display: flex;
      align-items: center; /* Center comments vertically within the container */
    }
    .comment-card {
      display: inline-flex;
      flex-shrink: 0;
      width: 80%; /* Adjust width to ensure it's centered */
      justify-content: center;
      box-sizing: border-box;
      scroll-snap-align: start;
    }
    .card {
      margin: 0 10px;
      width: 100%; /* Ensure each card takes up the full width of the comment card */
    }
  </style>
</head>
<body>
<div class="container">
  <h1><?= htmlspecialchars($article->getTitle()) ?></h1>
  <p><?= nl2br(htmlspecialchars($article->getContent())) ?></p>
  <p><strong>Description:</strong> <?= htmlspecialchars($article->getDescription()) ?></p>
  <a href="/dashboard" class="btn">Back to Dashboard</a>

  <h2>Commentaires</h2>
  <div class="comments-container">
    <div class="comments">
        <?php foreach ($comments as $index => $comment): ?>
          <div class="comment-card" id="comment-<?= $index ?>">
            <div class="card">
              <div class="card-content">
                <p><?= htmlspecialchars($comment->getContent()) ?></p>
                <p><small>Posté par utilisateur <?= htmlspecialchars($comment->getUserId()) ?></small></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
    </div>
  </div>

  <h3>Ajouter un Commentaire</h3>
  <form action="/store-comment?article_id=<?= htmlspecialchars($article->getId()) ?>" method="post">
    <div class="input-field">
      <textarea id="content" name="content" class="materialize-textarea" required></textarea>
      <label for="content">Votre Commentaire</label>
    </div>
    <button type="submit" class="btn">Envoyer</button>
  </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
