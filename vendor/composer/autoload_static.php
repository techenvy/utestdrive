<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf953438782c78e200b21ad2e083164a7
{
    public static $files = array (
        '9791399aa8c96e69bfb124b41ba4a79f' => __DIR__ . '/..' . '/boospot/boo-settings-helper/class-boo-settings-helper.php',
    );

    public static $classMap = array (
        'Utestdrive\\Activator' => __DIR__ . '/../..' . '/includes/class-activator.php',
        'Utestdrive\\Admin' => __DIR__ . '/../..' . '/admin/class-admin.php',
        'Utestdrive\\Deactivator' => __DIR__ . '/../..' . '/includes/class-deactivator.php',
        'Utestdrive\\Front' => __DIR__ . '/../..' . '/public/class-front.php',
        'Utestdrive\\Globals' => __DIR__ . '/../..' . '/includes/class-globals.php',
        'Utestdrive\\Init' => __DIR__ . '/../..' . '/includes/class-init.php',
        'Utestdrive\\Loader' => __DIR__ . '/../..' . '/includes/class-loader.php',
        'Utestdrive\\Site_Create' => __DIR__ . '/../..' . '/site/class-site-create.php',
        'Utestdrive\\Taxonomy' => __DIR__ . '/../..' . '/includes/class-taxonomy.php',
        'Utestdrive\\i18N' => __DIR__ . '/../..' . '/includes/class-i18n.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitf953438782c78e200b21ad2e083164a7::$classMap;

        }, null, ClassLoader::class);
    }
}
