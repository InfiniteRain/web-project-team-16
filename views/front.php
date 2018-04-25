<?php if (!user()): ?>
    <h1>User is not logged in</h1>
    <?php if (isset($registerMsg)): ?>
        <b style="color: green"><?= $registerMsg ?></b>
    <?php endif; ?>
    <h2>Registration</h2>
    <form action="/register" method="post">
        First name:<br>
        <input type="text" name="first_name" value="<?= old_val('first_name') ?>"><br>
        <?php if (v_has_error('first_name')): ?>
            <b style="color: red;"><?= v_get_error('first_name') ?></b><br>
        <?php endif; ?>
        Last name:<br>
        <input type="text" name="last_name" value="<?= old_val('last_name') ?>"><br>
        <?php if (v_has_error('last_name')): ?>
            <b style="color: red;"><?= v_get_error('last_name') ?></b><br>
        <?php endif; ?>
        E-Mail address:<br>
        <input type="text" name="email" value="<?= old_val('email') ?>"><br>
        <?php if (v_has_error('email')): ?>
            <b style="color: red;"><?= v_get_error('email') ?></b><br>
        <?php endif; ?>
        Username:<br>
        <input type="text" name="username" value="<?= old_val('username') ?>"><br>
        <?php if (v_has_error('username')): ?>
            <b style="color: red;"><?= v_get_error('username') ?></b><br>
        <?php endif; ?>
        Password:<br>
        <input type="text" name="password"><br>
        <?php if (v_has_error('password')): ?>
            <b style="color: red;"><?= v_get_error('password') ?></b><br>
        <?php endif; ?>
        Repeat password:<br>
        <input type="text" name="password_confirmation"><br>
        <?php if (v_has_error('password_confirmation')): ?>
            <b style="color: red;"><?= v_get_error('password_confirmation') ?></b><br>
        <?php endif; ?>
        <br>
        <button type="submit">Submit</button>
    </form>
    <hr>
    <h2>Login</h2>
    <?php if (isset($loginError)): ?>
        <h3 style="color: red;"><?= $loginError ?></h3>
    <?php endif; ?>

    <form action="/login" method="post">
        First name:<br>
        <input type="text" name="username"><br>
        Password:<br>
        <input type="text" name="password"><br>
        <br>
        <button type="submit">Submit</button>
    </form>
<?php else: ?>
    <h1>User is logged in</h1>
    <h2>Login info:</h2>
    Username: <b><?= user()->username ?></b><br>
    First name: <b><?= user()->first_name ?></b><br>
    Last name: <b><?= user()->last_name ?></b><br>
    Type: <b><?= user()->userType()->name ?></b><br>
    Specialty: <b><?= user()->userSpeciality() !== null ? user()->userSpeciality()->name : 'null' ?></b><br>

    <?php if (user()->userType()->name === 'patient'): ?>
        <b>Appointments:</b><br>
        <ul>
        <?php foreach (user()->appointmentsWithDoctors() as $appointment): ?>
            <li>
                Appointment on
                <?= $appointment->datetime ?> with Dr.
                <?= $appointment->appointmentDoctor()->last_name ?>.
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <hr>
    <h2>Logout</h2>
    <form action="/logout" method="get">
        <button type="submit">Logout</button>
    </form>
<?php endif; ?>
