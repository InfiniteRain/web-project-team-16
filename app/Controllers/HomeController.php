<?php

namespace WebTech\Hospital\Controllers;

use WebTech\Hospital\Controller;

class HomeController extends Controller
{
    public function pageHome()
    {
        return $this->view('home', ['title' => 'Hospital | Home']);
    }

    public function pageContact()
    {
        return $this->view('contact', ['title' => 'Hospital | Contact']);
    }

    public function pageAbout()
    {
        return $this->view('about', ['title' => 'Hospital | About']);
    }
}
