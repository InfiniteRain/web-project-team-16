<!doctype html>
<html>
<head>
    <!-- including header -->
    <?php require_once __DIR__ . '/shared/head.php'; ?>
</head>
<body>

<!-- including navbar -->
<?php require_once __DIR__ . '/shared/navbar.php'; ?>

<div>
    <div class="container">
        <form action="#">
            <label>Name</label>
            <input type="text" name="name" placeholder="Your name">

            <label>Email</label>
            <input type="text" name="email" placeholder="Your E-Mail address">

            <label>Message</label>
            <textarea name="message" placeholder="Your message" rows="20"></textarea>

            <input type="submit" value="SEND MESSAGE">
        </form>
    </div>
</div>

<!-- including footer -->
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<!-- including scripts -->
<?php require_once __DIR__ . '/shared/scripts.php'; ?>

</body>
</html>
