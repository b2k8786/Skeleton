<?php

namespace App\controllers;

use \App\BaseController;

class Test extends BaseController
{

    function info()
    {
        phpinfo();
    }
    function new()
    {
        $user = new \App\models\Users();
        // $data = $user->getAll();
        echo $user->username;
        // _dump($data);
    }


    function home()
    {
        $this->view('demo');
    }
    function stream()
    {
        $session = $_SESSION;
        session_write_close();

        header("Content-Type: text/event-stream");
        header("Cache-Control: no-store");
        header("Access-Control-Allow-Origin: *");

        while ($i) {
            echo "id: " . date('s') . "\n";
            echo "data:" . json_encode($session) . "\n";
            echo "data: " . date('h:i:s A') . "\n\n";
            ob_flush();
            flush();
            sleep(1);
        }
    }
}
