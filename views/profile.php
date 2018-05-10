<!doctype html>
<html>
<head>
    <!-- including header -->
    <?php require_once __DIR__ . '/shared/head.php'; ?>
</head>
<body>

<!-- including navbar -->
<?php require_once __DIR__ . '/shared/navbar.php'; ?>

<div class="register-box">
    <img src="/assets/img/avatar.png" class="avatar">

    <?php if (isset($profileMsg)): ?>
        <div style="width:100%; text-align: center;">
            <b style="color: lime;"><?= $profileMsg ?></b>
        </div>
    <?php endif; ?>

    <h1>Edit Profile:</h1>
    <br>
    <div>

        <form action="/profile" method="post">
            <div class="cont">
                <label>
                    First name:
                    <?php if (v_has_error('first_name')): ?>
                        <br>
                        <span class="error"><?= v_get_error('first_name') ?></span><br>
                    <?php endif; ?>
                </label>
                <input type="text" name="first_name" value="<?= old_val('first_name') ?>"
                       placeholder="<?= user()->first_name ?>">
            </div>
            <div class="cont">
                <label>
                    Last name:
                    <?php if (v_has_error('last_name')): ?>
                        <br>
                        <span class="error"><?= v_get_error('last_name') ?></span><br>
                    <?php endif; ?>
                </label>
                <input type="text" name="last_name" value="<?= old_val('last_name') ?>"
                       placeholder="<?= user()->last_name ?>">
            </div>
            <div class="cont">
                <label>
                    Username:
                    <?php if (v_has_error('username')): ?>
                        <br>
                        <span class="error"><?= v_get_error('username') ?></span><br>
                    <?php endif; ?>
                </label>
                <input type="text" name="username" value="<?= old_val('username') ?>"
                       placeholder="<?= user()->username ?>">
            </div>
            <div class="cont">
                <label>
                    E-Mail:
                    <?php if (v_has_error('email')): ?>
                        <br>
                        <span class="error"><?= v_get_error('email') ?></span><br>
                    <?php endif; ?>
                </label>
                <input type="text" name="email" value="<?= old_val('email') ?>"
                       placeholder="<?= user()->email ?>">
            </div>
            <div class="cont">
                <label>
                    Password:
                    <?php if (v_has_error('password')): ?>
                        <br>
                        <span class="error"><?= v_get_error('password') ?></span><br>
                    <?php endif; ?>
                </label>
                <input type="password" name="password">
            </div>
            <div class="cont">
                <label>
                    Repeat password:
                    <?php if (v_has_error('password_confirmation')): ?>
                        <br>
                        <span class="error"><?= v_get_error('password_confirmation') ?></span><br>
                    <?php endif; ?>
                </label>
                <input type="password" name="password_confirmation">
            </div>

            <input type="submit" name="submit" value="Confirm">
        </form>
    </div>
</div>

<!-- including footer -->
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<!-- including scripts -->
<?php require_once __DIR__ . '/shared/scripts.php'; ?>

</body>
</html>