<!doctype html>
<html>
<head>
    <!-- including header -->
    <?php require_once __DIR__ . '/shared/head.php'; ?>
</head>
<body>

<!-- including navbar -->
<?php require_once __DIR__ . '/shared/navbar.php'; ?>

<div class="login-box" style="height: 350px;">
    <img src="/assets/img/avatar.png" class="avatar">

    <h1>Forgot password:</h1>
    <?php if (isset($forgotMsg)): ?>
        <b style="color: lime;"><?= $forgotMsg ?></b>
        <br>
    <?php endif; ?>

    <br>
    <div>
        <form action="/forgot" method="POST">
            <label>
                E-Mail address:
                <?php if (v_has_error('email')): ?>
                    <br>
                    <span class="error"><?= v_get_error('email') ?></span><br>
                <?php endif; ?>
            </label>
            <input type="text" name="email" placeholder="Your E-Mail address">
            <input type="submit" name="submit" value="Send recovery E-Mail">
        </form>
    </div>

</div>

<!-- including footer -->
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<!-- including scripts -->
<?php require_once __DIR__ . '/shared/scripts.php'; ?>

</body>
</html>
