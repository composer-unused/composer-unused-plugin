<?php

declare(strict_types=1);

namespace ComposerUnused\ComposerUnusedPlugin;

use Composer\Command\BaseCommand;
use ComposerUnused\ComposerUnused\Console\Command\UnusedCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class UnusedPluginCommand extends BaseCommand
{
    private UnusedCommand $command;

    public function __construct(UnusedCommand $command)
    {
        $this->command = $command;
        parent::__construct('unused');
    }

    protected function configure(): void
    {
        $this->setDefinition($this->command->getDefinition());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('<fg=cyan>Running composer-unused version ' . UnusedCommand::VERSION . '</>');

        return $this->command->run($input, $output);
    }
}
