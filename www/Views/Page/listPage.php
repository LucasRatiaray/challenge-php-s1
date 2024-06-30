<!-- Page/listPages.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <title>List Pages</title>
</head>
<body>
<div class="container">
  <h1>List Pages</h1>
    <?php if (!empty($pages)): ?>
      <table>
        <thead>
        <tr>
          <th>Title</th>
          <th>Content</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($pages as $page): ?>
          <tr>
            <td><?= htmlspecialchars($page->getTitle()) ?></td>
            <td><?= htmlspecialchars($page->getContent()) ?></td>
            <td><?= htmlspecialchars($page->getDescription()) ?></td>
            <td>
              <a href="/view-page/<?= $page->getSlug() ?>" class="btn">View</a>
              <a href="/edit-page?id=<?= $page->getId() ?>" class="btn">Edit</a>
              <a href="/delete-page?id=<?= $page->getId() ?>" class="btn red" onclick="return confirm('Are you sure you want to delete this page?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No pages found.</p>
    <?php endif; ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
