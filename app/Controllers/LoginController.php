<?php

namespace WebTech\Hospital\Controllers;

use WebTech\Hospital\Controller;
use WebTech\Hospital\Models\User;
use WebTech\Hospital\Session;

class LoginController extends Controller
{
    /**
     * @param $request
     * @return string
     */
    public function pageLogin($request)
    {
        return $this->view('login', [
            'title' => 'Hospital | Login',
            'redirect' => isset($request['r']) ? $request['r'] : null
        ]);
    }

    /**
     * @param $request
     * @throws \Exception
     */
    public function login($request)
    {


        $user = User::where('username=?', [$request['username']]);

        $msg = 'Wrong username/password.';
        if (!isset($user[0])) {
            $this->redirectBack(['loginError' => $msg]);
        }

        $user = $user[0];
        if (!password_verify($request['password'], $user->password)) {
            $this->redirectBack(['loginError' => $msg]);
        }

        Session::login($user);

        $this->redirect(isset($request['redirect']) ? $request['redirect'] : '/');
    }

    /**
     * @throws \Exception
     */
    public function logout()
    {
        Session::logout();

        $this->redirect('/');
    }

    public function pageRegister($request)
    {
        return $this->view('register', ['title' => 'Hospital | Register']);
    }

    /**
     * @param $request
     * @throws \Exception
     */
    public function register($request)
    {
        $this->validate($request, [
            'username' => 'required|min:3|max:20|alphanumeric|unique:SYSUSER,username',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
            'first_name' => 'required|alphanumeric',
            'last_name' => 'required|alphanumeric',
            'email' => 'required|email|unique:SYSUSER,email'
        ]);

        $newUser = new User();
        $newUser->username = trim($request['username']);
        $newUser->password = password_hash($request['password'], PASSWORD_BCRYPT);
        $newUser->first_name = trim($request['first_name']);
        $newUser->last_name = trim($request['last_name']);
        $newUser->email = trim($request['email']);
        $newUser->type = 3;
        $newUser->save();

        $this->redirectBack(['registerMsg' => "User {$newUser->username} was successfully registered."]);
    }
}