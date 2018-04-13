<?php

namespace WebTech\Hospital\Controllers;

use WebTech\Hospital\Controller;

class HomeController extends Controller
{
    public function getHomePage($request)
    {
        return $this->view('front', []);
    }
}
