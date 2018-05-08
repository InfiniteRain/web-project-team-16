<!doctype html>
<html>
<head>
    <!-- including header -->
    <?php require_once __DIR__ . '/shared/head.php'; ?>
</head>
<body>

<!-- including navbar -->
<?php require_once __DIR__ . '/shared/navbar.php'; ?>

<div class="w3-content">
    <img class="mySlides" src="/assets/img/banner1.jpg" style="width:100%">
    <img class="mySlides" src="/assets/img/banner2.jpg" style="width:100%">
    <img class="mySlides" src="/assets/img/banner3.jpg" style="width:100%">
    <img class="mySlides" src="/assets/img/banner4.jpg" style="width:100%">
</div>

<div>
    <br><br>
    <div class="content">
        <h2>What do you want to do?</h2>
        <a href="/appointments/book" class="btn appointments" id="submission">Book an Appointment</a>
        <a href="/appointments/view" class="btn appointments">Show Appointments</a>
        <a href="/profile" class="btn profile">Edit Profile</a>
    </div>
</div>

<!-- including footer -->
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<!-- including scripts -->
<?php require_once __DIR__ . '/shared/scripts.php'; ?>

</body>
</html>
