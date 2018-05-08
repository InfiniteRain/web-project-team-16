<?php

namespace WebTech\Hospital\Controllers;

use WebTech\Hospital\Controller;
use WebTech\Hospital\Models\Appointment;
use WebTech\Hospital\Models\User;

/**
 * Appointment controller class.
 *
 * @author David Lõssenko <lysenkodavid@gmail.com>
 */
class AppointmentController extends Controller
{
    /**
     * Gets the book appointment page.
     *
     * @return string
     * @throws \Exception
     */
    public function pageBookAppointment()
    {
        if (!user()) {
            $this->redirect('/login?r=' . urlencode('/appointments/book'));
        }

        if (user()->userType()->name !== 'patient') {
            $this->redirect('/');
        }

        return $this->view('book', [
            'doctors' => User::where('type=2')
        ]);
    }

    /**
     * Books an appointment.
     *
     * @param $request
     * @throws \Exception
     */
    public function bookAppointment($request)
    {
        if (!user() || user()->userType()->name !== 'patient') {
            $this->redirect('/');
        }

        $this->validate($request, [
            'doctor' => 'required|int',
            'date' => 'required|date',
            'time' => 'required|time'
        ]);

        if (count(User::where('id=? and type=2', [$request['doctor']])) === 0) {
            throw new \Exception("Doctor with id {$request['doctor']} does not exist.");
        }

        $app = new Appointment();
        $app->patient = user()->id;
        $app->doctor = $request['doctor'];
        $app->approved = 0;
        $app->decision_made = 0;
        $app->datetime = $request['date'] . ' ' . $request['time'] . ':00';
        $app->cancelled = 0;
        $app->save();

        $this->redirectBack(['appointmentMsg' => 'Appointment was booked.']);
    }

    /**
     * Gets the view appointments page.
     *
     * @param $request
     * @return string
     * @throws \Exception
     */
    public function pageViewAppointments($request)
    {
        if (!user()) {
            $this->redirect('/login?r=' . urlencode('/appointments/view'));
        }

        $apps = [];
        switch (user()->userType()->name) {
            case 'admin':
                $apps = Appointment::where('1=1');
                break;
            case 'doctor':
                $apps = Appointment::where('doctor=?', [user()->id]);
                break;
            case 'patient':
                $apps = Appointment::where('patient=?', [user()->id]);
                break;
        }

        $filtered = [];
        foreach ($apps as $app) {
            $failed = false;
            if (!empty($request['f'])) {
                $f = strtolower($request['f']);
                $dfn = strtolower($app->appointmentDoctor()->first_name);
                $dln = strtolower($app->appointmentDoctor()->last_name);
                $pfn = strtolower($app->appointmentPatient()->first_name);
                $pln = strtolower($app->appointmentPatient()->last_name);

                switch (user()->userType()->name) {
                    case 'admin':
                        if (
                            strpos($dfn, $f) === false &&
                            strpos($dln, $f) === false &&
                            strpos($pfn, $f) === false &&
                            strpos($pln, $f) === false
                        ) {
                            $failed = true;
                        }

                        break;
                    case 'doctor':
                        if (
                            strpos($pfn, $f) === false &&
                            strpos($pln, $f) === false
                        ) {
                            $failed = true;
                        }

                        break;
                    case 'patient':
                        if (
                            strpos($dfn, $f) === false &&
                            strpos($dln, $f) === false
                        ) {
                            $failed = true;
                        }

                        break;
                }
            }

            if (!empty($request['d'])) {
                switch ($request['d']) {
                    case 'approved':
                        if (!($app->decision_made && $app->approved)) {
                            $failed = true;
                        }

                        break;
                    case 'declined':
                        if (!($app->decision_made && !$app->approved)) {
                            $failed = true;
                        }

                        break;
                    case 'undecided':
                        if ($app->decision_made) {
                            $failed = true;
                        }
                }
            }

            if (!empty($request['c'])) {
                switch ($request['c']) {
                    case 'yes':
                        if (!$app->cancelled) {
                            $failed = true;
                        }

                        break;
                    case 'no':
                        if ($app->cancelled) {
                            $failed = true;
                        }
                }
            }

            if (!$failed) {
                $filtered[] = $app;
            }
        }

        return $this->view('apps', [
            'apps' => $filtered,
            'ut' => user()->userType()->name,
            'f' => isset($request['f']) ? $request['f'] : '',
            'd' => isset($request['d']) ? $request['d'] : '',
            'c' => isset($request['c']) ? $request['c'] : '',
        ]);
    }

