<?php

namespace WebTech\Hospital;

use WebTech\Hospital\Models\User;

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
     * Returns the user of the current session, or false if there's none.
     *
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
            } catch (\Exception $exception) {
                return false;
            }
        }

        return self::$user;
    }

    /**
     * Logs the user in.
     *
     * @param User $user
     * @throws \Exception
     */
    public static function login(User $user)
    {
        if (session_status() == PHP_SESSION_NONE) {
            throw new \Exception('PHP session was not started.');
        }

        if (isset($_SESSION['userId'])) {
            throw new \Exception('PHP session already has a user.');
        }

        $_SESSION['userId'] = $user->id;
    }

    /**
     * Logs the user out.
     *
     * @throws \Exception
     */
    public static function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            throw new \Exception('PHP session was not started.');
        }

        if (!isset($_SESSION['userId'])) {
            throw new \Exception('PHP session has no user.');
        }

        $_SESSION['userId'] = null;
    }

    /**
     * Sets redirection session data.
     *
     * @param array $data
     */
    public static function setRedirectData(array $data)
    {
        $_SESSION['redirect'] = $data;
    }

    /**
     * Gets redirection session data.
     *
     * @return array
     */
    public static function getRedirectData()
    {
        return isset($_SESSION['redirect']) ? $_SESSION['redirect'] : [];
    }
}
