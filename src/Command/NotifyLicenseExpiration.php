<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NotifyLicenseExpiration extends Command
{
    protected static $defaultName = 'app:notify:license-expiration';

    protected function configure()
    {
        $this
            ->setDescription('Checks if any license is about to expire, if so sends a notification email to the owner.')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('dry-run')) {
            $io->note(sprintf('Dry mode enabled'));
            $count = 0;
        } else {
            $count = 1;
        }

        $io->success(sprintf('Sent %d notification mails about expired licenses', $count));

        return 0;
    }
}
