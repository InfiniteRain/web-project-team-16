<?php

require_once __DIR__ . '/../app/header.php';

use WebTech\Hospital\Models\User;
use WebTech\Hospital\Session;

switch ($_POST['action']) {
    case 'register':
        try {
            $newUser = new User();
            $newUser->username = $_POST['username'];
            $newUser->password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $newUser->first_name = $_POST['firstName'];
            $newUser->last_name = $_POST['lastName'];
            $newUser->email = $_POST['email'];
            $newUser->type = 1;
            $newUser->save();
        } catch (Exception $exception) {

        }

        break;
    case 'login':
        try {
            $user = User::where('username=?', [$_POST['username']]);

            if (!isset($user[0])) {
                break;
            }

            $user = $user[0];
            if (!password_verify($_POST['password'], $user->password)) {
                break;
            }

            Session::login($user);
        } catch (Exception $exception) {

        }

        break;
    case 'logout';
        try {
            Session::logout();
        } catch (Exception $exception) {

        }

        break;
    default:

        break;
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
die();
