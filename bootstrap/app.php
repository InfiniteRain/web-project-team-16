<?php

// Starting the session.
session_start();

// Default timezone.
date_default_timezone_set('Europe/London');

// Some constant definitions.
define('CONFIG', parse_ini_file('../config.ini'));
