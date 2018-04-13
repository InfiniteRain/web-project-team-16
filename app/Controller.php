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
    protected function view(string $_path, array $_data = [])
    {
        ob_start();

        foreach ($_data as $_k => $_v) {
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
    protected function json(array $data)
    {
        return json_encode($data);
    }
}