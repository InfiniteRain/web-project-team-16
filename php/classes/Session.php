<?php

require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/Database.php';

class Session
{
    /**
     * @var User
     */
    private static $user;

    public static function user()
    {
        if (session_status() == PHP_SESSION_NONE || !isset($_SESSION['userId'])) {
            return false;
        }

        if (!isset(self::$user)) {
            try {
                self::$user = User::find($_SESSION['userId']);
            } catch (Exception $exception) {
                return false;
            }
        }

        return self::$user;
    }
}
