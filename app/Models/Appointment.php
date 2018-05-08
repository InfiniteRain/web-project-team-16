<?php

namespace WebTech\Hospital\Models;

use WebTech\Hospital\Model;

/**
 * Appointment model.
 *
 * @author David Lõssenko <lysenkodavid@gmail.com>
 */
class Appointment extends Model
{
    /**
     * @var string Name of the table.
     */
    protected static $table = 'APPOINTMENT';

    /**
     * @var array List of column names.
     */
    protected static $columns = [
        'id',
        'patient',
        'doctor',
        'approved',
        'decision_made',
        'datetime',
        'cancelled'
    ];

    /**
     * @var string Name of the primary key column.
     */
    protected static $primaryKey = 'id';

    /**
     * @var User|null
     */
    private $appointmentDoctor;

    /**
     * @var User|null
     */
    private $appointmentPatient;

    /**
     * Returns the doctor model of the appointment.
     *
     * @return User
     * @throws \Exception
     */
    public function appointmentDoctor()
    {
        if (!isset($this->appointmentDoctor)) {
            $this->appointmentDoctor = User::find($this->doctor);
        }

        return $this->appointmentDoctor;
    }

    /**
     * Returns the patient model of the appointment.
     *
     * @return User
     * @throws \Exception
     */
    public function appointmentPatient()
    {
        if (!isset($this->appointmentPatient)) {
            $this->appointmentPatient = User::find($this->patient);
        }

        return $this->appointmentPatient;
    }
}
