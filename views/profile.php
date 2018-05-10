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

    <h1>Edit Profile:</h1>
    <br>
    <div>

        <form action="/profile" method="post">
            <div class="cont">
                <label>
                    First name:
                </label>
                <input type="text" name="first_name" value=<?= user()->first_name?>
                       placeholder="First Name">
            </div>
            <div class="cont">
                <label>
                    Last name:
                </label>
                <input type="text" name="last_name" value=<?= user()->last_name?>
                placeholder="Last Name">
            </div>
            <div class="cont">
                <label>
                    Username:
                </label>
                <input type="text" name="username" value=<?= user()->username?>
                       placeholder="Username">
            </div>
            <div class="cont">
                <label>
                    E-Mail:
                </label>
                <input type="text" name="email" value=<?= user()->email?>
                       placeholder="Email">
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