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
}
