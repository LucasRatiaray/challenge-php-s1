<h1>Liste des utilisateurs</h1>

<?php foreach ($users as $user): ?>
  <form action="/update-user-inline" method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

    <label for="firstname_<?= htmlspecialchars($user['id']) ?>">Prénom:</label>
    <input type="text" id="firstname_<?= htmlspecialchars($user['id']) ?>" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required>

    <label for="lastname_<?= htmlspecialchars($user['id']) ?>">Nom:</label>
    <input type="text" id="lastname_<?= htmlspecialchars($user['id']) ?>" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required>

    <label for="email_<?= htmlspecialchars($user['id']) ?>">Email:</label>
    <input type="email" id="email_<?= htmlspecialchars($user['id']) ?>" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <button type="submit">Mettre à jour</button>
  </form>
  <hr>
<?php endforeach; ?>