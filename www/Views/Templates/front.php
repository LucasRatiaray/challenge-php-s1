<?php
use App\Models\Config;

$configModel = new Config();
$config = $configModel->getConfig();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Ceci est mon front</title>
  <meta name="description" content="Super site avec une magnifique intégration">
  <link rel="stylesheet" href="/css/front.css">
  <style>
    :root {
      --background-color: <?= htmlspecialchars($config->getBackgroundColor()) ?>;
      --font-color: <?= htmlspecialchars($config->getFontColor()) ?>;
      --font-size: <?= htmlspecialchars($config->getFontSize()) ?>;
      --font-style: <?= htmlspecialchars($config->getFontStyle()) ?>;
    }

    body {
      background-color: var(--background-color);
      color: var(--font-color);
      font-size: var(--font-size);
      font-style: var(--font-style);
    }
  </style>
</head>
<body>

<!-- include view -->
<?php include "../Views/".$this->view.".php";?>

</body>
</html>