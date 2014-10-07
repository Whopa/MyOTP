<?php
/**
 * Created by PhpStorm.
 * User: framato
 * Date: 07/10/14
 * Time: 15:32
 */

namespace Whopa\Myotp\Facades;
use Illuminate\Support\Facades\Facade;

class Myotp extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'myotp';
    }
} 