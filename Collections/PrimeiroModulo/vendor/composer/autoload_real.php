<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit802866a3e97b93b70c41ce844fe91a33
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit802866a3e97b93b70c41ce844fe91a33', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit802866a3e97b93b70c41ce844fe91a33', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit802866a3e97b93b70c41ce844fe91a33::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}