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
                <input type="text" name="first_name" value=""
                       placeholder="Your first name">
            </div>
            <div class="cont">
                <label>
                    Last name:
                </label>
                <input type="text" name="last_name" value=""
                       placeholder="Your last name">
            </div>
            <div class="cont">
                <label>
                    Username:
                </label>
                <input type="text" name="username" value=""
                       placeholder="Your username">
            </div>
            <div class="cont">
                <label>
                    E-Mail:
                </label>
                <input type="text" name="email" value=""
                       placeholder="Your E-Mail address">
            </div>
            <div class="cont">
                <label>
                    Password:
                </label>
                <input type="text" name="password" placeholder="Your password">
            </div>
            <div class="cont">
                <label>
                    Repeat password:
                </label>
                <input type="text" name="password_confirmation" placeholder="Repeat your password">
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