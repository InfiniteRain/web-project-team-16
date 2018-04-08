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
        'datetime'
    ];

    /**
     * @var string Name of the primary key column.
     */
    protected static $primaryKey = 'id';
}
