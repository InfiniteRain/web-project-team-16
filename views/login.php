<!doctype html>
<html>
<head>
    <!-- including header -->
    <?php require_once __DIR__ . '/shared/head.php'; ?>
</head>
<body>

<!-- including navbar -->
<?php require_once __DIR__ . '/shared/navbar.php'; ?>

<div class="login-box">
    <img src="/assets/img/avatar.png" class="avatar">

    <h1>Login:</h1>
    <?php if (isset($loginError)): ?>
        <b style="color: pink;"><?= $loginError ?></b>
        <br>
    <?php endif; ?>

    <br>
    <div>
        <form action="/login" method="POST">
            <?php if ($redirect): ?>
                <input type="hidden" name="redirect" value="<?= $redirect ?>">
            <?php endif; ?>
            <label>Username:</label>
            <input type="text" name="username" placeholder="Your username">
            <label>Password: </label>
            <input type="password" name="password" placeholder="Your password">
            <input type="submit" name="submit" value="Login">
            <span class="psw">
                <a href="resetpassword.html">Forgot password?</a>
            </span>
        </form>
    </div>

</div>


<!-- including footer -->
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<!-- including scripts -->
<?php require_once __DIR__ . '/shared/scripts.php'; ?>

</body>
</html>
