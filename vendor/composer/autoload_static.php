<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita8e84f6b9d979c9414ec52a30474a2ce
{
    public static $classMap = array (
        'Admin' => __DIR__ . '/../..' . '/src/Admin.php',
        'ApiTest' => __DIR__ . '/..' . '/scssphp/tests/ApiTest.php',
        'ExceptionTest' => __DIR__ . '/..' . '/scssphp/tests/ExceptionTest.php',
        'I18n' => __DIR__ . '/../..' . '/src/I18n.php',
        'InputTest' => __DIR__ . '/..' . '/scssphp/tests/InputTest.php',
        'Loader' => __DIR__ . '/../..' . '/src/Loader.php',
        'Main' => __DIR__ . '/../..' . '/src/Main.php',
        'PublicClass' => __DIR__ . '/../..' . '/src/PublicClass.php',
        'scss_formatter' => __DIR__ . '/..' . '/scssphp/scss.inc.php',
        'scss_formatter_compressed' => __DIR__ . '/..' . '/scssphp/scss.inc.php',
        'scss_formatter_nested' => __DIR__ . '/..' . '/scssphp/scss.inc.php',
        'scss_parser' => __DIR__ . '/..' . '/scssphp/scss.inc.php',
        'scss_server' => __DIR__ . '/..' . '/scssphp/scss.inc.php',
        'scssc' => __DIR__ . '/..' . '/scssphp/scss.inc.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInita8e84f6b9d979c9414ec52a30474a2ce::$classMap;

        }, null, ClassLoader::class);
    }
}