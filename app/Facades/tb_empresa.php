<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class tb_empresa extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tb_empresa';
    }
}
