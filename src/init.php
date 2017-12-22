<?php

namespace LwtHelper;

class Loader
{

    const NAMEPRE = 'LwtHelper';

    public static function loadClass($class)
    {
        $filePath = str_replace(self::NAMEPRE, '', __DIR__ . $class . '.php');

        $file = str_replace('\\', '/', $filePath);

        if (is_file($file)) {
            require_once($file);
        }
        
    }

}

spl_autoload_register(array('LwtHelper\Loader', 'loadClass'));
//spl_autoload_register('Loader::loadClass');
