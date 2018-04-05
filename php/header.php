<?php

// Starting the session.
session_start();

$_SESSION['userId'] = 1;

// Necessary includes.
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Session.php';
require_once __DIR__ . '/classes/User.php';

// Some constant definitions.
define('CONFIG', parse_ini_file('../config.ini'));

