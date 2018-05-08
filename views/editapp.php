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
    <?php if (isset($editMsg)): ?>
        <div style="width:100%; text-align: center;">
            <b style="color: lime;"><?= $editMsg ?></b><br>
        </div>
    <?php endif; ?>
    <h1>Appointment:</h1>
    <div>
        <form action="/appointments/<?= $app->id ?>/edit" method="post">

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
                    <option value="<?= $doctor->id ?>" <?php
                        if (old_val('doctor')) {
                            echo old_val('doctor') === $doctor->id ? 'selected' : '';
                        } else {
                            echo $app->appointmentDoctor()->id === $doctor->id ? 'selected' : '';
                        }
                    ?>>
                        <?= $doctor->first_name . ' ' . $doctor->last_name ?>
                        (<?= $doctor->userSpeciality()->name ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <?php
                $date = substr($app->datetime, 0, 10);
                $time = substr($app->datetime, 11, 5);
            ?>

            <label>
                Date:
                <?php if (v_has_error('date')): ?>
                    <br>
                    <span class="error"><?= v_get_error('date') ?></span><br>
                <?php endif; ?>
            </label>
            <input type="date" name="date" value="<?= old_val('date') ?: $date ?>">
            <label>
                Time:
                <?php if (v_has_error('time')): ?>
                    <br>
                    <span class="error"><?= v_get_error('time') ?></span><br>
                <?php endif; ?>
            </label>
            <input type="time" name="time" value="<?= old_val('time') ?: $time ?>">
            <input type="submit" value="Edit">
        </form>
    </div>
</div>

<!-- including footer -->
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<!-- including scripts -->
<?php require_once __DIR__ . '/shared/scripts.php'; ?>

</body>
</html>
