<!doctype html>
<html>
<head>
    <!-- including header -->
    <?php require_once __DIR__ . '/shared/head.php'; ?>
</head>
<body>

<!-- including navbar -->
<?php require_once __DIR__ . '/shared/navbar.php'; ?>

<br>
<div class="title-box">
    <h1 class="h1-no-center" style="display: inline;">Users</h1>
    <div class="right">
        <form action="/users" method="get">
            <label for="f">Filter:</label>
            <input id="f" type="text" name="f" value="">
            <label for="t">Type:</label>
            <select id="t" name="t">
                <option value="any">Any</option>
                <option value="admin">Admin</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Patient</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>
</div>

<div class="appointments-box">
    <table>
        <thead>
        <tr>
            <th>Username</th>
            <th>Name</th>
            <th>E-Mail</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user->username ?></td>
                <td><?= $user->first_name . ' ' . $user->last_name ?></td>
                <td><?= $user->email ?></td>
                <td><?= $user->userType()->name ?></td>
                <td style="text-align: center;">
                    <a href="/users/<?= $user->id ?>">Profile</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- including footer -->
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<!-- including scripts -->
<?php require_once __DIR__ . '/shared/scripts.php'; ?>

</body>
</html>
