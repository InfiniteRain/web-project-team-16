<?php

namespace WebTech\Hospital\Controllers;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use WebTech\Hospital\Controller;
use WebTech\Hospital\Models\User;
use WebTech\Hospital\Session;

/**
 * Login controller class.
 *
 * @author David Lõssenko <lysenkodavid@gmail.com>
 */
class LoginController extends Controller
{
    /**
     * Gets the login page.
     *
     * @param $request
     * @return string
     */
    public function pageLogin($request)
    {
        if (user()) {
            $this->redirect('/');
        }

        return $this->view('login', [
            'title' => 'Hospital | Login',
            'redirect' => isset($request['r']) ? $request['r'] : null
        ]);
    }

    /**
     * Logs a user in.
     *
     * @param $request
     * @throws \Exception
     */
    public function login($request)
    {
        if (user()) {
            $this->redirect('/');
        }

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
     * Logs a user out.
     *
     * @throws \Exception
     */
    public function logout()
    {
        if (!user()) {
            $this->redirect('/');
        }

        Session::logout();

        $this->redirect('/');
    }

    /**
     * Shows the register page.
     *
     * @param $request
     * @return string
     */
    public function pageRegister($request)
    {
        return $this->view('register', ['title' => 'Hospital | Register']);
    }

    /**
     * Registers a new user.
     *
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

        $this->redirectBack(['registerMsg' => 'Registration was successful.']);
    }

    /**
     * Shows the recovery page.
     *
     * @return string
     */
    public function pageForgot()
    {
        return $this->view('forgot');
    }

    /**
     * Sends to recovery E-Mail.
     *
     * @param $request
     * @throws \Exception
     */
    public function forgot($request)
    {
        if (user()) {
            $this->redirect('/');
        }

        $this->validate($request, [
            'email' => 'required|email'
        ]);

        $user = User::where('email=?', [$request['email']]);
        if (!isset($user[0])) {
            $this->redirectBack(['forgotMsg' => 'Recovery E-Mail sent.']);
        }

        $user = $user[0];
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+48 hours'));

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = CONFIG['mail_host'];
        $mail->Port = CONFIG['mail_port'];
        $mail->SMTPSecure = CONFIG['mail_prot'];
        $mail->SMTPAuth = true;
        $mail->Username = CONFIG['mail_username'];
        $mail->Password = CONFIG['mail_password'];

        $mail->setFrom(CONFIG['mail_username'], 'Webtech Team 16');
        $mail->addAddress($user->email);
        $mail->Subject = 'Forgot password';
        $mail->msgHTML('
            Follow this link to recover your account: 
            <br>
            http://' . $_SERVER['SERVER_NAME'] . '/forgot/' . $token
        );
        $mail->send();

        $user->recovery_token = $token;
        $user->recovery_expires = $expires;
        $user->save();

        $this->redirectBack(['forgotMsg' => 'Recovery E-Mail sent.']);
    }

    /**
     * Displays the recovery page.
     *
     * @param $request
     * @param $token
     * @return string
     * @throws Exception
     */
    public function pageRecover($request, $token)
    {
        if (count($user = User::where('recovery_token=?', [$token])) === 0) {
            throw new Exception('Incorrect token provided.');
        }

        $user = $user[0];

        if (time() > strtotime($user->recovery_expires)) {
            throw new Exception('Incorrect token provided.');
        }

        return $this->view('recover', ['token' => $token]);
    }

    /**
     * Changes password.
     *
     * @param $request
     * @param $token
     * @throws Exception
     * @throws \Exception
     */
    public function recover($request, $token)
    {
        if (count($user = User::where('recovery_token=?', [$token])) === 0) {
            throw new Exception('Incorrect token provided.');
        }

        $user = $user[0];

        if (time() > strtotime($user->recovery_expires)) {
            throw new Exception('Incorrect token provided.');
        }

        $this->validate($request, [
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ]);

        $user->password = password_hash($request['password'], PASSWORD_BCRYPT);
        $user->recovery_token = null;
        $user->recovery_expires = null;
        $user->save();

        $this->redirect('/');
    }
}
