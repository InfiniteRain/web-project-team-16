<?php

require_once __DIR__ . '../Database.php';
require_once __DIR__ . '../Model.php';

class User extends Model
{
    /**
     * @var string
     */
    protected $table = 'SYSUSER';

    /**
     * @var array
     */
    protected $columns = ['id', 'username', 'password', 'first_name', 'last_name', 'email', 'type', 'speciality'];

    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
