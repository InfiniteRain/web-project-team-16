<?php

require_once __DIR__ . '/../Model.php';

/**
 * User model.
 *
 * @author David Lõssenko <lysenkodavid@gmail.com>
 */
class User extends Model
{
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
}
