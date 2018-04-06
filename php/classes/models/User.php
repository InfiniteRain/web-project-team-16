<?php

require_once __DIR__ . '/../Model.php';

class User extends Model
{
    /**
     * @var string
     */
    protected static $table = 'SYSUSER';

    /**
     * @var array
     */
    protected static $columns = [
        'id', 'username', 'password', 'first_name', 'last_name', 'email', 'type', 'speciality'
    ];

    /**
     * @var string
     */
    protected static $primaryKey = 'id';
}
