<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb4b1cc862a6151a307d815abfab88814
{
    public static $prefixesPsr0 = array (
        'j' => 
        array (
            'jc21' => 
            array (
                0 => __DIR__ . '/..' . '/jc21/clitable/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInitb4b1cc862a6151a307d815abfab88814::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitb4b1cc862a6151a307d815abfab88814::$classMap;

        }, null, ClassLoader::class);
    }
}
