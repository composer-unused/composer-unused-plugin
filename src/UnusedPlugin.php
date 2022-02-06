<?php

declare(strict_types=1);

namespace ComposerUnused\ComposerUnusedPlugin;

use Composer\Command\BaseCommand;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin;
use Exception;
use _ComposerUnused_\ComposerUnused\ComposerUnused\Console\Command\UnusedCommand;
use Phar;

final class UnusedPlugin implements Plugin\PluginInterface, Plugin\Capable, Plugin\Capability\CommandProvider
{
    private $container;

    /**
     * @param mixed ...$args
     */
    public function __construct(...$args)
    {
        if (!empty($args)) {
            /** @var self $plugin */
            $plugin = $args[0]['plugin'];

            $this->container = $plugin->container;
        }
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
        $pharPath = __DIR__ . '/../composer-unused.phar';
        Phar::loadPhar($pharPath, 'composer-unused.phar');
        $this->container = require 'phar://composer-unused.phar/config/container.php';
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }

    public function getCapabilities(): array
    {
        return [
            Plugin\Capability\CommandProvider::class => self::class
        ];
    }

    /**
     * @return array|BaseCommand[]
     * @throws Exception
     */
    public function getCommands(): array
    {
        return [
            new UnusedPluginCommand($this->container->get(UnusedCommand::class))
        ];
    }
}
