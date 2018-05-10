<?php

namespace WebTech\Hospital;

/**
 * Controller class.
 *
 * @author David Lõssenko <lysenkodavid@gmail.com>
 */
class Controller
{
    /**
     * Returns the view as a string.
     *
     * @param string $_path
     * @param array $_data
     * @return string
     */
    public function view(string $_path, array $_data = [])
    {
        ob_start();

        foreach (array_merge(Session::getRedirectData(), $_data) as $_k => $_v) {
            $$_k = $_v;
        }

        include __DIR__ . '/../views/' . str_replace('.', '/', $_path) . '.php';

        return ob_get_clean();
    }

    /**
     * Returns JSON from passed data.
     *
     * @param array $data
     * @return string
     */
    public function json(array $data)
    {
        return json_encode($data);
    }

    /**
     * Redirects the client to another URI.
     *
     * @param string $uri
     * @param array $data
     */
    public function redirect(string $uri, array $data = [])
    {
        if (isset($data['validationErrors']) && count($data['validationErrors']) > 0) {
            Session::setRedirectData(array_merge($data, ['oldRequest' => $_REQUEST]));
        } else {
            Session::setRedirectData(array_merge($data));
        }

        header('Location: ' . $uri);
        die();
    }

    /**
     * Redirects the client to its http referer.
     *
     * @param array $data
     */
    public function redirectBack(array $data = [])
    {
        if (isset($data['validationErrors']) && count($data['validationErrors']) > 0) {
            Session::setRedirectData(array_merge($data, ['oldRequest' => $_REQUEST]));
        } else {
            Session::setRedirectData(array_merge($data));
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }

    /**
     * Validates request data.
     *
     * @param array $data
     * @param array $rules
     * @throws \Exception
     */
    public function validate(array $data, array $rules)
    {
        $validator = new Validator($data, $rules);
        $validator->passOrFail($this);
    }
}
