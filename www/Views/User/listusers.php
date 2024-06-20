<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <title>Liste des utilisateurs</title>
</head>
<body>

<div class="container">
  <h1>Liste des utilisateurs</h1>
    <?php if (!empty($users)): ?>
        <?php
        usort($users, function($a, $b) {
            return $a['id'] <=> $b['id'];
        });
        ?>
      <form action="/update-users-inline" method="post">
        <table class="highlight">
          <thead>
          <tr>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td>
                <input type="text" name="users[<?= htmlspecialchars($user['id']) ?>][firstname]" value="<?= htmlspecialchars($user['firstname']) ?>">
              </td>
              <td>
                <input type="text" name="users[<?= htmlspecialchars($user['id']) ?>][lastname]" value="<?= htmlspecialchars($user['lastname']) ?>">
              </td>
              <td>
                <input type="email" name="users[<?= htmlspecialchars($user['id']) ?>][email]" value="<?= htmlspecialchars($user['email']) ?>">
              </td>
              <td>
                <a href="/edit-user?id=<?= htmlspecialchars($user['id']) ?>" class="btn">Edit</a>
                <form action="/delete-user" method="post" style="display:inline;">
                  <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                  <button type="submit" class="btn red" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Delete</button>
                </form>
                <button type="submit" class="btn green">Save</button>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </form>
    <?php else: ?>
      <p>No users found.</p>
    <?php endif; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
