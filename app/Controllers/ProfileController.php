<?php

namespace WebTech\Hospital\Controllers;

use WebTech\Hospital\Controller;

class ProfileController extends Controller
{
    public function pageProfile()
    {
        return $this->view('profile');
    }

    /**
     * @param $request
     * @throws \Exception
     */
    public function editProfile($request)
    {

        $user = user();

        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->username = $request['username'];
        $user->email = $request['email'];

        $user->save();

        $this->redirectBack();

    }
}