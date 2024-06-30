<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customiser</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <style>
    .color-picker, .font-picker {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>Personnaliser le thème</h1>
  <form action="/update-config" method="post" id="customize-form">
    <div class="input-field color-picker">
      <label for="background-color">Couleur de l'arrière-plan</label>
      <input type="color" id="background-color" name="background-color" value="<?= htmlspecialchars($config->getBackgroundColor()) ?>">
    </div>
    <div class="input-field color-picker">
      <label for="font-color">Couleur de la police</label>
      <input type="color" id="font-color" name="font-color" value="<?= htmlspecialchars($config->getFontColor()) ?>">
    </div>
    <div class="input-field font-picker">
      <label for="font-size">Taille de la police</label>
      <input type="number" id="font-size" name="font-size" min="10" max="72" value="<?= intval($config->getFontSize()) ?>">
    </div>
    <div class="input-field font-picker">
      <label for="font-style">Style de la police</label>
      <input type="text" id="font-style" name="font-style" value="<?= htmlspecialchars($config->getFontStyle()) ?>">
    </div>
    <button type="submit" class="btn">Appliquer</button>
  </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
