<!doctype html>
<html>
<head>
    <!-- including header -->
    <?php require_once __DIR__ . '/shared/head.php'; ?>
</head>
<body>

<!-- including navbar -->
<?php require_once __DIR__ . '/shared/navbar.php'; ?>

<br>
<div class="title-box">
    <h1 class="h1-no-center" style="display: inline;">Appointments</h1>
    <div class="right">
        <form action="/appointments/view" method="get">
            <label for="datepicker">Day:</label>
            <input id="datepicker" type="text" name="dt" value="<?= $dt ?>">
            <label for="f">Filter:</label>
            <input id="f" type="text" name="f" value="<?= $f ?>">
            <label for="d">Decision:</label>
            <select id="d" name="d">
                <option value="any" <?= $d == 'any' ? 'selected' : '' ?>>Any</option>
                <option value="approved" <?= $d == 'approved' ? 'selected' : '' ?>>Approved</option>
                <option value="declined" <?= $d == 'declined' ? 'selected' : '' ?>>Declined</option>
                <option value="undecided" <?= $d == 'undecided' ? 'selected' : '' ?>>Undecided</option>
            </select>
            <label for="c">Cancelled:</label>
            <select id="c" name="c">
                <option value="any" <?= $c == 'any' ? 'selected' : '' ?>>Any</option>
                <option value="yes" <?= $c == 'yes' ? 'selected' : '' ?>>Cancelled</option>
                <option value="no" <?= $c == 'no' ? 'selected' : '' ?>>Not Cancelled</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>
</div>

<div class="appointments-box">
    <table>
        <thead>
        <tr>
            <?php if ($ut === 'admin'): ?>
                <th>Doctor's name</th>
                <th>Patient's name</th>
            <?php elseif ($ut === 'doctor'): ?>
                <th>Patient's name</th>
            <?php else: ?>
                <th>Doctor's name</th>
            <?php endif; ?>
            <th>Date and time</th>
            <th>Approved</th>
            <th>Cancelled</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($apps as $app): ?>
            <tr
                <?php if (!$app->decision_made && !$app->cancelled): ?>
                    style="background: #ffc107;"
                <?php elseif ($app->cancelled || !$app->approved): ?>
                    style="background: #dc3545;"
                <?php elseif ($app->approved): ?>
                    style="background: #28a745;"
                <?php endif; ?>
            >
                <?php if ($ut === 'admin'): ?>
                    <td><?= $app->appointmentDoctor()->first_name . ' ' . $app->appointmentDoctor()->last_name ?></td>
                    <td><?= $app->appointmentPatient()->first_name . ' ' . $app->appointmentPatient()->last_name ?></td>
                <?php elseif ($ut === 'doctor'): ?>
                    <td><?= $app->appointmentPatient()->first_name . ' ' . $app->appointmentPatient()->last_name ?></td>
                <?php else: ?>
                    <td><?= $app->appointmentDoctor()->first_name . ' ' . $app->appointmentDoctor()->last_name ?></td>
                <?php endif; ?>
                <td>
                <?php
                    $dt = $app->datetime;
                    $y = substr($dt, 0, 4);
                    $m = substr($dt, 5, 2);
                    $d = substr($dt, 8, 2);
                    echo "$m/$d/$y";
                ?>
                </td>
                <td>
                    <?php if ($app->decision_made): ?>
                        <?= $app->approved ? 'Yes' : 'No' ?>
                    <?php else: ?>
                        Decision Not Made
                    <?php endif; ?>
                </td>
                <td><?= $app->cancelled ? 'Yes' : 'No' ?></td>
                <td style="text-align: center;">
                    <?php if ($ut === 'admin'): ?>
                        <?php if (!$app->cancelled && !($app->decision_made && !$app->approved)): ?>
                            <?php if (!$app->decision_made): ?>
                                <a href="/appointments/<?= $app->id ?>/approve">Approve</a> |
                                <a href="/appointments/<?= $app->id ?>/decline">Decline</a> |
                            <?php endif; ?>
                            <a href="/appointments/<?= $app->id ?>/edit">Edit</a> |
                            <a href="/appointments/<?= $app->id ?>/cancel">Cancel</a>
                        <?php else: ?>
                            <b>N/A</b>
                        <?php endif; ?>
                    <?php elseif ($ut === 'doctor'): ?>
                        <?php if (!$app->decision_made): ?>
                            <a href="/appointments/<?= $app->id ?>/approve">Approve</a> |
                            <a href="/appointments/<?= $app->id ?>/decline">Decline</a>
                        <?php else: ?>
                            <b>N/A</b>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if (!$app->cancelled && !($app->decision_made && !$app->approved)): ?>
                            <a href="/appointments/<?= $app->id ?>/edit">Edit</a> |
                            <a href="/appointments/<?= $app->id ?>/cancel">Cancel</a>
                        <?php else: ?>
                            <b>N/A</b>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- including footer -->
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<!-- including scripts -->
<?php require_once __DIR__ . '/shared/scripts.php'; ?>

</body>
</html>
