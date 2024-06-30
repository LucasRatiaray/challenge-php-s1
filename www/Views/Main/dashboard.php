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
    .stats-card {
      padding: 20px;
      margin-bottom: 20px;
    }
    .latest-section {
      margin-bottom: 40px;
    }
    .grid {
      overflow: hidden;
    }
    .grid .row {
      display: flex;
      flex-wrap: wrap;
      margin: 0 -1rem;
    }
    .grid .col {
      padding: 0 1rem;
    }
    .col-4 {
      width: 33.3333%;
    }
    .col-6 {
      width: 50%;
    }
    .col-12 {
      width: 100%;
    }
    @media screen and (min-width: 768px) {
      .grid .col-sm-6 {
        width: 50%;
      }
      .grid .col-sm-4 {
        width: 33.3333%;
      }
      .grid .col-sm-12 {
        width: 100%;
      }
    }
    @media screen and (min-width: 960px) {
      .grid .col-md-6 {
        width: 50%;
      }
      .grid .col-md-4 {
        width: 33.3333%;
      }
      .grid .col-md-12 {
        width: 100%;
      }
    }
    @media screen and (min-width: 1180px) {
      .grid .col-lg-6 {
        width: 50%;
      }
      .grid .col-lg-4 {
        width: 33.3333%;
      }
      .grid .col-lg-12 {
        width: 100%;
      }
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
  <li><a href="/list-page">List Pages</a></li>
  <li><a href="/media">Media</a></li>
  <li><a href="/customize">Customiser</a></li>
  <li><a href="/logout">Logout</a></li>
</ul>

<!-- Main Content -->
<div class="main-content">
  <h1>Welcome to the Dashboard</h1>

  <!-- Stats Section -->
  <div class="grid">
    <div class="row">
      <div class="col col-12 col-md-6 col-lg-3">
        <div class="card stats-card blue lighten-4">
          <div class="card-content">
            <span class="card-title">Total Users</span>
            <h3><?= $totalUsers ?></h3>
          </div>
        </div>
      </div>
      <div class="col col-12 col-md-6 col-lg-3">
        <div class="card stats-card green lighten-4">
          <div class="card-content">
            <span class="card-title">Total Pages</span>
            <h3><?= $totalPages ?></h3>
          </div>
        </div>
      </div>
      <div class="col col-12 col-md-6 col-lg-3">
        <div class="card stats-card orange lighten-4">
          <div class="card-content">
            <span class="card-title">Total Articles</span>
            <h3><?= $totalArticles ?></h3>
          </div>
        </div>
      </div>
      <div class="col col-12 col-md-6 col-lg-3">
        <div class="card stats-card red lighten-4">
          <div class="card-content">
            <span class="card-title">Total Comments</span>
            <h3><?= $totalComments ?></h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Latest Entries Section -->
  <div class="latest-section">
    <h2>Latest Entries</h2>
    <div class="grid">
      <div class="row">
        <div class="col col-12 col-md-4">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Latest Page</span>
                <?php if ($latestPage): ?>
                  <p>Title: <?= htmlspecialchars($latestPage->getTitle()) ?></p>
                  <p>Description: <?= htmlspecialchars($latestPage->getDescription()) ?></p>
                <?php else: ?>
                  <p>No pages found.</p>
                <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-4">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Latest Article</span>
                <?php if ($latestArticle): ?>
                  <p>Title: <?= htmlspecialchars($latestArticle->getTitle()) ?></p>
                  <p>Description: <?= htmlspecialchars($latestArticle->getDescription()) ?></p>
                <?php else: ?>
                  <p>No articles found.</p>
                <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col col-12 col-md-4">
          <div class="card">
            <div class="card-content">
              <span class="card-title">Latest Comment</span>
                <?php if ($latestComment): ?>
                  <p>Content: <?= htmlspecialchars($latestComment->getContent()) ?></p>
                  <p>User ID: <?= htmlspecialchars($latestComment->getUserId()) ?></p>
                <?php else: ?>
                  <p>No comments found.</p>
                <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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
