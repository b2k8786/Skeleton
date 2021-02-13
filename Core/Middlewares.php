<?php

namespace Core;

class Middlewares{

    function initialLoad()
    {
        return [
            \Core\SupORM::class
        ];
    }

}