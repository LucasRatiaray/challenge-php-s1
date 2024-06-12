<h1>Profil</h1>

<?php if (isset($user)): ?>
  <p>First Name: <?= htmlspecialchars($user['firstname']) ?></p>
  <p>Last Name: <?= htmlspecialchars($user['lastname']) ?></p>
  <p>Email: <?= htmlspecialchars($user['email']) ?></p>
  <p>Role: <?= htmlspecialchars($user['role']) ?></p>

  <form action="/edit-user" method="get">
    <button type="submit">Edit</button>
  </form>
<?php else: ?>
  <p>User information not available.</p>
<?php endif; ?>
