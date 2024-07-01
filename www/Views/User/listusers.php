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

        $currentUserId = $_SESSION['user_id'];
        ?>
      <form action="/update-users-inline" method="post" class="user-list-form">
        <table class="highlight">
          <thead>
          <tr>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Role</th>
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
                <select name="users[<?= htmlspecialchars($user['id']) ?>][role]" class="browser-default" <?= ($user['id'] == $currentUserId) ? 'disabled' : '' ?>>
                  <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                  <option value="contributor" <?= $user['role'] == 'contributor' ? 'selected' : '' ?>>Contributor</option>
                  <option value="editor" <?= $user['role'] == 'editor' ? 'selected' : '' ?>>Editor</option>
                </select>
              </td>
              <td>
                <form action="/delete-user" method="post" style="display:inline;">
                  <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                  <button type="submit" class="btn red" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');" <?= ($user['id'] == $currentUserId) ? 'disabled' : '' ?>>Delete</button>
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

<div class="right-align">
  <a href="/add-user" class="btn-floating btn-large waves-effect waves-light red"><i class="material-icons">add</i></a>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>