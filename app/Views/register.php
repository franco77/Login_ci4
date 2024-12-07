<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <h1>Register</h1>
    <form action="/registerUser" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <label for="role">Role:</label>
        <select name="role_id" id="role">
            <option value="1">Admin</option>
            <option value="2">User</option>
        </select>
        <br>
        <button type="submit">Register</button>
    </form>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= esc($error) ?></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <p style="color: green;"><?= esc($success) ?></p>
    <?php endif; ?>
</body>

</html>