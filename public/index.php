<?php

require_once __DIR__ . '/../app/header.php';

use WebTech\Hospital\Session;



?>

<?php if (!Session::user()): ?>
    <h1>User is not logged in</h1>
    <h2>Registration</h2>
    <form action="action.php" method="post">
        <input type="hidden" name="action" value="register">

        First name:<br>
        <input type="text" name="firstName"><br>
        Last name:<br>
        <input type="text" name="lastName"><br>
        E-Mail address:<br>
        <input type="email" name="email"><br>
        Username:<br>
        <input type="text" name="username"><br>
        Password:<br>
        <input type="text" name="password"><br>
        <br>
        <button type="submit">Submit</button>
    </form>
    <hr>
    <h2>Login</h2>
    <form action="action.php" method="post">
        <input type="hidden" name="action" value="login">

        First name:<br>
        <input type="text" name="username"><br>
        Password:<br>
        <input type="text" name="password"><br>
        <br>
        <button type="submit">Submit</button>
    </form>
<?php else: ?>
    <h1>User is logged in</h1>
    <h2>Login info:</h2>
    Username: <b><?= Session::user()->username ?></b><br>
    First name: <b><?= Session::user()->first_name ?></b><br>
    Last name: <b><?= Session::user()->last_name ?></b><br>
    <hr>
    <h2>Logout</h2>
    <form action="action.php" method="post">
        <input type="hidden" name="action" value="logout">

        <button type="submit">Logout</button>
    </form>
<?php endif; ?>

