<?php

declare(strict_types=1);

namespace App\UI\Console;

use App\Common\Exception\BadRequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;



class CreateAllAccountCommand extends Command
{
    protected static $defaultName = 'create:all';

    protected function configure()
    {
        $this->setDescription('Initilalise tous les comptes nécessaires');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplication();
        if (null === $application) {
            throw new BadRequestException("Une erreur est survenue lors de l'initialisation de la bdd", null);
        }

        $command = $application->find('d:d:d');

        $arguments = [
            '-q' => true,
            '--force' => true,
        ];
        $commandInput = new ArrayInput($arguments);
        $command->run($commandInput, $output);

        // create database
        $command = $application->find('d:d:c');
        $arguments = [];
        $commandInput = new ArrayInput($arguments);
        $command->run($commandInput, $output);

        // apply migrations
        $command = $application->find('d:m:m');
        $arguments = [
            '--no-interaction' => true,
        ];
        $commandInput = new ArrayInput($arguments);
        $command->run($commandInput, $output);

        // create contact
        $command = $application->find('create:contact');
        $arguments = [
            '--no-interaction' => true,
        ];
        $commandInput = new ArrayInput($arguments);
        $command->run($commandInput, $output);

        // create system
        $command = $application->find('create:system');
        $arguments = [
            '--no-interaction' => true,
        ];
        $commandInput = new ArrayInput($arguments);
        $command->run($commandInput, $output);

        // create director
        $command = $application->find('create:director');
        $arguments = [
            '--no-interaction' => true,
        ];
        $commandInput = new ArrayInput($arguments);
        $command->run($commandInput, $output);

        // create owner
        $command = $application->find('create:owner');
        $arguments = [
            '--no-interaction' => true,
        ];
        $commandInput = new ArrayInput($arguments);
        $command->run($commandInput, $output);

        // create admin
        $command = $application->find('create:admin');
        $arguments = [
            '--no-interaction' => true,
        ];
        $commandInput = new ArrayInput($arguments);
        $command->run($commandInput, $output);
    }
}
