<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <title>Upload Image</title>
</head>
<body>
<div class="container">
    <h1>Upload Image</h1>
    <form action="/media/upload" method="post" enctype="multipart/form-data">
        <div class="input-field">
            <input id="title" name="title" type="text" required>
            <label for="title">Title</label>
        </div>
        <div class="input-field">
            <textarea id="description" name="description" class="materialize-textarea" required></textarea>
            <label for="description">Description</label>
        </div>
        <div class="file-field input-field">
            <div class="btn">
                <span>File</span>
                <input type="file" name="image" required>
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text">
            </div>
        </div>
        <button type="submit" class="btn">Upload</button>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
