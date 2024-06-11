<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Pages</title>
</head>
<body>
<h1>Your Pages</h1>
<table border="1">
    <thead>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Content</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($pages as $page): ?>
        <tr>
            <td><?= htmlspecialchars($page['id']); ?></td>
            <td><?= htmlspecialchars($page['title']); ?></td>
            <td><?= htmlspecialchars($page['content']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<a href="/dashboard">Back to Dashboard</a>
</body>
</html>