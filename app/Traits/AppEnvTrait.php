<?php

namespace App\Traits;

trait AppEnvTrait{

    function returnAppEnviroment(){
        return env('APP_ENV', 'local');
    }

}
