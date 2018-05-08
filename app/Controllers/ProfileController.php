<?php

namespace WebTech\Hospital\Controllers;

use WebTech\Hospital\Controller;

class ProfileController extends Controller
{
    public function pageProfile()
    {
        return $this->view('profile');
    }

    public function profile()
    {

    }
}