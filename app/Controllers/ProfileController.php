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
            $this->redirect('/login?r=' . urlencode('/profile'));
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

    /**
     * @return string
     * @throws \Exception
     */
    public function pageUsers($request)
    {
        if (user()->userType()->name !== 'admin') {
            $this->redirect('/');
        }

        $users = User::where('1=1');

        $filtered = [];
        foreach ($users as $user) {
            $failed = false;

            if (!empty($request['f'])) {
                $f = strtolower($request['f']);
                $un = strtolower($user->username);
                $fn = strtolower($user->first_name);
                $ln = strtolower($user->last_name);

                if (
                    strpos($un, $f) === false &&
                    strpos($fn . ' ' . $ln, $f) === false
                ) {
                    $failed = true;
                }
            }

            if (!empty($request['t'])) {
                switch ($request['t']) {
                    case 'admin':
                        if ($user->type !== 1) {
                            $failed = true;
                        }

                        break;
                    case 'doctor':
                        if ($user->type !== 2) {
                            $failed = true;
                        }

                        break;
                    case 'patient':
                        if ($user->type !== 3) {
                            $failed = true;
                        }

                        break;
                }
            }

            if (!$failed) {
                $filtered[] = $user;
            }
        }
        
        return $this->view('users', [
            'users' => $filtered
        ]);
    }

    /**
     * Open another user's profile page.
     *
     * @throws \Exception
     */
    public function pageAdminProfile($request, $id)
    {
        if (user()->userType()->name !== 'admin') {
            $this->redirect('/');
        }

        $user = User::find($id);

        return $this->view('adminprofile', [
            'user' => $user
        ]);
    }

    /**
     * Edit a profile of another user.
     *
     * @param $request
     * @param $id
     * @throws \Exception
     */
    public function editAdminProfile($request, $id)
    {
        if (user()->userType()->name !== 'admin') {
            $this->redirect('/');
        }

        $user = User::find($id);

        $this->validate($request, [
            'username' => 'min:3|max:20|alphanumeric',
            'password' => 'min:8',
            'password_confirmation' => 'same:password',
            'first_name' => 'alphanumeric',
            'last_name' => 'alphanumeric',
            'email' => 'email'
        ]);

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

        if (!empty($request['type'])) {
            if ($request['type'] < 1 || $request['type'] > 3) {
                $errors['type'][0] = 'Value has to be 1, 2 or 3.';
            }

            $user->type = $request['type'];
        }

        if (count($errors) > 0) {
            $this->redirectBack(['validationErrors' => $errors]);
        } else {
            $user->save();
            $this->redirectBack(['profileMsg' => 'Profile was successfully edited!']);
        }
    }
}
