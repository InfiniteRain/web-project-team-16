<?php

namespace WebTech\Hospital\Controllers;

use WebTech\Hospital\Controller;

class AppointmentController extends Controller
{
    public function pageBookAppointment()
    {
        if (!user()) {
            $this->redirect('/login?r=' . urlencode('/appointments/book'));
        }
    }

    public function pageViewAppointments()
    {
        if (!user()) {
            $this->redirect('/login?r=' . urlencode('/appointments/view'));
        }
    }
}