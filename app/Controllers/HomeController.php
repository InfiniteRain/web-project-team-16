<?php

namespace WebTech\Hospital\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use WebTech\Hospital\Controller;

/**
 * Home controller class.
 *
 * @author David Lõssenko <lysenkodavid@gmail.com>
 */
class HomeController extends Controller
{
    /**
     * Gets home page.
     *
     * @return string
     */
    public function pageHome()
    {
        return $this->view('home', ['title' => 'Hospital | Home']);
    }

    /**
     * Gets contact page.
     *
     * @return string
     */
    public function pageContact()
    {
        return $this->view('contact', ['title' => 'Hospital | Contact']);
    }

    /**
     * Sends the contact message.
     *
     * @param $request
     * @throws \Exception
     */
    public function contact($request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = CONFIG['mail_host'];
        $mail->Port = CONFIG['mail_port'];
        $mail->SMTPSecure = CONFIG['mail_prot'];
        $mail->SMTPAuth = true;
        $mail->Username = CONFIG['mail_username'];
        $mail->Password = CONFIG['mail_password'];

        $mail->setFrom(CONFIG['mail_username']);
        $mail->addAddress(CONFIG['mail_username']);
        $mail->Subject = 'Contact - ' . $request['name'];
        $mail->msgHTML("
            <b>Name</b>: {$request['name']}<br>
            <b>E-Mail</b>: {$request['email']}<br>
            <b>Message</b>: {$request['message']}
        ");
        $mail->send();

        $this->redirectBack();
    }

    /**
     * Gets about page.
     *
     * @return string
     */
    public function pageAbout()
    {
        return $this->view('about', ['title' => 'Hospital | About']);
    }
}
