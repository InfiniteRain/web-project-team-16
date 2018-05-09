<!doctype html>
<html>
<head>
    <!-- including header -->
    <?php require_once __DIR__ . '/shared/head.php'; ?>
</head>
<body>

<!-- including navbar -->
<?php require_once __DIR__ . '/shared/navbar.php'; ?>

<div class="login-box" style="height: 430px;">
    <img src="/assets/img/avatar.png" class="avatar">

    <h1>Change password:</h1>

    <br>
    <div>
        <form action="/forgot/<?= $token ?>" method="POST">
            <label>
                Password:
                <?php if (v_has_error('password')): ?>
                    <br>
                    <span class="error"><?= v_get_error('password') ?></span><br>
                <?php endif; ?>
            </label>
            <input type="password" name="password">
            <label>
                Repeat password:
                <?php if (v_has_error('password_confirmation')): ?>
                    <br>
                    <span class="error"><?= v_get_error('password_confirmation') ?></span><br>
                <?php endif; ?>
            </label>
            <input type="password" name="password_confirmation">
            <input type="submit" name="submit" value="Change password">
        </form>
    </div>

</div>

<!-- including footer -->
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<!-- including scripts -->
<?php require_once __DIR__ . '/shared/scripts.php'; ?>

</body>
</html>
