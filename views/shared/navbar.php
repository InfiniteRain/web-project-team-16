<ul>
    <li><a href="/">Home</a></li>
    <li><a href="/contact">Contact us</a></li>
    <li><a href="/about">About us</a></li>
    <?php if (!user()): ?>
        <li class="right"><a href="/login">Login</a></li>
        <li class="right"><a href="/register">Register</a></li>
    <?php else: ?>
        <li class="right"><a href="/logout">Logout</a></li>
        <li class="right"><a href="/profile">Profile - <?= user()->first_name . ' ' . user()->last_name ?></a></li>
        <?php if (user()->userType()->name === 'admin'): ?>
            <li class="right"><a href="/users">Admin - Users</a></li>
            <li class="right"><a href="/appointments/view">Admin - Appointments</a></li>
        <?php endif; ?>
    <?php endif; ?>
</ul>