    /**
     * Approves an appointment.
     *
     * @param $request
     * @param $id
     * @throws \Exception
     */
    public function approve($request, $id)
    {
        if (!user()) {
            $this->redirect('/');
        }

        $app = Appointment::find($id);

        if ($app->decision_made || $app->cancelled) {
            throw new \Exception('Action not permitted.');
        }

        if (user()->userType()->name !== 'admin'
            && !(user()->userType()->name === 'doctor' && $app->appointmentDoctor()->id === user()->id)) {
            throw new \Exception('Action not permitted.');
        }

        $app->decision_made = 1;
        $app->approved = 1;
        $app->save();

        $this->redirectBack();
    }

    /**
     * Declines an appointment.
     *
     * @param $request
     * @param $id
     * @throws \Exception
     */
    public function decline($request, $id)
    {
        if (!user()) {
            $this->redirect('/');
        }

        $app = Appointment::find($id);

        if ($app->decision_made || $app->cancelled) {
            throw new \Exception('Action not permitted.');
        }

        if (user()->userType()->name !== 'admin'
            && !(user()->userType()->name === 'doctor' && $app->appointmentDoctor()->id === user()->id)) {
            throw new \Exception('Action not permitted.');
        }

        $app->decision_made = 1;
        $app->approved = 0;
        $app->save();

        $this->redirectBack();
    }

    /**
     * Gets the edit appointment page.
     *
     * @param $request
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function pageEdit($request, $id)
    {
        if (!user()) {
            $this->redirect('/');
        }

        $app = Appointment::find($id);

        if ($app->cancelled || ($app->decision_made && !$app->approved)) {
            throw new \Exception('Action not permitted.');
        }

        if (user()->userType()->name !== 'admin'
            && !(user()->userType()->name === 'patient' && $app->appointmentPatient()->id === user()->id)) {
            throw new \Exception('Action not permitted.');
        }

        return $this->view('editapp', [
            'doctors' => User::where('type=2'),
            'app' => $app
        ]);
    }

    /**
     * Edits an appointment.
     *
     * @param $request
     * @param $id
     * @throws \Exception
     */
    public function edit($request, $id)
    {
        if (!user()) {
            $this->redirect('/');
        }

        $app = Appointment::find($id);

        if ($app->cancelled || ($app->decision_made && !$app->approved)) {
            throw new \Exception('Action not permitted.');
        }

        if (user()->userType()->name !== 'admin'
            && !(user()->userType()->name === 'patient' && $app->appointmentPatient()->id === user()->id)) {
            throw new \Exception('Action not permitted.');
        }

        $this->validate($request, [
            'doctor' => 'required|int',
            'date' => 'required|date',
            'time' => 'required|time'
        ]);

        if (count(User::where('id=? and type=2', [$request['doctor']])) === 0) {
            throw new \Exception("Doctor with id {$request['doctor']} does not exist.");
        }

        $app->doctor = $request['doctor'];
        $app->datetime = $request['date'] . ' ' . $request['time'] . ':00';
        $app->save();

        $this->redirectBack([
            'editMsg' => 'Appointment edited.'
        ]);
    }

    /**
     * Cancels an appointment.
     *
     * @param $request
     * @param $id
     * @throws \Exception
     */
    public function cancel($request, $id)
    {
        if (!user()) {
            $this->redirect('/');
        }

        $app = Appointment::find($id);

        if ($app->cancelled || ($app->decision_made && !$app->approved)) {
            throw new \Exception('Action not permitted.');
        }

        if (user()->userType()->name !== 'admin'
            && !(user()->userType()->name === 'patient' && $app->appointmentPatient()->id === user()->id)) {
            throw new \Exception('Action not permitted.');
        }

        $app->cancelled = 1;
        $app->save();

        $this->redirectBack();
    }
}
