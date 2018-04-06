<?php

// Starting the session.
session_start();

// Some constant definitions.
define('CONFIG', parse_ini_file('../config.ini'));

// TODO: REMOVE (FOR NOW ITS HERE FOR TESTING PURPOSES)
$_SESSION['userId'] = 1;

// Necessary includes.
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Session.php';

require_once __DIR__ . '/classes/models/User.php';

