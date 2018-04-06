<?php

require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/Database.php';

/**
 * Session class is used for handling sessions of visitors, primarily the user information.
 *
 * @author David Lõssenko <lysenkodavid@gmail.com>
 */
class Session
{
    /**
     * @var User
     */
    private static $user;

    /**
     * @return bool|User
     */
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


    public static function login(User $user)
    {

    }

    public static function logout()
    {

    }
}
