<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit45363e398a2e1d2bcb367dc88b86e491
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Enigma\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Enigma\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib/Enigma',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit45363e398a2e1d2bcb367dc88b86e491::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit45363e398a2e1d2bcb367dc88b86e491::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}