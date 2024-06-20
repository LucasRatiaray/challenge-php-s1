<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <title>Dashboard</title>
  <style>
    .sidenav {
      width: 250px;
    }
    .main-content {
      padding-left: 250px;
    }
  </style>
</head>
<body>

<ul class="sidenav sidenav-fixed" id="mobile-demo">
  <li><a href="/create-page">Create Page</a></li>
  <li><a href="/create-article">Create Article</a></li>
  <li><a href="/profil-user">Profil</a></li>
    <?php if ($userRole === 'admin'): ?>
      <li><a href="/list-users">Voir tous les utilisateurs</a></li>
    <?php endif; ?>
  <li><a href="/list-articles">List Articles</a></li>
  <li><a href="/logout">Logout</a></li>
</ul>

<!-- Main Content -->
<div class="main-content">
  <h1>Welcome to the Dashboard</h1>
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
              <a href="/view-page?id=<?= $page->getId() ?>" class="btn">View</a>
              <a href="/edit-page?id=<?= $page->getId() ?>" class="btn">Edit</a>
              <a href="/delete-page?id=<?= $page->getId() ?>" class="btn red">Delete</a>
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
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems);
  });
</script>
</body>
</html>
