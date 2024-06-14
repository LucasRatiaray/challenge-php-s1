<h1>Dashboard</h1>
<form action="/logout" method="post">
    <button type="submit">Logout</button>
</form>

<form action="/create-page" method="get">
  <button type="submit">Create Page</button>
</form>

<form action="/profil-user" method="get">
  <button type="submit">Profil</button>
</form>

<?php if ($userRole === 'admin'): ?>
  <form action="/list-users" method="get">
    <button type="submit">Voir tous les utilisateurs</button>
  </form>
<?php endif; ?>