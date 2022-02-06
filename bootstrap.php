<?php
declare(strict_types=1);

namespace ComposerUnused;

use Composer\Autoload\ClassLoader;

final class PharAutoloader
{
    /** @var ClassLoader */
    private static $composerAutoloader;

    final public static function loadClass(string $class): void
    {
        if (!extension_loaded('phar')/* || defined('__PHPSTAN_RUNNING__')*/) {
            return;
        }

        if (strpos($class, '_ComposerUnused_') === 0) {
            if (!in_array('phar', stream_get_wrappers(), true)) {
                throw new \Exception('Phar wrapper is not registered. Please review your php.ini settings.');
            }

            if (self::$composerAutoloader === null) {
                self::$composerAutoloader = require 'phar://' . __DIR__ . '/composer-unused.phar/vendor/autoload.php';
            }
            self::$composerAutoloader->loadClass($class);

            return;
        }
        if (strpos($class, 'ComposerUnused\\') !== 0) {
            return;
        }

        if (!in_array('phar', stream_get_wrappers(), true)) {
            throw new \Exception('Phar wrapper is not registered. Please review your php.ini settings.');
        }

        $filename = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $filename = substr($filename, strlen('ComposerUnused\\'));
        $filepath = 'phar://' . __DIR__ . '/composer-unused.phar/src/' . $filename . '.php';

        if (!file_exists($filepath)) {
            return;
        }

        require $filepath;
    }
}

spl_autoload_register([PharAutoloader::class, 'loadClass']);
