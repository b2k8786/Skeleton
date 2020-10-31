<?php

/**
 * @property Array $request Holds raw request parameters as array
 */

namespace Core;

class Controller
{

    function view($view, $variables = null)
    {
        if (!empty($variables)) {
            extract($variables);
        }
        return include VIEW_PATH . $view . '.php';
    }

    function rawInput()
    {
        parse_str(file_get_contents('php://input'), $this->request);
    }
}
