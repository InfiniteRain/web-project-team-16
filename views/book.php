<!doctype html>
<html>
<head>
    <!-- including header -->
    <?php require_once __DIR__ . '/shared/head.php'; ?>
</head>
<body>

<!-- including navbar -->
<?php require_once __DIR__ . '/shared/navbar.php'; ?>

<div class="appointment-box">
    <?php if (isset($appointmentMsg)): ?>
        <div style="width:100%; text-align: center;">
            <b style="color: lime;"><?= $appointmentMsg ?></b><br>
        </div>
    <?php endif; ?>
    <h1>Appointment:</h1>
    <div>
        <form action="/appointments/book" method="post">

            <label>
                Doctor:
                <?php if (v_has_error('doctor')): ?>
                    <br>
                    <span class="error"><?= v_get_error('doctor') ?></span><br>
                <?php endif; ?>
            </label>
            <select name="doctor">
                <option value="">-</option>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?= $doctor->id ?>" <?= old_val('doctor') === $doctor->id ? 'selected' : '' ?>>
                        <?= $doctor->first_name . ' ' . $doctor->last_name ?>
                        (<?= $doctor->userSpeciality()->name ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>
                Date:
                <?php if (v_has_error('date')): ?>
                    <br>
                    <span class="error"><?= v_get_error('date') ?></span><br>
                <?php endif; ?>
            </label>
            <input type="date" name="date" value="<?= old_val('date') ?>">
            <label>
                Time:
                <?php if (v_has_error('time')): ?>
                    <br>
                    <span class="error"><?= v_get_error('time') ?></span><br>
                <?php endif; ?>
            </label>
            <input type="time" name="time" value="<?= old_val('time') ?>">
            <input type="submit" value="Book">
        </form>
    </div>
</div>

<!-- including footer -->
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<!-- including scripts -->
<?php require_once __DIR__ . '/shared/scripts.php'; ?>

</body>
</html>
