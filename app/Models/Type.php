<?php

namespace WebTech\Hospital\Models;

use WebTech\Hospital\Model;

/**
 * Type model.
 *
 * @author David Lõssenko <lysenkodavid@gmail.com>
 */
class Type extends Model
{
    /**
     * @var string Name of the table.
     */
    protected static $table = 'TYPE';

    /**
     * @var array List of column names.
     */
    protected static $columns = [
        'id',
        'name'
    ];

    /**
     * @var string Name of the primary key column.
     */
    protected static $primaryKey = 'id';
}
