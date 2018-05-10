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
    <div class="container" id="contact-form">
        <h1>Contact us</h1>
        <form action="#">
            <label>Name</label>
            <input type="text"  name="firstname" placeholder="Your name..">

            <label>Email</label>
            <input type="text" name="Email" placeholder="Your Email..">

            <label>Message</label>
            <textarea name="Message" placeholder="Write something.." style="height:200px"></textarea>

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
