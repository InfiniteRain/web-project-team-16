<?php

namespace WebTech\Hospital\Models;

use WebTech\Hospital\Model;

/**
 * User model.
 *
 * @author David Lõssenko <lysenkodavid@gmail.com>
 */
class User extends Model
{
    /**
     * @var Type
     */
    private $userType;

    /**
     * @var Speciality|null
     */
    private $userSpeciality;

    /**
     * This var is used only when the user is a DOCTOR.
     * Meaning that this array stores the registered appointments with clients.
     *
     * @var array
     */
    private $appointmentsWithPatients;

    /**
     * This var is only used when the user is a PATIENT.
     * Meaning that this array stores the registered appointments with doctors.
     *
     * @var array
     */
    private $appointmentsWithDoctors;

    /**
     * @var string Name of the table.
     */
    protected static $table = 'SYSUSER';

    /**
     * @var array List of column names.
     */
    protected static $columns = [
        'id',
        'username',
        'password',
        'first_name',
        'last_name',
        'email',
        'type',
        'speciality'
    ];

    /**
     * @var string Name of the primary key column.
     */
    protected static $primaryKey = 'id';


    /**
     * Gets the type model of this user.
     *
     * @throws \Exception
     */
    public function userType()
    {
        if (!isset($this->userType)) {
            $this->userType = Type::find($this->type);
        }

        return $this->userType;
    }

    /**
     * Gets the specialty model of this user.
     *
     * @throws \Exception
     */
    public function userSpeciality()
    {
        if (!isset($this->userSpeciality) && $this->speciality !== null) {
            $this->userSpeciality = Speciality::find($this->speciality);
        }

        return $this->userSpeciality;
    }

    /**
     * Gets appointments of a doctor.
     *
     * @throws \Exception
     */
    public function appointmentsWithPatients()
    {
        if ($this->userType()->id != 2) {
            throw new \Exception('This user is not a doctor!');
        }

        if (!isset($this->appointmentsWithPatients)) {
            $this->appointmentsWithPatients = Appointment::where('doctor=?', [$this->id]);
        }

        return $this->appointmentsWithPatients;
    }

    /**
     * Gets appointments of a patient.
     *
     * @throws \Exception
     */
    public function appointmentsWithDoctors()
    {
        if ($this->userType()->id != 3) {
            throw new \Exception('This user is not a patient!');
        }

        if (!isset($this->appointmentsWithDoctors)) {
            $this->appointmentsWithDoctors = Appointment::where('patient=?', [$this->id]);
        }

        return $this->appointmentsWithDoctors;
    }
}
