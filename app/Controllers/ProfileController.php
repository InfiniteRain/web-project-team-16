<?php

namespace WebTech\Hospital\Controllers;

use WebTech\Hospital\Controller;
use WebTech\Hospital\Models\User;

/**
 * Profile controller class.
 *
 * @author Xanthe Hammer
 */
class ProfileController extends Controller
{
    /**
     * Displays the profile page.
     *
     * @return string
     */
    public function pageProfile()
    {
        if (!user()) {
            $this->redirect('/');
        }

        return $this->view('profile');
    }

    /**
     * Edits the profile.
     *
     * @param $request
     * @throws \Exception
     */
    public function editProfile($request)
    {
        if (!user()) {
            $this->redirect('/');
        }

        $this->validate($request, [
            'username' => 'min:3|max:20|alphanumeric',
            'password' => 'min:8',
            'password_confirmation' => 'same:password',
            'first_name' => 'alphanumeric',
            'last_name' => 'alphanumeric',
            'email' => 'email'
        ]);

        $user = user();

        $errors = [];
        if (!empty($request['username'])) {
            $list = User::where('id != ? AND username = ?', [$user->id, $request['username']]);
            if (count($list) > 0) {
                $errors['username'][0] = 'Value in the field username is already taken.';
            }

            $user->username = $request['username'];
        }

        if (!empty($request['first_name'])) {
            $user->first_name = $request['first_name'];
        }

        if (!empty($request['last_name'])) {
            $user->last_name = $request['last_name'];
        }

        if (!empty($request['email'])) {
            $list = User::where('id != ? AND email = ?', [$user->id, $request['email']]);
            if (count($list) > 0) {
                $errors['email'][0] = 'Value in the field email is already taken.';
            }

            $user->email = $request['email'];
        }

        if (!empty($request['password'])) {
            $user->password = password_hash($request['password'], PASSWORD_BCRYPT);
        }

        if (count($errors) > 0) {
            $this->redirectBack(['validationErrors' => $errors]);
        } else {
            $user->save();
            $this->redirectBack(['profileMsg' => 'Profile was successfully edited!']);
        }
    }
}
