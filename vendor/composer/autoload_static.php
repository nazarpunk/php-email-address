<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdd264c22d83f1e78049e37612f0d176d
{
    public static $files = array (
        '9fa8f017e3c3820504ace2d39268f2b8' => __DIR__ . '/../..' . '/src/RandomPassword/Password.php',
    );

    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'EmailAdress\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'EmailAdress\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdd264c22d83f1e78049e37612f0d176d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdd264c22d83f1e78049e37612f0d176d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdd264c22d83f1e78049e37612f0d176d::$classMap;

        }, null, ClassLoader::class);
    }
}
