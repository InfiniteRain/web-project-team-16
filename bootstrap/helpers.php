<?php

use WebTech\Hospital\Session;

/**
 * Gets whether or not a field has an error associated with itself.
 *
 * @param $field
 * @return bool
 */
function v_has_error($field)
{
    return isset(Session::getRedirectData()['validationErrors'][$field]);
}

/**
 * Gets the error message attached to a field.
 *
 * @param $field
 * @return string
 */
function v_get_error($field)
{
    return v_has_error($field) ? Session::getRedirectData()['validationErrors'][$field][0] : '';
}

/**
 * Gets the old value of an input.
 *
 * @param $field
 * @return string
 */
function old_val($field)
{
    return isset(Session::getRedirectData()['oldRequest'][$field])
        ? Session::getRedirectData()['oldRequest'][$field]
        : '';
}

/**
 * Gets the currently logged in user, or false if there is no logged in user.
 *
 * @return bool|\WebTech\Hospital\Models\User
 */
function user()
{
    return Session::user();
}